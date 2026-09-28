<?php
/**
 * Vindbaarheid, zonder plugin.
 *
 * Een SEO-plugin doet drie dingen: een titel en een omschrijving meegeven, de
 * gegevens voor het deelkaartje zetten, en de informatie over het bedrijf in
 * schema.org-vorm neerzetten. Dat is hier allemaal met de hand gedaan, in een
 * paar honderd regels in plaats van een paar megabyte.
 *
 * GEO (vindbaar voor taalmodellen) zit erbij: schone HTML, echte koppen, een
 * llms.txt en antwoorden die op de pagina zelf staan in plaats van achter een
 * klik. Wat een taalmodel prettig leest, leest Google ook prettig.
 *
 * Draait er wél een SEO-plugin, dan stapt dit bestand opzij voor de koppen en
 * de omschrijving: twee titels in één pagina is erger dan geen.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Of er een SEO-plugin actief is die dit werk al doet.
 *
 * @return bool
 */
function kbj_seo_plugin_actief() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' );
}

/**
 * De naam van het bedrijf.
 *
 * @return string
 */
function kbj_bedrijfsnaam() {
	return kbj_optie( 'bedrijf', (string) get_bloginfo( 'name' ) );
}

/**
 * De zin die de site samenvat.
 *
 * @return string
 */
function kbj_standaardzin() {
	$zin = kbj_optie( 'zin' );

	if ( '' !== $zin ) {
		return $zin;
	}

	$tagline = (string) get_bloginfo( 'description' );

	return '' !== $tagline ? $tagline : kbj_bedrijfsnaam();
}

/**
 * De omschrijving van de pagina waar de bezoeker nu staat.
 *
 * Volgorde: de handmatige samenvatting, dan de eerste alinea van de pagina, dan
 * de standaardzin. Nooit een afgekapte zin midden in een woord.
 *
 * @return string
 */
function kbj_omschrijving() {
	return (string) apply_filters( 'kbj_omschrijving', kbj_omschrijving_basis() );
}

/**
 * De omschrijving zonder filter.
 *
 * @return string
 */
function kbj_omschrijving_basis() {
	if ( is_front_page() ) {
		return kbj_inkorten( kbj_standaardzin(), 160 );
	}

	$pagina = kbj_huidige_pagina();

	if ( $pagina && ! empty( $pagina['zoek'] ) && '' === trim( (string) get_post_field( 'post_excerpt', get_queried_object_id() ) ) ) {
		return kbj_inkorten( $pagina['zoek'], 160 );
	}

	if ( is_singular() ) {
		$id = get_queried_object_id();

		$kort = get_post_field( 'post_excerpt', $id );

		if ( '' !== trim( (string) $kort ) ) {
			return kbj_inkorten( $kort, 160 );
		}

		/*
		 * De eerste alinea van de pagina. Blokken die alleen opmaak zijn (een
		 * groep, een afstand) hebben geen tekst, dus die slaan we over. Staat de
		 * tekst in een patroon, dan komt hij hier ook langs, want de pagina
		 * bevat de blokken zelf.
		 */
		$inhoud = (string) get_post_field( 'post_content', $id );
		$html   = do_blocks( $inhoud );

		/*
		 * Een spatie voor elke afsluitende blokrand. Zonder dit plakt een kop
		 * aan de alinea eronder vast en staat er in Google "Wat we doenKort en
		 * concreet".
		 */
		$html = preg_replace( '#</(p|h[1-6]|li|div|figcaption|summary|td|th|blockquote)>#i', ' $0', $html );

		$tekst = trim( wp_strip_all_tags( $html ) );
		$tekst = preg_replace( '/\s+/u', ' ', $tekst );

		if ( '' !== $tekst && mb_strlen( $tekst ) > 60 ) {
			return kbj_inkorten( $tekst, 160 );
		}
	}

	if ( is_search() ) {
		/* translators: %s: de zoekterm. */
		return sprintf( __( 'Zoekresultaten voor %s.', 'kombij' ), get_search_query() );
	}

	return kbj_inkorten( kbj_standaardzin(), 160 );
}

