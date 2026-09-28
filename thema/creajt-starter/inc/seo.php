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
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Of er een SEO-plugin actief is die dit werk al doet.
 *
 * @return bool
 */
function cjt_seo_plugin_actief() {
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
function cjt_bedrijfsnaam() {
	return cjt_optie( 'bedrijf', (string) get_bloginfo( 'name' ) );
}

/**
 * De zin die de site samenvat.
 *
 * @return string
 */
function cjt_standaardzin() {
	$zin = cjt_optie( 'zin' );

	if ( '' !== $zin ) {
		return $zin;
	}

	$tagline = (string) get_bloginfo( 'description' );

	return '' !== $tagline ? $tagline : cjt_bedrijfsnaam();
}

/**
 * De omschrijving van de pagina waar de bezoeker nu staat.
 *
 * Volgorde: de handmatige samenvatting, dan de eerste alinea van de pagina, dan
 * de standaardzin. Nooit een afgekapte zin midden in een woord.
 *
 * @return string
 */
function cjt_omschrijving() {
	if ( is_singular() ) {
		$id = get_queried_object_id();

		$kort = get_post_field( 'post_excerpt', $id );

		if ( '' !== trim( (string) $kort ) ) {
			return cjt_inkorten( $kort, 160 );
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
			return cjt_inkorten( $tekst, 160 );
		}
	}

	if ( is_search() ) {
		/* translators: %s: de zoekterm. */
		return sprintf( __( 'Zoekresultaten voor %s.', 'creajt-starter' ), get_search_query() );
	}

	return cjt_inkorten( cjt_standaardzin(), 160 );
}

/**
 * Het beeld dat meegaat als iemand een pagina deelt.
 *
 * @return string
 */
function cjt_deelbeeld() {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );

		if ( $url ) {
			return (string) $url;
		}
	}

	$eigen = CJT_DIR . '/assets/beeld/delen.jpg';

	if ( file_exists( $eigen ) ) {
		return CJT_URI . '/assets/beeld/delen.jpg';
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
function cjt_starttitel( $titel ) {
	if ( cjt_seo_plugin_actief() || ! is_front_page() ) {
		return $titel;
	}

	$naam = cjt_bedrijfsnaam();
	$zin  = cjt_optie( 'zin' );

	if ( '' === $zin ) {
		$zin = (string) get_bloginfo( 'description' );
	}

	$plaats = cjt_optie( 'plaats' );

	if ( '' !== $zin ) {
		return cjt_inkorten( $naam . ' | ' . $zin, 65 );
	}

	if ( '' !== $plaats ) {
		return $naam . ' | ' . $plaats;
	}

	return $naam;
}
add_filter( 'pre_get_document_title', 'cjt_starttitel', 20 );

/**
 * De metaregels in de kop: omschrijving, en de gegevens voor het deelkaartje.
 */
function cjt_meta_tags() {
	if ( cjt_seo_plugin_actief() ) {
		return;
	}

	$omschrijving = cjt_omschrijving();
	$titel        = wp_get_document_title();
	$beeld        = cjt_deelbeeld();

	printf( "\n<meta name=\"description\" content=\"%s\">\n", esc_attr( $omschrijving ) );

	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $titel ) );
	printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $omschrijving ) );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( cjt_huidige_url() ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( cjt_bedrijfsnaam() ) );
	printf( "<meta property=\"og:locale\" content=\"%s\">\n", esc_attr( cjt_taal( '_' ) ) );

	if ( '' !== $beeld ) {
		printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $beeld ) );
		printf( "<meta name=\"twitter:card\" content=\"%s\">\n", 'summary_large_image' );
	} else {
		printf( "<meta name=\"twitter:card\" content=\"%s\">\n", 'summary' );
	}
}
add_action( 'wp_head', 'cjt_meta_tags', 5 );

/**
 * De canonieke url: welke versie van deze pagina de echte is.
 *
 * Zonder dit kan dezelfde pagina onder meerdere adressen in Google komen
 * (met en zonder schuine streep, met een volgparameter erachter) en verdeelt
 * hij de waarde over allebei.
 */
function cjt_canoniek() {
	if ( cjt_seo_plugin_actief() || is_404() || is_search() ) {
		return;
	}

	printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( cjt_huidige_url() ) );
}
add_action( 'wp_head', 'cjt_canoniek', 6 );

/**
 * De taal van de site, in de vorm die het gevraagde formaat wil.
 *
 * @param string $scheiding '-' voor html, '_' voor Open Graph.
 * @return string
 */
