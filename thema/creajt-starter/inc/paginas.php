<?php
/**
 * De pagina's van de site, en het menu.
 *
 * De startpagina is het verhaal; de losse pagina's zijn de hoofdstukken waar je
 * verder kunt lezen. Elke pagina moet op zichzelf werken: iemand die via Google
 * binnenkomt op "Diensten" moet daar alles vinden wat hij nodig heeft, zonder
 * eerst naar de home te hoeven.
 *
 * Dit draait bij het activeren van het thema. Bestaat een pagina al, dan
 * blijven we eraf.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De pagina's die de site nodig heeft.
 *
 * De volgorde is de volgorde in het menu. Een lege `menu` betekent: wel een
 * pagina, niet in de balk bovenaan.
 *
 * Pas deze lijst aan voor de klant. Elk patroon dat je hier noemt moet ook in
 * /patterns bestaan, anders blijft de verwijzing gewoon staan en komt er een
 * lege plek. Dat is met opzet: beter een zichtbare lege plek dan een pagina die
 * stilletjes halve inhoud heeft.
 *
 * @return array[]
 */
function cjt_paginas() {
	return array(
		array(
			'slug'     => 'diensten',
			'titel'    => __( 'Diensten', 'creajt-starter' ),
			'menu'     => __( 'Diensten', 'creajt-starter' ),
			'kop'      => __( 'Wat we doen', 'creajt-starter' ),
			'intro'    => __( 'Kort en concreet: dit is waar je ons voor kunt vragen.', 'creajt-starter' ),
			'zoek'     => __( 'Een overzicht van wat we doen, voor wie, en wat je van ons mag verwachten.', 'creajt-starter' ),
			'patronen' => array( 'diensten', 'beeldbreuk', 'vragen', 'slot' ),
		),
		array(
			'slug'     => 'werkwijze',
			'titel'    => __( 'Werkwijze', 'creajt-starter' ),
			'menu'     => __( 'Werkwijze', 'creajt-starter' ),
			'kop'      => __( 'Zo gaat het', 'creajt-starter' ),
			'intro'    => __( 'Van het eerste bericht tot de oplevering, stap voor stap.', 'creajt-starter' ),
			'zoek'     => __( 'Van het eerste bericht tot de oplevering: elke stap, wat we doen en wanneer je wat hoort.', 'creajt-starter' ),
			'patronen' => array( 'werkwijze', 'slot' ),
		),
		array(
			'slug'     => 'over',
			'titel'    => __( 'Over ons', 'creajt-starter' ),
			'menu'     => __( 'Over', 'creajt-starter' ),
			'kop'      => __( 'Over ons', 'creajt-starter' ),
			'intro'    => __( 'Het gezicht achter het werk.', 'creajt-starter' ),
			'zoek'     => __( 'Wie we zijn, hoe we werken en waarom we dit doen.', 'creajt-starter' ),
			'patronen' => array( 'over', 'beeldbreuk', 'slot' ),
		),
		array(
			'slug'     => 'contact',
			'titel'    => __( 'Contact', 'creajt-starter' ),
			'menu'     => __( 'Contact', 'creajt-starter' ),
			'kop'      => __( 'Stuur een bericht', 'creajt-starter' ),
			'intro'    => __( 'Laat weten waar je aan denkt, dan schrijven we terug wat we voor je kunnen doen.', 'creajt-starter' ),
			'zoek'     => __( 'Laat weten waar je aan denkt, dan schrijven we terug wat we voor je kunnen doen.', 'creajt-starter' ),
			'patronen' => array( 'contact' ),
		),
		array(
			'slug'     => 'bedankt',
			'titel'    => __( 'Bedankt', 'creajt-starter' ),
			'menu'     => '',
			'kop'      => '',
			'intro'    => '',
			'patronen' => array( 'bedankt' ),
		),
		array(
			'slug'     => 'algemene-voorwaarden',
			'titel'    => __( 'Algemene voorwaarden', 'creajt-starter' ),
			'menu'     => '',
			'kop'      => __( 'Algemene voorwaarden', 'creajt-starter' ),
			'intro'    => __( 'De afspraken die bij elke offerte en elke opdracht horen.', 'creajt-starter' ),
			'patronen' => array(),
		),
	);
}