/**
 * Het beeld dat meegaat als iemand een pagina deelt.
 *
 * @return string
 */
function kbj_deelbeeld() {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );

		if ( $url ) {
			return (string) $url;
		}
	}

	$eigen = KBJ_DIR . '/assets/beeld/delen.jpg';

	if ( file_exists( $eigen ) ) {
		return KBJ_URI . '/assets/beeld/delen.jpg';
	}

	return '';
}

/**
 * De titel van de voorpagina.
 *
 * WordPress zet daar standaard "Sitenaam - Tagline" neer, en dat leest als een
 * slogan in plaats van als een antwoord op waar iemand naar zocht.
 *
 * @param string $titel De titel die WordPress maakte.
 * @return string
 */
function kbj_starttitel( $titel ) {
	if ( kbj_seo_plugin_actief() || ! is_front_page() ) {
		return $titel;
	}

	$naam = kbj_bedrijfsnaam();
	$zin  = (string) get_bloginfo( 'description' );

	if ( '' === $zin ) {
		$zin = kbj_optie( 'zin' );
	}

	// Waar iemand naar zoekt eerst, de naam erachter: dat leest in Google als antwoord.
	if ( '' !== $zin && mb_strlen( $zin . ' | ' . $naam ) <= 70 ) {
		return $zin . ' | ' . $naam;
	}

	// Te lang voor Google: dan de korte naam, zonder de plaats die al in de zin staat.
	$kort = trim( preg_replace( '/\s+' . preg_quote( kbj_optie( 'plaats' ), '/' ) . '$/u', '', $naam ) );

	if ( '' !== $zin && mb_strlen( $zin . ' | ' . $kort ) <= 70 ) {
		return $zin . ' | ' . $kort;
	}

	$plaats = kbj_optie( 'plaats' );

	if ( '' !== $zin ) {
		return kbj_inkorten( $naam . ' | ' . $zin, 65 );
	}

	if ( '' !== $plaats ) {
		return $naam . ' | ' . $plaats;
	}

	return $naam;
}
add_filter( 'pre_get_document_title', 'kbj_starttitel', 20 );

/**
 * De pagina uit kbj_paginas() waar de bezoeker nu op staat.
 *
 * @return array|null
 */
function kbj_huidige_pagina() {
	if ( ! is_page() ) {
		return null;
	}

	$slug = (string) get_post_field( 'post_name', get_queried_object_id() );

	foreach ( kbj_paginas() as $pagina ) {
		if ( $pagina['slug'] === $slug ) {
			return $pagina;
		}
	}

	return null;
}

/**
 * De titel van een binnenpagina: waar iemand naar zoekt, met de naam erachter.
 *
 * "Wonen met zorg" zegt in Google weinig. "Wonen met zorg in Maasbommel,
 * kleinschalig en huiselijk | KomBij Maasbommel" zegt waar het is en wat het is.
 *
 * @param array $delen De delen van de titel.
 * @return array
 */
function kbj_paginatitel( $delen ) {
	if ( kbj_seo_plugin_actief() ) {
		return $delen;
	}

	$pagina = kbj_huidige_pagina();

	if ( $pagina && ! empty( $pagina['seo'] ) ) {
		$delen['title'] = $pagina['seo'];
	}

	unset( $delen['tagline'] );

	return $delen;
}
add_filter( 'document_title_parts', 'kbj_paginatitel' );

/**
 * Een verticale streep tussen de delen van de titel, geen gedachtestreepje.
 *
 * @return string
 */
function kbj_titelscheiding() {
	return '|';
}
add_filter( 'document_title_separator', 'kbj_titelscheiding' );

/**
 * De metaregels in de kop: omschrijving, en de gegevens voor het deelkaartje.
 */
