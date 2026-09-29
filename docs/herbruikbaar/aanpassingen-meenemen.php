<?php
/**
 * Aanpassingen meenemen: pagina's opnieuw opbouwen zonder eigen werk te verliezen.
 *
 * Losstaand onderdeel voor een WordPress-thema of -plugin. Het thema zet
 * pagina's klaar als blokken (bijvoorbeeld uit patronen). Later wil je die
 * pagina's opnieuw opbouwen met een nieuwe opmaak, zonder dat verloren gaat wat
 * de klant zelf in de editor heeft veranderd.
 *
 * Werking in het kort:
 * 1. Bij het opbouwen krijgt elk los blok een vast kenmerk in attrs.metadata.
 *    Groepen krijgen een merkje, zodat ze nooit voor een eigen blok doorgaan.
 * 2. De pagina onthoudt per kenmerk een vingerafdruk van hoe het blok eruitzag.
 * 3. Bij opnieuw opbouwen:
 *    - blok anders dan de vingerafdruk  -> het blok van de klant gaat mee;
 *    - blok met kenmerk verdwenen       -> blijft weg in de nieuwe opmaak;
 *    - blok zonder kenmerk (zelf gemaakt) -> komt achter hetzelfde blok als eerst;
 *    - wat nergens meer past            -> wordt geteld en gemeld.
 *    De huidige versie gaat altijd eerst de revisies in.
 *
 * Gebruik:
 *   require_once __DIR__ . '/aanpassingen-meenemen.php';
 *
 *   // Nieuwe pagina aanmaken:
 *   $id = amm_pagina_aanmaken( array( 'post_title' => 'Over ons', 'post_name' => 'over-ons' ), $blokopmaak, 'over-ons' );
 *
 *   // Later opnieuw opbouwen:
 *   $verslag = amm_opnieuw_opbouwen( $id, $nieuwe_blokopmaak, 'over-ons' );
 *   echo amm_verslag_tekst( $verslag );
 *
 *   // Is de pagina door de klant aangepast?
 *   amm_pagina_stand( $id ); // 'origineel', 'aangepast' of 'onbekend'
 *
 * Wil je andere namen (bijvoorbeeld twee thema's met dit onderdeel op één
 * site), definieer dan vóór het inladen AMM_META en AMM_SLEUTEL.
 *
 * @package AanpassingenMeenemen
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'AMM_META' ) ) {
	/** Post meta met kenmerk => vingerafdruk. */
	define( 'AMM_META', '_amm_blokken' );
}

if ( ! defined( 'AMM_SLEUTEL' ) ) {
	/** Naam van het kenmerk in attrs.metadata van een blok. */
	define( 'AMM_SLEUTEL', 'amm' );
}

/**
 * Vingerafdruk van een blok: tekst, links, foto's en instellingen.
 *
 * Bewust zonder alles wat de editor bij opslaan anders kan wegschrijven terwijl
 * de klant niets veranderde:
 * - class- en style-attributen in de HTML;
 * - className, style, lock en metadata in de instellingen;
 * - instellingen die op hun standaardwaarde staan (de editor laat die weg);
 * - lege instellingen;
 * - verschil in witruimte en in hoe tekens zijn geschreven (&amp; of &).
 *
 * @param array $blok Een blok uit parse_blocks().
 * @return string
 */
