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
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De items uit het hoofdmenu.
 *
 * @return array[] Elk item heeft een label en een url.
 */
function kbj_menu_items() {
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
		return kbj_menu_uit_paginas();
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
		$items = kbj_menu_uit_paginas();
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
function kbj_menu_uit_paginas() {
	$items     = array(
		array(
			'label' => __( 'Home', 'kombij' ),
			'url'   => home_url( '/' ),
		),
	);
	$overslaan = array( 'home', 'privacyverklaring', 'algemene-voorwaarden', 'bedankt', 'sample-page' );

	foreach ( kbj_paginas() as $pagina ) {
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
function kbj_menu_lijst( $items, $kant ) {
	if ( empty( $items ) ) {
		return '';
	}

	$html = '';

	foreach ( $items as $item ) {
		$huidig = untrailingslashit( $item['url'] ) === untrailingslashit( kbj_huidige_url() );

		$html .= sprintf(
			'<li><a href="%1$s"%2$s>%3$s</a></li>',
			esc_url( $item['url'] ),
			$huidig ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}

	return sprintf( '<ul class="kbj-menu__lijst kbj-menu__lijst--%s">%s</ul>', esc_attr( $kant ), $html );
}

/**
 * Het logo in de header: het icoon met het woordmerk ernaast.
 *
 * Het staande logo uit het merkboek is te hoog voor een balk van 76 pixels.
 * Daarom staat hier een liggende versie, samengesteld uit dezelfde vormen: het
 * glas-in-loodicoon en het woordmerk KomBij, in de originele merkkleuren. Het is
 * een afbeelding en geen ingevoegde SVG, want het logo mag niet meekleuren.
 *
 * @return string
 */
function kbj_header_logo() {
	return sprintf(
		'<a class="kbj-menu__logo" href="%1$s" rel="home"><img src="%2$s" alt="%3$s" width="618" height="232" decoding="async" fetchpriority="high"></a>',
		esc_url( home_url( '/' ) ),
		esc_url( KBJ_URI . '/assets/beeld/logo-balk.svg' ),
		esc_attr( kbj_optie( 'bedrijf', (string) get_bloginfo( 'name' ) ) )
	);
}

/**
 * Het telefoonnummer als link, voor in de balk en onderaan elke pagina.
 *
 * Voor de doelgroep van KomBij is bellen de gewoonste volgende stap. Daarom
 * staat het nummer altijd in beeld, ook op de telefoon waar het menu dicht is.
 *
 * @param string $klasse Klasse op de link.
 * @param bool   $tekst  Het nummer tonen, of alleen het icoon.
 * @return string
 */
function kbj_bel_link( $klasse, $tekst = true ) {
	$nummer = kbj_optie( 'telefoon' );

	if ( '' === $nummer ) {
		return '';
	}

	return sprintf(
		'<a class="%1$s" href="tel:%2$s">%3$s<span%4$s>%5$s</span></a>',
		esc_attr( $klasse ),
		esc_attr( preg_replace( '/[^\d+]/', '', $nummer ) ),
		kbj_icoon_telefoon(),
		$tekst ? '' : ' class="screen-reader-text"',
		esc_html( $tekst ? $nummer : sprintf( /* translators: %s: telefoonnummer. */ __( 'Bel %s', 'kombij' ), $nummer ) )
	);
}

/**
 * Een klein telefoonicoon, in de kleur van de tekst.
 *
 * @return string
 */
function kbj_icoon_telefoon() {
	return '<svg class="kbj-icoon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.6a1 1 0 0 1-.25 1z"/></svg>';
}

/**
 * De hele header: logo links, menu en de twee oproepen rechts.
 *
 * @return string
 */
function kbj_render_header() {
	$items   = kbj_menu_items();
	$contact = get_page_by_path( 'contact' );

	$oproep = sprintf(
		'<a class="kbj-menu__cta" href="%s">%s</a>',
		esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		esc_html__( 'Kom kennismaken', 'kombij' )
	);

	return sprintf(
		'<div %1$s><nav class="kbj-menu" aria-label="%2$s">'
			. '%3$s'
			. '<div class="kbj-menu__snel">%4$s'
			. '<button class="kbj-menu__knop" type="button" aria-expanded="false" aria-controls="kbj-menu-paneel">'
				. '<span class="kbj-menu__streep" aria-hidden="true"></span>'
				. '<span class="kbj-menu__woord">%5$s</span>'
			. '</button></div>'
			. '<div class="kbj-menu__binnen" id="kbj-menu-paneel">'
				. '%6$s'
				. '<div class="kbj-menu__rechts">%7$s%8$s</div>'
			. '</div>'
		. '</nav>%9$s</div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-header' ) ),
		esc_attr__( 'Hoofdmenu', 'kombij' ),
		kbj_header_logo(),
		kbj_bel_link( 'kbj-menu__bel-icoon', false ),
		esc_html__( 'Menu', 'kombij' ),
		kbj_menu_lijst( $items, 'hoofd' ),
		kbj_bel_link( 'kbj-menu__bel' ),
		$oproep,
		kbj_bordje()
	);
}