function kbj_meta_tags() {
	if ( kbj_seo_plugin_actief() ) {
		return;
	}

	$omschrijving = kbj_omschrijving();
	$titel        = wp_get_document_title();
	$beeld        = kbj_deelbeeld();

	printf( "\n<meta name=\"description\" content=\"%s\">\n", esc_attr( $omschrijving ) );

	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $titel ) );
	printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $omschrijving ) );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( kbj_huidige_url() ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( kbj_bedrijfsnaam() ) );
	printf( "<meta property=\"og:locale\" content=\"%s\">\n", esc_attr( kbj_taal( '_' ) ) );

	if ( '' !== $beeld ) {
		printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $beeld ) );
		printf( "<meta name=\"twitter:card\" content=\"%s\">\n", 'summary_large_image' );
	} else {
		printf( "<meta name=\"twitter:card\" content=\"%s\">\n", 'summary' );
	}
}
add_action( 'wp_head', 'kbj_meta_tags', 5 );

/**
 * De canonieke url: welke versie van deze pagina de echte is.
 *
 * Zonder dit kan dezelfde pagina onder meerdere adressen in Google komen
 * (met en zonder schuine streep, met een volgparameter erachter) en verdeelt
 * hij de waarde over allebei.
 */
function kbj_canoniek() {
	if ( kbj_seo_plugin_actief() || is_404() || is_search() ) {
		return;
	}

	printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( kbj_huidige_url() ) );
}
add_action( 'wp_head', 'kbj_canoniek', 6 );

/**
 * De taal van de site, in de vorm die het gevraagde formaat wil.
 *
 * @param string $scheiding '-' voor html, '_' voor Open Graph.
 * @return string
 */
function kbj_taal( $scheiding = '-' ) {
	$taal = get_locale();

	if ( '' === $taal ) {
		$taal = 'nl_NL';
	}

	return str_replace( array( '-', '_' ), $scheiding, $taal );
}

/**
 * De site aankondigen als Nederlands, ook als WordPress zelf Engels staat.
 *
 * @param string $attributen Wat WordPress meegaf.
 * @return string
 */
function kbj_taal_attribuut( $attributen ) {
	if ( false !== strpos( $attributen, 'lang=' ) ) {
		return $attributen;
	}

	return trim( $attributen . ' lang="' . esc_attr( kbj_taal() ) . '"' );
}
add_filter( 'language_attributes', 'kbj_taal_attribuut' );

/**
 * De gegevens in schema.org-vorm.
 *
 * Eén blok met alles erin: het bedrijf, de site, het kruimelpad en de
 * veelgestelde vragen als die op deze pagina staan. Google leest liever één
 * samenhangend blok dan vier losse.
 */