function amm_vingerafdruk( $blok ) {
	$html  = preg_replace( '/\s(class|style)="[^"]*"/', '', (string) $blok['innerHTML'] );
	$html  = trim( preg_replace( '/\s+/', ' ', $html ) );
	$html  = html_entity_decode( $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$attrs = is_array( $blok['attrs'] ) ? $blok['attrs'] : array();

	unset( $attrs['metadata'], $attrs['className'], $attrs['style'], $attrs['lock'] );

	$soort = WP_Block_Type_Registry::get_instance()->get_registered( (string) $blok['blockName'] );

	foreach ( $attrs as $naam => $waarde ) {
		$standaard = $soort && isset( $soort->attributes[ $naam ] ) && array_key_exists( 'default', $soort->attributes[ $naam ] ) ? $soort->attributes[ $naam ]['default'] : '';

		if ( $waarde === $standaard || '' === $waarde ) {
			unset( $attrs[ $naam ] );
		}
	}

	ksort( $attrs );

	return md5( $blok['blockName'] . '|' . $html . '|' . wp_json_encode( $attrs ) );
}

/**
 * Het kenmerk van een blok, of een lege tekst.
 *
 * @param array $blok Een blok.
 * @return string
 */
function amm_sleutel( $blok ) {
	return isset( $blok['attrs']['metadata'][ AMM_SLEUTEL ] ) ? (string) $blok['attrs']['metadata'][ AMM_SLEUTEL ] : '';
}

/**
 * Of een blok een groep is die het thema zelf heeft gemaakt.
 *
 * @param array $blok Een blok.
 * @return bool
 */
function amm_is_groep( $blok ) {
	return ! empty( $blok['attrs']['metadata'][ AMM_SLEUTEL . 'groep' ] );
}

/**
 * Geeft elk los blok een vast kenmerk en elke groep een merkje.
 *
 * Het kenmerk komt uit: de pagina ($slug), het soort blok, de standaardtekst
 * (of bij een blok zonder tekst de instellingen) en een volgnummer voor
 * dubbele. Blijft de standaardtekst in een nieuwe versie gelijk, dan blijft het
 * kenmerk gelijk en kan een aanpassing terug op zijn plek.
 *
 * @param string $inhoud Blokopmaak.
 * @param string $slug   Naam van de pagina; maakt kenmerken uniek per pagina.
 * @return array array( 'inhoud' => string, 'kaart' => array( kenmerk => vingerafdruk ) ).
 */
function amm_sleutels_toevoegen( $inhoud, $slug ) {
	$tellers = array();
	$kaart   = array();
	$blokken = amm_sleutels_in( parse_blocks( $inhoud ), $slug, $tellers, $kaart );

	return array(
		'inhoud' => serialize_blocks( $blokken ),
		'kaart'  => $kaart,
	);
}

/**
 * Hulpje van amm_sleutels_toevoegen(): loopt door de blokken.
 *
 * @param array  $blokken Blokken.
 * @param string $slug    Pagina.
 * @param array  $tellers Hoe vaak een basis al voorkwam.
 * @param array  $kaart   Kenmerk => vingerafdruk.
 * @return array
 */
function amm_sleutels_in( $blokken, $slug, &$tellers, &$kaart ) {
	foreach ( $blokken as $i => $blok ) {
		if ( empty( $blok['blockName'] ) ) {
			continue; // Witruimte tussen blokken.
		}

		if ( ! isset( $blokken[ $i ]['attrs']['metadata'] ) || ! is_array( $blokken[ $i ]['attrs']['metadata'] ) ) {
			$blokken[ $i ]['attrs']['metadata'] = array();
		}

		if ( ! empty( $blok['innerBlocks'] ) ) {
			$blokken[ $i ]['attrs']['metadata'][ AMM_SLEUTEL . 'groep' ] = true;
			$blokken[ $i ]['innerBlocks'] = amm_sleutels_in( $blok['innerBlocks'], $slug, $tellers, $kaart );
			continue;
		}

		$tekst = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $blok['innerHTML'] ) ) );
		$attrs = $blok['attrs'];
		unset( $attrs['metadata'] );
		$basis = substr( md5( $slug . '|' . $blok['blockName'] . '|' . $tekst . '|' . ( '' === $tekst ? wp_json_encode( $attrs ) : '' ) ), 0, 10 );

		$tellers[ $basis ] = isset( $tellers[ $basis ] ) ? $tellers[ $basis ] + 1 : 1;
		$sleutel           = $basis . '-' . $tellers[ $basis ];

		$blokken[ $i ]['attrs']['metadata'][ AMM_SLEUTEL ] = $sleutel;
		$kaart[ $sleutel ] = amm_vingerafdruk( $blokken[ $i ] );
	}

	return $blokken;
}

