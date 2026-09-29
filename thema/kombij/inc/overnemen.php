<?php
/**
 * Eigen aanpassingen meenemen naar een nieuwe opmaak.
 *
 * Het thema zet de pagina's klaar als gewone blokken. Een nieuwe versie van het
 * thema kan die pagina's opnieuw opbouwen, maar dan mag niet verloren gaan wat
 * de eigenaren zelf hebben veranderd. Daarom krijgt elk los blok (een kop, een
 * alinea, een knop, een foto) bij het opbouwen een vast kenmerk, en onthoudt de
 * pagina hoe elk blok er toen uitzag.
 *
 * Bij opnieuw opbouwen:
 * - een blok dat anders is dan toen, gaat mee naar dezelfde plek in de nieuwe
 *   opmaak;
 * - een blok dat is weggehaald, blijft weg;
 * - een blok dat iemand zelf heeft toegevoegd, komt achter hetzelfde blok als
 *   waar het eerst achter stond.
 *
 * Wat nergens meer past, wordt gemeld. De oude versie staat dan nog in de
 * revisies, dus niets raakt echt kwijt.
 *
 * @package KomBij
 */

defined( 'ABSPATH' ) || exit;

/** Onder welke naam een pagina onthoudt hoe elk blok eruitzag. */
const KBJ_BLOKKEN_META = '_kbj_blokken';

/**
 * Een vingerafdruk van een blok: tekst, links, foto's en instellingen, zonder
 * de opmaakklassen. Zo telt alleen wat iemand echt heeft veranderd, niet hoe
 * de editor de blokopmaak toevallig wegschrijft.
 *
 * @param array $blok Een blok uit parse_blocks().
 * @return string
 */
function kbj_blok_vingerafdruk( $blok ) {
	$html = preg_replace( '/\s(class|style)="[^"]*"/', '', (string) $blok['innerHTML'] );
	$html = trim( preg_replace( '/\s+/', ' ', $html ) );
	$attrs = $blok['attrs'];

	unset( $attrs['metadata'], $attrs['className'], $attrs['style'], $attrs['lock'] );

	/*
	 * De editor schrijft een instelling die op de standaardwaarde staat niet
	 * weg. Dat is geen aanpassing, dus die tellen we hier ook niet mee.
	 */
	$soort = WP_Block_Type_Registry::get_instance()->get_registered( (string) $blok['blockName'] );

	foreach ( $attrs as $naam => $waarde ) {
		$standaard = $soort && isset( $soort->attributes[ $naam ] ) && array_key_exists( 'default', $soort->attributes[ $naam ] ) ? $soort->attributes[ $naam ]['default'] : '';

		if ( $waarde === $standaard || '' === $waarde ) {
			unset( $attrs[ $naam ] );
		}
	}

	// Tekst en links tellen, niet hoe de editor de tekens toevallig schrijft.
	$html = html_entity_decode( $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	ksort( $attrs );

	return md5( $blok['blockName'] . '|' . $html . '|' . wp_json_encode( $attrs ) );
}

/**
 * Het kenmerk van een blok, of een lege tekst.
 *
 * @param array $blok Een blok.
 * @return string
 */
function kbj_blok_sleutel( $blok ) {
	return isset( $blok['attrs']['metadata']['kbj'] ) ? (string) $blok['attrs']['metadata']['kbj'] : '';
}

/**
 * Geeft elk los blok een vast kenmerk.
 *
 * Het kenmerk komt uit de pagina, het soort blok en de standaardtekst. Blijft de
 * standaardtekst in een nieuwe versie gelijk, dan blijft het kenmerk gelijk, en
 * kan een aanpassing dus terug op zijn plek.
 *
 * @param string $inhoud Blokopmaak.
 * @param string $slug   De pagina.
 * @return array array( 'inhoud' => string, 'kaart' => array( kenmerk => vingerafdruk ) ).
 */
function kbj_sleutels_toevoegen( $inhoud, $slug ) {
	$tellers = array();
	$kaart   = array();
	$blokken = kbj_sleutels_in( parse_blocks( $inhoud ), $slug, $tellers, $kaart );

	return array(
		'inhoud' => serialize_blocks( $blokken ),
		'kaart'  => $kaart,
	);
}

/**
 * Loopt door de blokken en zet de kenmerken erin.
 *
 * @param array  $blokken Blokken.
 * @param string $slug    De pagina.
 * @param array  $tellers Hoe vaak een basis al voorkwam.
 * @param array  $kaart   Kenmerk => vingerafdruk.
 * @return array
 */
function kbj_sleutels_in( $blokken, $slug, &$tellers, &$kaart ) {
	foreach ( $blokken as $i => $blok ) {
		if ( empty( $blok['blockName'] ) ) {
			continue;
		}

		if ( ! empty( $blok['innerBlocks'] ) ) {
			// Een groep van het thema: gemerkt, zodat hij nooit voor een eigen blok doorgaat.
			if ( ! isset( $blokken[ $i ]['attrs']['metadata'] ) || ! is_array( $blokken[ $i ]['attrs']['metadata'] ) ) {
				$blokken[ $i ]['attrs']['metadata'] = array();
			}

			$blokken[ $i ]['attrs']['metadata']['kbjgroep'] = true;
			$blokken[ $i ]['innerBlocks']                   = kbj_sleutels_in( $blok['innerBlocks'], $slug, $tellers, $kaart );
			continue;
		}

		$tekst = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $blok['innerHTML'] ) ) );
		$attrs = $blok['attrs'];
		unset( $attrs['metadata'] );
		$basis = substr( md5( $slug . '|' . $blok['blockName'] . '|' . $tekst . '|' . ( '' === $tekst ? wp_json_encode( $attrs ) : '' ) ), 0, 10 );

		$tellers[ $basis ] = isset( $tellers[ $basis ] ) ? $tellers[ $basis ] + 1 : 1;
		$sleutel           = $basis . '-' . $tellers[ $basis ];

		if ( ! isset( $blokken[ $i ]['attrs']['metadata'] ) || ! is_array( $blokken[ $i ]['attrs']['metadata'] ) ) {
			$blokken[ $i ]['attrs']['metadata'] = array();
		}

		$blokken[ $i ]['attrs']['metadata']['kbj'] = $sleutel;
		$kaart[ $sleutel ]                         = kbj_blok_vingerafdruk( $blokken[ $i ] );
	}

	return $blokken;
}