function kbj_jsonld() {
	if ( is_404() || is_search() ) {
		return;
	}

	$knopen = array( kbj_jsonld_bedrijf(), kbj_jsonld_website() );

	$kruimels = kbj_jsonld_kruimelpad();

	if ( $kruimels ) {
		$knopen[] = $kruimels;
	}

	$vacature = kbj_jsonld_vacature();

	if ( $vacature ) {
		$knopen[] = $vacature;
	}

	if ( is_singular() ) {
		$vragen = kbj_jsonld_vragen( get_queried_object_id() );

		if ( $vragen ) {
			$knopen[] = $vragen;
		}
	}

	$blok = array(
		'@context' => 'https://schema.org',
		'@graph'   => $knopen,
	);

	printf(
		"\n<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode( $blok, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'kbj_jsonld', 10 );

/**
 * Het bedrijf.
 *
 * LocalBusiness als er een plaats bekend is, anders Organization. Een
 * LocalBusiness zonder adres of plaats is voor Google een lege huls.
 *
 * @return array
 */
function kbj_jsonld_bedrijf() {
	$plaats = kbj_optie( 'plaats' );
	$soort  = '' !== $plaats ? 'LocalBusiness' : 'Organization';

	$knoop = array(
		'@type'       => $soort,
		'@id'         => home_url( '/#bedrijf' ),
		'name'        => kbj_bedrijfsnaam(),
		'url'         => home_url( '/' ),
		'description' => kbj_standaardzin(),
	);

	$logo = kbj_deelbeeld();

	if ( '' !== $logo ) {
		$knoop['image'] = $logo;
	}

	$email = kbj_optie( 'email' );

	if ( '' !== $email ) {
		$knoop['email'] = $email;
	}

	$telefoon = kbj_optie( 'telefoon' );

	if ( '' !== $telefoon ) {
		$knoop['telephone'] = $telefoon;
	}

	if ( '' !== $plaats ) {
		$adres = array(
			'@type'           => 'PostalAddress',
			'addressLocality' => $plaats,
			'addressCountry'  => 'NL',
		);

		$straat = kbj_optie( 'straat' );

		if ( '' !== $straat ) {
			$adres['streetAddress'] = $straat;
		}

		$postcode = kbj_optie( 'postcode' );

		if ( '' !== $postcode ) {
			$adres['postalCode'] = $postcode;
		}

		$knoop['address'] = $adres;
	}

	$werkgebied = kbj_optie( 'werkgebied' );

	if ( '' !== $werkgebied ) {
		$gebieden = array_filter( array_map( 'trim', explode( ',', $werkgebied ) ) );

		if ( $gebieden ) {
			$knoop['areaServed'] = array_map(
				function ( $naam ) {
					return array(
						'@type' => 'AdministrativeArea',
						'name'  => $naam,
					);
				},
				array_values( $gebieden )
			);
		}
	}

	$sociaal = array();

	foreach ( array( 'instagram', 'facebook', 'linkedin', 'youtube' ) as $kanaal ) {
		$link = kbj_optie( $kanaal );

		if ( '' !== $link ) {
			$sociaal[] = $link;
		}
	}

	if ( $sociaal ) {
		$knoop['sameAs'] = $sociaal;
	}

	$knoop['logo']    = KBJ_URI . '/assets/beeld/logo.png';
	$knoop['slogan']  = 'Zorg vanuit ons hart';
	$knoop['hasMap']  = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( trim( kbj_optie( 'straat' ) . ' ' . kbj_optie( 'postcode' ) . ' ' . $plaats ) );
	$knoop['founder'] = array(
		array(
			'@type' => 'Person',
			'name'  => 'Corrie Roelofsen',
		),
		array(
			'@type' => 'Person',
			'name'  => 'Chantal',
		),
		array(
			'@type' => 'Person',
			'name'  => 'Edwin',
		),
	);

	$breedte = kbj_optie( 'breedtegraad' );
	$lengte  = kbj_optie( 'lengtegraad' );

	if ( is_numeric( $breedte ) && is_numeric( $lengte ) ) {
		$knoop['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $breedte,
			'longitude' => (float) $lengte,
		);
	}

	/*
	 * De openingstijden van de dagbesteding. Wonen en logeren is dag en nacht,
	 * maar dat is zorg en geen openingstijd: een bezoek gaat op afspraak.
	 */
	$tijd = kbj_optie( 'dagbesteding_tijd' );

	if ( preg_match( '/^(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', $tijd, $treffer ) ) {
		$knoop['openingHoursSpecification'] = array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => $treffer[1],
				'closes'    => $treffer[2],
			),
		);
	}

	$knoop['knowsAbout'] = array( 'wonen met zorg', 'logeren met zorg', 'respijtzorg', 'dagbesteding voor ouderen', 'dementie', 'mantelzorg', 'kleinschalig wonen', 'WLZ', 'WMO', 'PGB' );

	$diensten = array();

	foreach ( kbj_paginas() as $pagina ) {
		if ( ! in_array( $pagina['slug'], array( 'wonen-met-zorg', 'logeren-met-zorg', 'dagbesteding' ), true ) ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		$diensten[] = array(
			'@type'       => 'Offer',
			'itemOffered' => array(
				'@type'       => 'Service',
				'name'        => $pagina['titel'],
				'description' => $pagina['zoek'],
				'url'         => $object ? (string) get_permalink( $object ) : home_url( '/' . $pagina['slug'] . '/' ),
				'areaServed'  => kbj_optie( 'werkgebied', $plaats ),
				'provider'    => array( '@id' => home_url( '/#bedrijf' ) ),
			),
		);
	}

	if ( $diensten ) {
		$knoop['makesOffer'] = $diensten;
	}

	return $knoop;
}

/**
 * De site zelf, met de zoekfunctie.
 *
 * @return array
 */
function kbj_jsonld_website() {
	return array(
		'@type'       => 'WebSite',
		'@id'         => home_url( '/#website' ),
		'url'         => home_url( '/' ),
		'name'        => kbj_bedrijfsnaam(),
		'description' => kbj_standaardzin(),
		'inLanguage'  => kbj_taal(),
		'publisher'   => array( '@id' => home_url( '/#bedrijf' ) ),
	);
}

/**
 * Het kruimelpad: waar deze pagina in de site hangt.
 *
 * Google zet dit onder de zoekresultaten in plaats van de kale url, en dat
 * scheelt aantoonbaar kliks.
 *
 * @return array|null
 */
function kbj_jsonld_kruimelpad() {
	if ( is_front_page() ) {
		return null;
	}

	$stappen = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => __( 'Home', 'kombij' ),
			'item'     => home_url( '/' ),
		),
	);

	if ( is_singular() ) {
		$id = get_queried_object_id();

		// Een pagina onder een andere pagina: de ouder hoort ertussen.
		foreach ( array_reverse( (array) get_post_ancestors( $id ) ) as $ouder ) {
			$stappen[] = array(
				'@type'    => 'ListItem',
				'position' => count( $stappen ) + 1,
				'name'     => get_the_title( $ouder ),
				'item'     => (string) get_permalink( $ouder ),
			);
		}

		$stappen[] = array(
			'@type'    => 'ListItem',
			'position' => count( $stappen ) + 1,
			'name'     => get_the_title( $id ),
			'item'     => (string) get_permalink( $id ),
		);
	} else {
		$stappen[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => wp_strip_all_tags( (string) get_the_archive_title() ),
			'item'     => kbj_huidige_url(),
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => kbj_huidige_url() . '#kruimels',
		'itemListElement' => $stappen,
	);
}