/**
 * Maakt een pagina aan met kenmerken, zodat aanpassingen later mee kunnen.
 *
 * @param array  $post   Velden voor wp_insert_post() (zonder post_content).
 * @param string $inhoud Blokopmaak.
 * @param string $slug   Naam voor de kenmerken; meestal gelijk aan post_name.
 * @return int|WP_Error
 */
function amm_pagina_aanmaken( $post, $inhoud, $slug ) {
	$vers = amm_sleutels_toevoegen( $inhoud, $slug );
	$id   = wp_insert_post(
		array_merge(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
			),
			$post,
			array( 'post_content' => wp_slash( $vers['inhoud'] ) ) // wp_insert_post haalt slashes weg; zonder wp_slash breekt JSON in de blokken.
		),
		true
	);

	if ( ! is_wp_error( $id ) && $id ) {
		update_post_meta( $id, AMM_META, $vers['kaart'] );
	}

	return $id;
}

/**
 * Leest de huidige pagina: welke blokken zijn anders, welke zijn gezien, welke
 * heeft de klant zelf gemaakt (en waar stonden die achter).
 *
 * @param array  $blokken Blokken.
 * @param array  $kaart   Kenmerk => vingerafdruk van toen.
 * @param array  $uit     array( 'gezien' => [], 'anders' => [], 'eigen' => [] ).
 * @param string $vorige  Laatste kenmerk tot nu toe.
 */
function amm_oud_lezen( $blokken, $kaart, &$uit, &$vorige ) {
	foreach ( $blokken as $blok ) {
		if ( empty( $blok['blockName'] ) ) {
			continue;
		}

		$sleutel = amm_sleutel( $blok );
		$bekend  = '' !== $sleutel && isset( $kaart[ $sleutel ] ) && ! isset( $uit['gezien'][ $sleutel ] );

		// Een los blok van het thema. Een gedupliceerd blok heeft hetzelfde
		// kenmerk; alleen de eerste telt, de kopie geldt als eigen blok.
		if ( $bekend && empty( $blok['innerBlocks'] ) ) {
			$uit['gezien'][ $sleutel ] = true;

			if ( amm_vingerafdruk( $blok ) !== $kaart[ $sleutel ] ) {
				$uit['anders'][ $sleutel ] = $blok;
			}

			$vorige = $sleutel;
			continue;
		}

		if ( amm_is_groep( $blok ) || ( ! empty( $blok['innerBlocks'] ) && amm_heeft_sleutel( $blok['innerBlocks'], $kaart ) ) ) {
			amm_oud_lezen( $blok['innerBlocks'], $kaart, $uit, $vorige );
			continue;
		}

		$uit['eigen'][] = array(
			'na'   => $vorige,
			'blok' => $blok,
		);
	}
}

/**
 * Of er ergens in deze blokken een blok van het thema zit.
 *
 * @param array $blokken Blokken.
 * @param array $kaart   Kenmerken.
 * @return bool
 */