/**
 * De inhoud van de startpagina: één verwijzing naar het patroon "homepage".
 *
 * Niet de secties zelf, maar de verwijzing naar de compositie. Daarmee bepaalt
 * het thema welke secties er staan en in welke volgorde.
 *
 * @return string
 */
function cjt_startpagina_inhoud() {
	return cjt_patroon_naar_blokken( '<!-- wp:pattern {"slug":"creajt-starter/homepage"} /-->' );
}

/**
 * Vervangt verwijzingen naar patronen door de blokken zelf.
 *
 * Waarom dat nodig is: een pagina die alleen "<!-- wp:pattern -->" bevat, is in
 * de editor één blok. WordPress zet dat blok op de gewone tekstbreedte, en alles
 * wat erin zit blijft daarbinnen. Secties die op de site van rand tot rand lopen
 * staan dan in de editor in een kolom van 780 pixels, met kolommen die niet
 * naast elkaar passen en koppen die over vier regels breken.
 *
 * Staan de blokken er los in, dan is elke sectie een eigen blok in de editor,
 * loopt hij daar net zo breed als op de site, en kun je erop klikken.
 *
 * @param string $blokken De blokopmaak.
 * @param int    $diepte  Beveiliging tegen een patroon dat zichzelf aanroept.
 * @return string
 */
function cjt_patroon_naar_blokken( $blokken, $diepte = 0 ) {
	if ( $diepte > 5 || false === strpos( $blokken, 'wp:pattern' ) ) {
		return $blokken;
	}

	return preg_replace_callback(
		'#<!--\s*wp:pattern\s*(\{.*?\})\s*/-->#',
		function ( $treffer ) use ( $diepte ) {
			$attrs = json_decode( $treffer[1], true );
			$slug  = is_array( $attrs ) && isset( $attrs['slug'] ) ? (string) $attrs['slug'] : '';

			if ( '' === $slug ) {
				return $treffer[0];
			}

			$register = WP_Block_Patterns_Registry::get_instance();

			// Niet geregistreerd: dan laten staan. Beter een verwijzing die het
			// nog doet dan een gat in de pagina.
			if ( ! $register->is_registered( $slug ) ) {
				return $treffer[0];
			}

			$patroon = $register->get_registered( $slug );

			if ( empty( $patroon['content'] ) ) {
				return $treffer[0];
			}

			return cjt_patroon_naar_blokken( $patroon['content'], $diepte + 1 );
		},
		$blokken
	);
}

/**
 * De startinhoud van een pagina.
 *
 * @param array $pagina Gegevens van de pagina.
 * @return string Blokmarkering.
 */
function cjt_pagina_inhoud( $pagina ) {
	$blokken = '';

	// Een pagina zonder kop begint meteen met zijn eigen sectie.
	if ( '' !== $pagina['kop'] ) {
		$blokken .= sprintf(
			'<!-- wp:cjt/paginakop {"titel":"%s","intro":"%s"} /-->',
			esc_attr( $pagina['kop'] ),
			esc_attr( $pagina['intro'] )
		);
	}

	if ( ! empty( $pagina['patronen'] ) ) {
		foreach ( $pagina['patronen'] as $slug ) {
			$blokken .= sprintf( '<!-- wp:pattern {"slug":"creajt-starter/%s"} /-->', $slug );
		}

		return cjt_patroon_naar_blokken( $blokken );
	}

	// Geen vaste secties: een leeg tekstblok, klaar om in te typen.
	$blokken .= '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->'
		. '<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">'
		. '<!-- wp:paragraph --><p></p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->';

	return $blokken;
}