/**
 * De veelgestelde vragen op deze pagina.
 *
 * De vragen staan als core/details-blokken in de pagina: een vraag met het
 * antwoord eronder. Dat is gewone HTML die iedereen kan lezen, dus staat het
 * antwoord ook echt op de pagina en niet alleen in de opmaak voor Google.
 *
 * @param int $pagina_id De pagina.
 * @return array|null
 */
function kbj_jsonld_vragen( $pagina_id ) {
	$blokken = parse_blocks( (string) get_post_field( 'post_content', $pagina_id ) );
	$vragen  = array();

	kbj_vragen_verzamelen( $blokken, $vragen );

	if ( count( $vragen ) < 2 ) {
		return null;
	}

	return array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink( $pagina_id ) . '#vragen',
		'mainEntity' => $vragen,
	);
}

/**
 * Loopt door de blokken heen en haalt de vraag-en-antwoordparen eruit.
 *
 * @param array $blokken De blokken.
 * @param array $vragen  De lijst die gevuld wordt.
 * @param int   $diepte  Beveiliging tegen te diepe nesting.
 */
function kbj_vragen_verzamelen( $blokken, &$vragen, $diepte = 0 ) {
	if ( $diepte > 8 ) {
		return;
	}

	foreach ( $blokken as $blok ) {
		if ( 'core/details' === $blok['blockName'] ) {
			$html = render_block( $blok );

			if ( preg_match( '#<summary[^>]*>(.*?)</summary>#is', $html, $treffer ) ) {
				$vraag   = trim( wp_strip_all_tags( $treffer[1] ) );
				$antwoord = trim( wp_strip_all_tags( str_replace( $treffer[0], '', $html ) ) );
				$antwoord = preg_replace( '/\s+/u', ' ', $antwoord );

				if ( '' !== $vraag && '' !== $antwoord ) {
					$vragen[] = array(
						'@type'          => 'Question',
						'name'           => $vraag,
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $antwoord,
						),
					);
				}
			}
		}

		if ( ! empty( $blok['innerBlocks'] ) ) {
			kbj_vragen_verzamelen( $blok['innerBlocks'], $vragen, $diepte + 1 );
		}
	}
}