/**
 * Zet nieuwe inhoud in een pagina en onthoudt de kenmerken.
 *
 * @param int    $id     Pagina.
 * @param string $inhoud Blokopmaak met kenmerken.
 * @param array  $kaart  Kenmerk => vingerafdruk.
 */
function kbj_pagina_vastleggen( $id, $inhoud, $kaart ) {
	wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => wp_slash( $inhoud ),
		)
	);
	update_post_meta( $id, KBJ_BLOKKEN_META, $kaart );
	kbj_afdruk_zetten( $id );
}

/**
 * Alle losse blokken met kenmerk, in volgorde, plus de eigen blokken met het
 * kenmerk van het blok waar ze achter staan.
 *
 * @param array  $blokken Blokken.
 * @param array  $kaart   Kenmerk => vingerafdruk van toen.
 * @param array  $uit     Wat er gevonden is.
 * @param string $vorige  Het laatste kenmerk tot nu toe.
 */
function kbj_oud_lezen( $blokken, $kaart, &$uit, &$vorige ) {
	foreach ( $blokken as $blok ) {
		if ( empty( $blok['blockName'] ) ) {
			continue;
		}

		$sleutel = kbj_blok_sleutel( $blok );
		$bekend  = '' !== $sleutel && isset( $kaart[ $sleutel ] ) && ! isset( $uit['gezien'][ $sleutel ] );

		if ( $bekend && empty( $blok['innerBlocks'] ) ) {
			$uit['gezien'][ $sleutel ] = true;

			if ( kbj_blok_vingerafdruk( $blok ) !== $kaart[ $sleutel ] ) {
				$uit['anders'][ $sleutel ] = $blok;
			}

			$vorige = $sleutel;
			continue;
		}

		// Een groep van het thema: kijk erin.
		if ( ! empty( $blok['attrs']['metadata']['kbjgroep'] ) || ( ! empty( $blok['innerBlocks'] ) && kbj_heeft_sleutel( $blok['innerBlocks'], $kaart ) ) ) {
			kbj_oud_lezen( $blok['innerBlocks'], $kaart, $uit, $vorige );
			continue;
		}

		// Zelf toegevoegd: onthoud waar het achter stond.
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
 * @param array $kaart   Kenmerken van het thema.
 * @return bool
 */
function kbj_heeft_sleutel( $blokken, $kaart ) {
	foreach ( $blokken as $blok ) {
		$sleutel = kbj_blok_sleutel( $blok );

		if ( '' !== $sleutel && isset( $kaart[ $sleutel ] ) ) {
			return true;
		}

		if ( ! empty( $blok['innerBlocks'] ) && kbj_heeft_sleutel( $blok['innerBlocks'], $kaart ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Zet de aanpassingen in de nieuwe blokken.
 *
 * @param array $blokken Nieuwe blokken.
 * @param array $oud     Wat kbj_oud_lezen() vond.
 * @param array $weg     Kenmerken die zijn weggehaald.
 * @param array $gedaan  Wat er gelukt is.
 * @return array
 */
function kbj_nieuw_invullen( $blokken, $oud, $weg, &$gedaan ) {
	$uit = array();

	foreach ( $blokken as $blok ) {
		$sleutel = kbj_blok_sleutel( $blok );

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
					$uit[]                     = $eigen['blok'];
					$gedaan['eigen_geplaatst'][] = $n;
				}
			}

			continue;
		}

		if ( ! empty( $blok['innerBlocks'] ) ) {
			$blok = kbj_binnen_vervangen( $blok, kbj_nieuw_invullen( $blok['innerBlocks'], $oud, $weg, $gedaan ) );
		}

		$uit[] = $blok;
	}

	return $uit;
}

/**
 * Vervangt de binnenblokken van een groep en houdt de opmaak eromheen heel.
 *
 * In innerContent staat elk binnenblok als null tussen de HTML van de groep.
 * Komen er blokken bij of gaan er af, dan moet dat lijstje meeveranderen.
 *
 * @param array $blok    De groep.
 * @param array $binnen  De nieuwe binnenblokken.
 * @return array
 */
function kbj_binnen_vervangen( $blok, $binnen ) {
	$oud_aantal = count( $blok['innerBlocks'] );
	$verschil   = count( $binnen ) - $oud_aantal;
	$inhoud     = $blok['innerContent'];

	if ( $verschil > 0 ) {
		// Extra plekken achter de laatste plek.
		$laatste = null;

		foreach ( $inhoud as $n => $stuk ) {
			if ( null === $stuk ) {
				$laatste = $n;
			}
		}

		$extra = array_fill( 0, $verschil, null );

		if ( null === $laatste ) {
			array_splice( $inhoud, 1, 0, $extra );
		} else {
			array_splice( $inhoud, $laatste + 1, 0, $extra );
		}
	} elseif ( $verschil < 0 ) {
		// Plekken weghalen, van achteren af.
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
 * Bouwt een pagina opnieuw op en neemt de eigen aanpassingen mee.
 *
 * @param int    $id     Pagina.
 * @param string $nieuw  De nieuwe blokopmaak uit het thema, zonder kenmerken.
 * @param string $slug   De pagina, voor de kenmerken.
 * @return array Wat er gebeurde: overgenomen, weg, eigen, niet_geplaatst, bekend.
 */
function kbj_opnieuw_met_aanpassingen( $id, $nieuw, $slug ) {
	$vers  = kbj_sleutels_toevoegen( $nieuw, $slug );
	$kaart = get_post_meta( $id, KBJ_BLOKKEN_META, true );
	$staat = array(
		'overgenomen'    => 0,
		'weg'            => 0,
		'eigen'          => 0,
		'niet_geplaatst' => 0,
		'bekend'         => is_array( $kaart ) && $kaart,
	);

	// Een pagina van voor deze versie: er is niets om mee te vergelijken.
	if ( ! $staat['bekend'] ) {
		kbj_pagina_vastleggen( $id, $vers['inhoud'], $vers['kaart'] );

		return $staat;
	}

	$oud    = array(
		'gezien' => array(),
		'anders' => array(),
		'eigen'  => array(),
	);
	$vorige = '';
	kbj_oud_lezen( parse_blocks( (string) get_post_field( 'post_content', $id, 'raw' ) ), $kaart, $oud, $vorige );

	// Wat toen bestond en nu niet meer in de pagina staat, is weggehaald.
	$weg    = array_diff_key( $kaart, $oud['gezien'] );
	$gedaan = array(
		'overgenomen'     => array(),
		'weg'             => array(),
		'eigen_geplaatst' => array(),
	);
	$blokken = parse_blocks( $vers['inhoud'] );

	// Eigen blokken helemaal bovenaan de pagina.
	$begin = array();

	foreach ( $oud['eigen'] as $n => $eigen ) {
		if ( '' === $eigen['na'] ) {
			$begin[]                     = $eigen['blok'];
			$gedaan['eigen_geplaatst'][] = $n;
		}
	}

	$blokken = array_merge( $begin, kbj_nieuw_invullen( $blokken, $oud, $weg, $gedaan ) );

	// De vingerafdrukken blijven die van het thema: zo telt een aanpassing de
	// volgende keer weer als aanpassing en gaat hij opnieuw mee.
	kbj_pagina_vastleggen( $id, serialize_blocks( $blokken ), $vers['kaart'] );

	$staat['overgenomen']    = count( array_unique( $gedaan['overgenomen'] ) );
	$staat['weg']            = count( array_unique( $gedaan['weg'] ) );
	$staat['eigen']          = count( $oud['eigen'] );
	$staat['niet_geplaatst'] = count( $oud['anders'] ) - $staat['overgenomen'] + count( $oud['eigen'] ) - count( array_unique( $gedaan['eigen_geplaatst'] ) );

	return $staat;
}