/**
 * Maakt de pagina's aan die er nog niet zijn.
 */
function cjt_installatie_paginas() {
	foreach ( cjt_paginas() as $pagina ) {
		if ( get_page_by_path( $pagina['slug'] ) ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_title'   => $pagina['titel'],
				'post_name'    => $pagina['slug'],
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => cjt_pagina_inhoud( $pagina ),
			)
		);
	}
}

/** Onder welke naam we onthouden hoe het menu er bij het schrijven uitzag. */
const CJT_MENU_AFDRUK = 'cjt_menu_afdruk';

/**
 * De items van het hoofdmenu, in de volgorde van het thema.
 *
 * @return string Blokmarkering, leeg als de pagina's er nog niet zijn.
 */
function cjt_menu_markering() {
	$items  = '';
	$aantal = 0;

	foreach ( cjt_paginas() as $pagina ) {
		if ( '' === $pagina['menu'] ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object ) {
			continue;
		}

		++$aantal;

		$items .= sprintf(
			'<!-- wp:navigation-link {"label":"%s","type":"page","id":%d,"url":"%s","kind":"post-type"} /-->',
			esc_attr( $pagina['menu'] ),
			(int) $object->ID,
			esc_url( get_permalink( $object ) )
		);
	}

	return $aantal > 0 ? $items : '';
}

/**
 * Maakt het hoofdmenu.
 *
 * In een blokthema is een menu een eigen inhoudstype (wp_navigation). Staat er
 * één, dan pakt het navigatieblok hem vanzelf op. Zonder menu toont dat blok een
 * lijst van alle pagina's, inclusief de privacyverklaring, en dat wil je niet in
 * je hoofdmenu.
 */
function cjt_installatie_menu() {
	$bestaand = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish', 'draft' ),
		)
	);

	// Een menu dat WordPress zelf aanmaakte bevat alleen een paginalijst. Dat is
	// geen menu dat iemand heeft ingericht, dus dat vullen we alsnog.
	if ( $bestaand && false === strpos( $bestaand[0]->post_content, 'wp:page-list' ) ) {
		return;
	}

	$items = cjt_menu_markering();

	if ( '' === $items ) {
		return;
	}

	$gegevens = array(
		'post_title'   => __( 'Hoofdmenu', 'creajt-starter' ),
		'post_name'    => 'hoofdmenu',
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_content' => $items,
	);

	if ( $bestaand ) {
		$gegevens['ID'] = $bestaand[0]->ID;
		wp_update_post( $gegevens );
	} else {
		wp_insert_post( $gegevens );
	}

	update_option( CJT_MENU_AFDRUK, md5( $items ) );
}

/**
 * Houdt de volgorde van het menu gelijk aan die van het thema.
 *
 * Verandert de volgorde in cjt_paginas(), dan hoort het menu mee te veranderen.
 * Maar niet als de klant het menu zelf heeft aangepast: dan is zijn versie de
 * juiste. We onthouden daarom bij het schrijven hoe het menu eruitzag. Wijkt de
 * inhoud daar nu van af, dan heeft iemand hem met de hand veranderd en blijven
 * we eraf.
 */
function cjt_menu_gelijkhouden() {
	$bestaand = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish', 'draft' ),
		)
	);

	if ( ! $bestaand ) {
		return;
	}

	$afdruk = (string) get_option( CJT_MENU_AFDRUK, '' );
	$nu     = md5( $bestaand[0]->post_content );

	// Zelf aangepast: afblijven.
	if ( '' !== $afdruk && $afdruk !== $nu ) {
		return;
	}

	$items = cjt_menu_markering();

	if ( '' === $items || md5( $items ) === $nu ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $bestaand[0]->ID,
			'post_content' => $items,
		)
	);

	update_option( CJT_MENU_AFDRUK, md5( $items ) );
}
add_action( 'admin_init', 'cjt_menu_gelijkhouden' );