/**
 * De sitemap uitdunnen.
 *
 * WordPress zet er standaard de auteurs en alle taxonomieën in. Op een site van
 * één bedrijf is dat een pagina met één naam erop en een handvol lege
 * categorieën: dunne pagina's die niets toevoegen en wel meetellen.
 *
 * @param WP_Sitemaps_Provider $aanbieder De aanbieder.
 * @param string               $naam      Zijn naam.
 * @return WP_Sitemaps_Provider|false
 */
function kbj_sitemap_uitdunnen( $aanbieder, $naam ) {
	return in_array( $naam, array( 'users', 'taxonomies' ), true ) ? false : $aanbieder;
}
add_filter( 'wp_sitemaps_add_provider', 'kbj_sitemap_uitdunnen', 10, 2 );

/**
 * /llms.txt: een korte gids voor taalmodellen.
 *
 * Steeds meer mensen vragen aan een taalmodel wat ze vroeger googelden. Zo'n
 * model leest liever één overzichtelijk tekstbestand dan een site vol opmaak.
 * Dit zet in een paar regels neer wie het bedrijf is, wat het doet en waar de
 * belangrijkste pagina's staan.
 */
function kbj_llms_txt() {
	if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$pad = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );

	if ( '/llms.txt' !== untrailingslashit( (string) $pad ) ) {
		return;
	}

	$regels = array(
		'# ' . kbj_bedrijfsnaam(),
		'',
		'> ' . kbj_standaardzin(),
		'',
	);

	$plaats     = kbj_optie( 'plaats' );
	$werkgebied = kbj_optie( 'werkgebied' );

	if ( '' !== $plaats ) {
		$regels[] = '- Vestigingsplaats: ' . $plaats;
	}

	if ( '' !== $werkgebied ) {
		$regels[] = '- Werkgebied: ' . $werkgebied;
	}

	$email = kbj_optie( 'email' );

	if ( '' !== $email ) {
		$regels[] = '- E-mail: ' . $email;
	}

	$telefoon = kbj_optie( 'telefoon' );

	if ( '' !== $telefoon ) {
		$regels[] = '- Telefoon: ' . $telefoon;
	}

	$straat = kbj_optie( 'straat' );

	if ( '' !== $straat ) {
		$regels[] = '- Adres: ' . $straat . ', ' . trim( kbj_optie( 'postcode' ) . ' ' . $plaats );
	}

	$feiten = kbj_llms_feiten();

	if ( $feiten ) {
		$regels[] = '';
		$regels[] = '## Belangrijk om te weten';
		$regels[] = '';

		foreach ( $feiten as $feit ) {
			$regels[] = '- ' . $feit;
		}
	}

	$regels[] = '';
	$regels[] = '## Pagina\'s';
	$regels[] = '';
	$regels[] = sprintf( '- [Home](%s): %s', home_url( '/' ), kbj_standaardzin() );

	foreach ( kbj_paginas() as $pagina ) {
		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object || 'bedankt' === $pagina['slug'] ) {
			continue;
		}

		$uitleg = ! empty( $pagina['zoek'] ) ? $pagina['zoek'] : $pagina['intro'];

		$regels[] = sprintf(
			'- [%s](%s)%s',
			$pagina['titel'],
			get_permalink( $object ),
			'' !== $uitleg ? ': ' . $uitleg : ''
		);
	}

	$vacatures = kbj_vacatures_open();

	if ( $vacatures ) {
		$regels[] = '';
		$regels[] = '## Vacatures';
		$regels[] = '';

		foreach ( $vacatures as $vacature ) {
			$kenmerken = array_filter(
				array(
					kbj_vacature_veld( $vacature->ID, 'uren' ),
					kbj_vacature_veld( $vacature->ID, 'opleiding' ),
					kbj_vacature_veld( $vacature->ID, 'salaris' ),
				)
			);

			$regels[] = sprintf( '- [%s](%s): %s', get_the_title( $vacature ), get_permalink( $vacature ), implode( ', ', $kenmerken ) );
		}

		$regels[] = '';
		$regels[] = 'Solliciteren: werkenbij@kombijmaasbommel.nl. Alle vacatures: ' . home_url( '/werken-bij/' );
	}

	$voorpagina = (int) get_option( 'page_on_front' );
	$vragen     = $voorpagina ? kbj_jsonld_vragen( $voorpagina ) : null;

	if ( $vragen ) {
		$regels[] = '';
		$regels[] = '## Veelgestelde vragen';

		foreach ( $vragen['mainEntity'] as $vraag ) {
			$regels[] = '';
			$regels[] = '### ' . $vraag['name'];
			$regels[] = '';
			$regels[] = $vraag['acceptedAnswer']['text'];
		}
	}

	$regels[] = '';
	$regels[] = '## Bronnen';
	$regels[] = '';
	$regels[] = '- [Sitemap](' . home_url( '/wp-sitemap.xml' ) . ')';
	$regels[] = '';

	/*
	 * Zonder dit staat de status op 404: WordPress kent /llms.txt niet als
	 * pagina en heeft dat al besloten voordat wij aan de beurt zijn. Een
	 * taalmodel dat een 404 terugkrijgt leest de inhoud niet.
	 */
	status_header( 200 );
	nocache_headers();

	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: all' );

	/*
	 * Geen esc_html hier: dit is een tekstbestand, geen HTML. Zou je het wel
	 * ontsmetten, dan wordt de > van het citaat een &gt; en staat er onzin.
	 * Alles wat hier in komt is al opgeschoond bij het opslaan van de opties.
	 */
	echo implode( "\n", $regels ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'template_redirect', 'kbj_llms_txt', 1 );

