<?php
/**
 * De header: menu links, logo in het midden, menu rechts.
 *
 * Waarom dit een eigen blok is en geen losse blokken in de header: het
 * navigatieblok van WordPress kan niet om een logo heen gesplitst worden. Door
 * het menu hier zelf te tekenen kunnen we de items in tweeën delen en het logo
 * er precies tussen zetten.
 *
 * De klant beheert het menu gewoon in de site-editor onder "Hoofdmenu"; dit blok
 * leest datzelfde menu uit.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De items uit het hoofdmenu.
 *
 * @return array[] Elk item heeft een label en een url.
 */
function cjt_menu_items() {
	$menus = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	if ( empty( $menus ) ) {
		return cjt_menu_uit_paginas();
	}

	$items = array();

	foreach ( parse_blocks( $menus[0]->post_content ) as $blok ) {
		if ( 'core/navigation-link' !== $blok['blockName'] || empty( $blok['attrs']['label'] ) ) {
			continue;
		}

		$items[] = array(
			'label' => (string) $blok['attrs']['label'],
			'url'   => isset( $blok['attrs']['url'] ) ? (string) $blok['attrs']['url'] : '#',
		);
	}

	/*
	 * WordPress maakt zelf een menu aan met alleen <!-- wp:page-list /--> erin
	 * zodra er ergens een navigatieblok staat en er nog geen menu is. Dan staan
	 * er geen losse links in, en vallen we terug op de pagina's zelf.
	 */
	if ( empty( $items ) ) {
		$items = cjt_menu_uit_paginas();
	}

	return $items;
}

/**
 * Menu-items afgeleid uit de pagina's, voor als er nog geen menu is ingericht.
 *
 * De juridische pagina's blijven eruit: die horen in de footer, niet bovenaan.
 *
 * @return array[]
 */
function cjt_menu_uit_paginas() {
	$items     = array();
	$overslaan = array( 'home', 'privacyverklaring', 'algemene-voorwaarden', 'bedankt', 'sample-page' );

	foreach ( cjt_paginas() as $pagina ) {
		if ( '' === $pagina['menu'] || in_array( $pagina['slug'], $overslaan, true ) ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object ) {
			continue;
		}

		$items[] = array(
			'label' => $pagina['menu'],
			'url'   => (string) get_permalink( $object ),
		);
	}

	return $items;
}

/**
 * Tekent één lijst met menu-items.
 *
 * @param array  $items De items.
 * @param string $kant  'links' of 'rechts', puur voor de klasse.
 * @return string
 */
function cjt_menu_lijst( $items, $kant ) {
	if ( empty( $items ) ) {
		return '';
	}

	$html = '';

	foreach ( $items as $item ) {
		$huidig = untrailingslashit( $item['url'] ) === untrailingslashit( cjt_huidige_url() );

		$html .= sprintf(
			'<li><a href="%1$s"%2$s>%3$s</a></li>',
			esc_url( $item['url'] ),
			$huidig ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}

	return sprintf( '<ul class="cjt-menu__lijst cjt-menu__lijst--%s">%s</ul>', esc_attr( $kant ), $html );
}

/**
 * Het logo in de header: de SVG als die er is, anders het sitelogo, anders de
 * naam van de site.
 *
 * Een SVG neemt zijn kleur over van de tekst eromheen. Daardoor is dezelfde
 * tekening licht over een donkere hero en donker op een lichte balk, en blijft
 * hij op elk scherm scherp.
 *
 * @return string
 */
function cjt_header_logo() {
	$vorm = cjt_logo_svg( 'cjt-menu__logo-vorm' );

	if ( '' === $vorm ) {
		$logo_id  = (int) get_theme_mod( 'custom_logo' );
		$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';

		if ( $logo_url ) {
			$vorm = sprintf(
				'<img src="%s" alt="%s" decoding="async">',
				esc_url( $logo_url ),
				esc_attr( get_bloginfo( 'name' ) )
			);
		}
	}

	if ( '' === $vorm ) {
		return sprintf(
			'<a class="cjt-menu__logo cjt-menu__logo--tekst" href="%s">%s</a>',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
	}

	return sprintf(
		'<a class="cjt-menu__logo" href="%1$s">%2$s</a>',
		esc_url( home_url( '/' ) ),
		$vorm
	);
}

/**
 * De hele header.
 *
 * @return string
 */
function cjt_render_header() {
	$items = cjt_menu_items();

	// Home hoort er altijd bij en staat vooraan.
	array_unshift(
		$items,
		array(
			'label' => __( 'Home', 'creajt-starter' ),
			'url'   => home_url( '/' ),
		)
	);

	// In tweeën delen, met de grootste helft links als het aantal oneven is.
	$helft  = (int) ceil( count( $items ) / 2 );
	$links  = array_slice( $items, 0, $helft );
	$rechts = array_slice( $items, $helft );

	$contact = get_page_by_path( 'contact' );

	/*
	 * De oproep staat rechts, los van de menulijst. Hij hoort niet tussen de
	 * gewone links: dit is geen plek waar je heen kunt, dit is de vraag die de
	 * hele site stelt.
	 */
	$oproep = sprintf(
		'<a class="cjt-menu__cta" href="%s">%s</a>',
		esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		esc_html__( 'Neem contact op', 'creajt-starter' )
	);

	return sprintf(
		'<div %1$s><nav class="cjt-menu" aria-label="%2$s">'
			. '<button class="cjt-menu__knop" type="button" aria-expanded="false" aria-controls="cjt-menu-paneel">'
				. '<span class="cjt-menu__streep" aria-hidden="true"></span>'
				. '<span class="screen-reader-text">%3$s</span>'
			. '</button>'
			. '%4$s'
			. '<div class="cjt-menu__binnen" id="cjt-menu-paneel">'
				. '%5$s'
				. '<span class="cjt-menu__gat" aria-hidden="true"></span>'
				. '<div class="cjt-menu__rechts">%6$s%7$s</div>'
			. '</div>'
		. '</nav></div>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-header' ) ),
		esc_attr__( 'Hoofdmenu', 'creajt-starter' ),
		esc_html__( 'Menu openen', 'creajt-starter' ),
		cjt_header_logo(),
		cjt_menu_lijst( $links, 'links' ),
		cjt_menu_lijst( $rechts, 'rechts' ),
		$oproep
	);
}