function amm_heeft_sleutel( $blokken, $kaart ) {
	foreach ( $blokken as $blok ) {
		$sleutel = amm_sleutel( $blok );

		if ( ( '' !== $sleutel && isset( $kaart[ $sleutel ] ) ) || ( ! empty( $blok['innerBlocks'] ) && amm_heeft_sleutel( $blok['innerBlocks'], $kaart ) ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Zet de aanpassingen in de nieuwe blokken.
 *
 * @param array $blokken Nieuwe blokken (met kenmerken).
 * @param array $oud     Uitkomst van amm_oud_lezen().
 * @param array $weg     Kenmerken die de klant heeft weggehaald.
 * @param array $gedaan  Wat er gelukt is.
 * @return array
 */
function amm_nieuw_invullen( $blokken, $oud, $weg, &$gedaan ) {
	$uit = array();

	foreach ( $blokken as $blok ) {
		$sleutel = amm_sleutel( $blok );

		if ( ! empty( $blok['blockName'] ) && empty( $blok['innerBlocks'] ) && '' !== $sleutel ) {
			if ( isset( $weg[ $sleutel ] ) ) {
				$gedaan['weg'][] = $sleutel;
				continue;
			}

			if ( isset( $oud['anders'][ $sleutel ] ) ) {
				$blok                    = $oud['anders'][ $sleutel ];
				$gedaan['overgenomen'][] = $sleutel;
			}

			$uit[] = $blok;

			foreach ( $oud['eigen'] as $n => $eigen ) {
				if ( $eigen['na'] === $sleutel ) {
					$uit[]                       = $eigen['blok'];
					$gedaan['eigen_geplaatst'][] = $n;
				}
			}

			continue;
		}

		if ( ! empty( $blok['innerBlocks'] ) ) {
			$blok = amm_binnen_vervangen( $blok, amm_nieuw_invullen( $blok['innerBlocks'], $oud, $weg, $gedaan ) );
		}

		$uit[] = $blok;
	}

	return $uit;
}

/**
 * Vervangt de binnenblokken van een groep.
 *
 * serialize_block() zet elk binnenblok op de plek van een null in
 * innerContent. Komen er blokken bij of gaan er af, dan moet het aantal nulls
 * meeveranderen, anders valt er een blok weg of breekt de HTML.
 *
 * @param array $blok   De groep.
 * @param array $binnen Nieuwe binnenblokken.
 * @return array
 */
function amm_binnen_vervangen( $blok, $binnen ) {
	$verschil = count( $binnen ) - count( $blok['innerBlocks'] );
	$inhoud   = $blok['innerContent'];

	if ( $verschil > 0 ) {
		$laatste = null;

		foreach ( $inhoud as $n => $stuk ) {
			if ( null === $stuk ) {
				$laatste = $n;
			}
		}

		array_splice( $inhoud, null === $laatste ? 1 : $laatste + 1, 0, array_fill( 0, $verschil, null ) );
	} elseif ( $verschil < 0 ) {
		for ( $n = count( $inhoud ) - 1; $n >= 0 && $verschil < 0; $n-- ) {
			if ( null === $inhoud[ $n ] ) {
				array_splice( $inhoud, $n, 1 );
				++$verschil;
			}
		}
	}

	$blok['innerBlocks']  = $binnen;
	$blok['innerContent'] = $inhoud;

	return $blok;
}

/**
 * Bouwt een pagina opnieuw op en neemt de aanpassingen van de klant mee.
 *
 * @param int    $id    Pagina.
 * @param string $nieuw Nieuwe blokopmaak, zonder kenmerken (die komen er hier bij).
 * @param string $slug  Zelfde naam als bij het aanmaken.
 * @return array overgenomen, weg, eigen, niet_geplaatst (aantallen) en bekend (bool).
 */
function amm_opnieuw_opbouwen( $id, $nieuw, $slug ) {
	$vers  = amm_sleutels_toevoegen( $nieuw, $slug );
	$kaart = get_post_meta( $id, AMM_META, true );
	$staat = array(
		'overgenomen'    => 0,
		'weg'            => 0,
		'eigen'          => 0,
		'niet_geplaatst' => 0,
		'bekend'         => is_array( $kaart ) && $kaart,
	);

	// Altijd eerst de huidige versie bewaren.
	wp_save_post_revision( $id );

	if ( $staat['bekend'] ) {
		$oud    = array(
			'gezien' => array(),
			'anders' => array(),
			'eigen'  => array(),
		);
		$vorige = '';
		amm_oud_lezen( parse_blocks( (string) get_post_field( 'post_content', $id, 'raw' ) ), $kaart, $oud, $vorige );

		$weg     = array_diff_key( $kaart, $oud['gezien'] );
		$gedaan  = array(
			'overgenomen'     => array(),
			'weg'             => array(),
			'eigen_geplaatst' => array(),
		);
		$blokken = parse_blocks( $vers['inhoud'] );
		$begin   = array();

		// Eigen blokken helemaal bovenaan, vóór het eerste blok van het thema.
		foreach ( $oud['eigen'] as $n => $eigen ) {
			if ( '' === $eigen['na'] ) {
				$begin[]                     = $eigen['blok'];
				$gedaan['eigen_geplaatst'][] = $n;
			}
		}

		$vers['inhoud'] = serialize_blocks( array_merge( $begin, amm_nieuw_invullen( $blokken, $oud, $weg, $gedaan ) ) );

		$staat['overgenomen']    = count( array_unique( $gedaan['overgenomen'] ) );
		$staat['weg']            = count( array_unique( $gedaan['weg'] ) );
		$staat['eigen']          = count( $oud['eigen'] );
		$staat['niet_geplaatst'] = count( $oud['anders'] ) - $staat['overgenomen'] + count( $oud['eigen'] ) - count( array_unique( $gedaan['eigen_geplaatst'] ) );
	}

	wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => wp_slash( $vers['inhoud'] ),
		)
	);

	// De vingerafdrukken blijven die van het thema, niet die van de klant.
	// Zo telt een aanpassing de volgende keer weer als aanpassing.
	update_post_meta( $id, AMM_META, $vers['kaart'] );

	return $staat;
}

/**
 * Of de klant een pagina heeft aangepast sinds het thema hem opbouwde.
 *
 * @param int $id Pagina.
 * @return string origineel, aangepast of onbekend (nog geen kenmerken).
 */
function amm_pagina_stand( $id ) {
	$kaart = get_post_meta( $id, AMM_META, true );

	if ( ! is_array( $kaart ) || ! $kaart ) {
		return 'onbekend';
	}

	$oud    = array(
		'gezien' => array(),
		'anders' => array(),
		'eigen'  => array(),
	);
	$vorige = '';
	amm_oud_lezen( parse_blocks( (string) get_post_field( 'post_content', $id, 'raw' ) ), $kaart, $oud, $vorige );

	return $oud['anders'] || $oud['eigen'] || array_diff_key( $kaart, $oud['gezien'] ) ? 'aangepast' : 'origineel';
}

/**
 * Het verslag van één pagina als leesbare zin.
 *
 * @param array $staat Uitkomst van amm_opnieuw_opbouwen().
 * @return string
 */
function amm_verslag_tekst( $staat ) {
	if ( ! $staat['bekend'] ) {
		return 'Eerste keer opgebouwd met kenmerken: vanaf nu gaan aanpassingen mee.';
	}

	$delen = array();

	if ( $staat['overgenomen'] ) {
		$delen[] = sprintf( '%d %s meegenomen', $staat['overgenomen'], 1 === $staat['overgenomen'] ? 'aanpassing' : 'aanpassingen' );
	}

	if ( $staat['weg'] ) {
		$delen[] = sprintf( '%d weggehaald %s weg', $staat['weg'], 1 === $staat['weg'] ? 'blok blijft' : 'blokken blijven' );
	}

	if ( $staat['eigen'] ) {
		$delen[] = sprintf( '%d eigen %s', $staat['eigen'], 1 === $staat['eigen'] ? 'blok' : 'blokken' );
	}

	if ( $staat['niet_geplaatst'] > 0 ) {
		$delen[] = sprintf( 'let op: %d paste niet meer en staat alleen in de revisies', $staat['niet_geplaatst'] );
	}

	return $delen ? ucfirst( implode( ', ', $delen ) ) . '.' : 'Niets aangepast; nieuwe opmaak staat erin.';
}