/**
 * De feiten die een taalmodel over KomBij moet kunnen noemen.
 *
 * Kort en controleerbaar. Wie aan een taalmodel vraagt "waar kan mijn moeder
 * logeren in Maas en Waal", krijgt dan een antwoord met de juiste gegevens.
 *
 * @return string[]
 */
function kbj_llms_feiten() {
	return array(
		'KomBij Maasbommel biedt wonen met zorg, logeren met zorg (respijtzorg) en dagbesteding voor ouderen en volwassenen met een zorgvraag, onder meer bij dementie.',
		'Gevestigd in een monumentaal gebouw uit 1869, de voormalige Lambertuskerk (rijksmonument), aan de Raadhuisdijk 44 in Maasbommel, gemeente West Maas en Waal, Gelderland.',
		'Wonen: 6 plekken voor bewoners met een WLZ-indicatie, eigen zit-slaapkamer, 24 uur per dag zorg en toezicht. Woonkosten vanaf € 985 per maand, exclusief servicekosten en zorg.',
		'Logeren: minimaal 2 nachten, vaste blokken (maandag tot woensdag, woensdag tot vrijdag, vrijdag tot maandag). Te betalen via WLZ, WMO, PGB (tot 156 etmalen per jaar) of particulier.',
		'Dagbesteding: maandag tot en met vrijdag van 10:30 tot 16:30, minimaal 2 dagen per week, via WMO, WLZ of particulier.',
		'Opgericht door Corrie Roelofsen, haar dochter Chantal en schoonzoon Edwin. Een familiebedrijf, zonder religieuze grondslag: iedereen is welkom.',
		'Rondleiding of kennismaking op afspraak, telefonisch of per e-mail.',
	);
}