function cjt_taal( $scheiding = '-' ) {
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
function cjt_taal_attribuut( $attributen ) {
	if ( false !== strpos( $attributen, 'lang=' ) ) {
		return $attributen;
	}

	return trim( $attributen . ' lang="' . esc_attr( cjt_taal() ) . '"' );
}
add_filter( 'language_attributes', 'cjt_taal_attribuut' );

/**
 * De gegevens in schema.org-vorm.
 *
 * Eén blok met alles erin: het bedrijf, de site, het kruimelpad en de
 * veelgestelde vragen als die op deze pagina staan. Google leest liever één
 * samenhangend blok dan vier losse.
 */
function cjt_jsonld() {
	if ( is_404() || is_search() ) {
		return;
	}

	$knopen = array( cjt_jsonld_bedrijf(), cjt_jsonld_website() );

	$kruimels = cjt_jsonld_kruimelpad();

	if ( $kruimels ) {
		$knopen[] = $kruimels;
	}

	if ( is_singular() ) {
		$vragen = cjt_jsonld_vragen( get_queried_object_id() );

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
add_action( 'wp_head', 'cjt_jsonld', 10 );

/**
 * Het bedrijf.
 *
 * LocalBusiness als er een plaats bekend is, anders Organization. Een
 * LocalBusiness zonder adres of plaats is voor Google een lege huls.
 *
 * @return array
 */
function cjt_jsonld_bedrijf() {
	$plaats = cjt_optie( 'plaats' );
	$soort  = '' !== $plaats ? 'LocalBusiness' : 'Organization';

	$knoop = array(
		'@type'       => $soort,
		'@id'         => home_url( '/#bedrijf' ),
		'name'        => cjt_bedrijfsnaam(),
		'url'         => home_url( '/' ),
		'description' => cjt_standaardzin(),
	);

	$logo = cjt_deelbeeld();

	if ( '' !== $logo ) {
		$knoop['image'] = $logo;
	}

	$email = cjt_optie( 'email' );

	if ( '' !== $email ) {
		$knoop['email'] = $email;
	}

	$telefoon = cjt_optie( 'telefoon' );

	if ( '' !== $telefoon ) {
		$knoop['telephone'] = $telefoon;
	}

	if ( '' !== $plaats ) {
		$adres = array(
			'@type'           => 'PostalAddress',
			'addressLocality' => $plaats,
			'addressCountry'  => 'NL',
		);

		$straat = cjt_optie( 'straat' );

		if ( '' !== $straat ) {
			$adres['streetAddress'] = $straat;
		}

		$postcode = cjt_optie( 'postcode' );

		if ( '' !== $postcode ) {
			$adres['postalCode'] = $postcode;
		}

		$knoop['address'] = $adres;
	}

	$werkgebied = cjt_optie( 'werkgebied' );

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
		$link = cjt_optie( $kanaal );

		if ( '' !== $link ) {
			$sociaal[] = $link;
		}
	}

	if ( $sociaal ) {
		$knoop['sameAs'] = $sociaal;
	}

	return $knoop;
}

/**
 * De site zelf, met de zoekfunctie.
 *
 * @return array
 */
function cjt_jsonld_website() {
	return array(
		'@type'       => 'WebSite',
		'@id'         => home_url( '/#website' ),
		'url'         => home_url( '/' ),
		'name'        => cjt_bedrijfsnaam(),
		'description' => cjt_standaardzin(),
		'inLanguage'  => cjt_taal(),
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
function cjt_jsonld_kruimelpad() {
	if ( is_front_page() ) {
		return null;
	}

	$stappen = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => __( 'Home', 'creajt-starter' ),
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
			'item'     => cjt_huidige_url(),
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => cjt_huidige_url() . '#kruimels',
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
function cjt_jsonld_vragen( $pagina_id ) {
	$blokken = parse_blocks( (string) get_post_field( 'post_content', $pagina_id ) );
	$vragen  = array();

	cjt_vragen_verzamelen( $blokken, $vragen );

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
function cjt_vragen_verzamelen( $blokken, &$vragen, $diepte = 0 ) {
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
			cjt_vragen_verzamelen( $blok['innerBlocks'], $vragen, $diepte + 1 );
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
function cjt_sitemap_uitdunnen( $aanbieder, $naam ) {
	return in_array( $naam, array( 'users', 'taxonomies' ), true ) ? false : $aanbieder;
}
add_filter( 'wp_sitemaps_add_provider', 'cjt_sitemap_uitdunnen', 10, 2 );

/**
 * /llms.txt: een korte gids voor taalmodellen.
 *
 * Steeds meer mensen vragen aan een taalmodel wat ze vroeger googelden. Zo'n
 * model leest liever één overzichtelijk tekstbestand dan een site vol opmaak.
 * Dit zet in een paar regels neer wie het bedrijf is, wat het doet en waar de
 * belangrijkste pagina's staan.
 */
function cjt_llms_txt() {
	if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$pad = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );

	if ( '/llms.txt' !== untrailingslashit( (string) $pad ) ) {
		return;
	}

	$regels = array(
		'# ' . cjt_bedrijfsnaam(),
		'',
		'> ' . cjt_standaardzin(),
		'',
	);

	$plaats     = cjt_optie( 'plaats' );
	$werkgebied = cjt_optie( 'werkgebied' );

	if ( '' !== $plaats ) {
		$regels[] = '- Vestigingsplaats: ' . $plaats;
	}

	if ( '' !== $werkgebied ) {
		$regels[] = '- Werkgebied: ' . $werkgebied;
	}

	$email = cjt_optie( 'email' );

	if ( '' !== $email ) {
		$regels[] = '- Contact: ' . $email;
	}

	$regels[] = '';
	$regels[] = '## Pagina\'s';
	$regels[] = '';
	$regels[] = sprintf( '- [Home](%s): %s', home_url( '/' ), cjt_standaardzin() );

	foreach ( cjt_paginas() as $pagina ) {
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
add_action( 'template_redirect', 'cjt_llms_txt', 1 );
