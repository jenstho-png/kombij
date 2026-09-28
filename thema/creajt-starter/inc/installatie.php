<?php
/**
 * Wat er één keer moet gebeuren bij het activeren van het thema.
 *
 * Zonder dit staat er na het activeren een lege site en moet iemand zelf een
 * startpagina aanmaken, de secties erin zetten en de permalinks goedzetten. Dat
 * is precies het soort stap dat blijft liggen, dus doet het thema het.
 *
 * Alles hieronder is terug te draaien: het maakt pagina's aan en zet twee
 * instellingen. Draait het een tweede keer, dan gebeurt er niets meer.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CJT_INSTALLATIE = 'cjt_installatie_gedaan';

/**
 * Zet de site klaar na het activeren.
 */
function cjt_installatie() {
	if ( get_option( CJT_INSTALLATIE ) ) {
		return;
	}

	cjt_installatie_permalinks();
	cjt_installatie_logo();
	cjt_installatie_startpagina();
	cjt_installatie_paginas();
	cjt_installatie_menu();

	update_option( CJT_INSTALLATIE, CJT_VERSION );
}
add_action( 'after_switch_theme', 'cjt_installatie', 20 );

/**
 * Nieuwe pagina's aanmaken na een update van het thema.
 *
 * Komt er in een nieuwe versie een pagina bij, dan werd die alleen aangemaakt
 * als je het thema opnieuw activeerde. Doe je dat niet, dan wijst het menu naar
 * een pagina die niet bestaat en staat er een 404 waar niemand op bedacht is.
 *
 * Alleen aanvullen, nooit overschrijven: wat de klant zelf heeft aangepast
 * blijft staan.
 */
function cjt_bijwerken() {
	$gedaan = get_option( CJT_INSTALLATIE );

	if ( ! $gedaan || CJT_VERSION === $gedaan ) {
		return;
	}

	cjt_installatie_paginas();
	cjt_installatie_menu();

	update_option( CJT_INSTALLATIE, CJT_VERSION );
}
add_action( 'admin_init', 'cjt_bijwerken' );

/**
 * Nette URL's aanzetten als ze nog op de kale ?p=123-vorm staan.
 *
 * Zonder dit krijgt een pagina een URL als ?page_id=12 in plaats van /diensten,
 * en die woorden in de URL zijn precies waar de vindbaarheid op leunt.
 */
function cjt_installatie_permalinks() {
	if ( '' !== get_option( 'permalink_structure' ) ) {
		return;
	}

	global $wp_rewrite;

	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules();
}

/**
 * Maakt de startpagina aan en zet hem als voorpagina.
 */
function cjt_installatie_startpagina() {
	// Staat er al een voorpagina ingesteld, dan blijven we daar vanaf.
	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
		return;
	}

	$bestaand = get_page_by_path( 'home' );

	if ( $bestaand ) {
		$pagina_id = $bestaand->ID;
	} else {
		$pagina_id = wp_insert_post(
			array(
				'post_title'   => __( 'Home', 'creajt-starter' ),
				'post_name'    => 'home',
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => cjt_startpagina_inhoud(),
			)
		);
	}

	if ( is_wp_error( $pagina_id ) || ! $pagina_id ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pagina_id );
}

/**
 * Zet het merklogo klaar als sitelogo.
 *
 * Het logo hoort bij het thema, dus hoeft niemand het te zoeken en te uploaden.
 * Het bestand gaat één keer de mediabibliotheek in; daarna kan de klant het
 * gewoon vervangen zoals elke andere afbeelding.
 */
function cjt_installatie_logo() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		return;
	}

	$bron = CJT_DIR . '/assets/beeld/logo.png';

	if ( ! file_exists( $bron ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$uploads = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return;
	}

	$doel = trailingslashit( $uploads['path'] ) . 'sitelogo.png';

	if ( ! copy( $bron, $doel ) ) {
		return;
	}

	$bijlage_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/png',
			'post_title'     => __( 'Logo', 'creajt-starter' ),
			'post_status'    => 'inherit',
		),
		$doel
	);

	if ( is_wp_error( $bijlage_id ) || ! $bijlage_id ) {
		return;
	}

	wp_update_attachment_metadata( $bijlage_id, wp_generate_attachment_metadata( $bijlage_id, $doel ) );
	set_theme_mod( 'custom_logo', $bijlage_id );
}
