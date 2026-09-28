<?php
/**
 * Lettertypes: zelf gehost, en alleen wat er echt staat.
 *
 * Waarom niet via Google Fonts: dat is een verzoek naar een server in de VS bij
 * elke bezoeker (AVG) en het kost een extra verbinding voordat er ook maar één
 * letter staat. Zelf hosten is sneller en juridisch schoon.
 *
 * Zet de woff2-bestanden in assets/fonts en noem ze hieronder. Staat een
 * bestand er niet, dan wordt het ook niet ingeladen en valt de site netjes
 * terug op de reservefonts uit theme.json. Geen 404, geen kapotte pagina.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De lettertypes die het thema kent.
 *
 * Twee families is de norm: één voor de koppen, één voor de tekst. Een derde
 * alleen als hij iets doet wat de andere twee niet kunnen.
 *
 * @return array[]
 */
function kbj_font_bestanden() {
	/*
	 * KomBij gebruikt alleen Montserrat: koppen in 700 en 600, tekst in 500.
	 * Eén variabel bestand van 38 kB bevat al die gewichten, dat is kleiner dan
	 * drie losse bestanden en het scheelt twee verzoeken.
	 */
	return array(
		array(
			'bestand' => 'montserrat.woff2',
			'familie' => 'Montserrat',
			'gewicht' => '100 900',
			'stijl'   => 'normal',
		),
	);
}

/**
 * De bestanden die als eerste in beeld staan en dus vooruit opgehaald worden.
 *
 * @return string[]
 */
function kbj_fonts_eerst() {
	return array( 'montserrat.woff2' );
}

/**
 * Bouwt de @font-face-regels voor de bestanden die er daadwerkelijk staan.
 *
 * @return string Lege string als er nog geen fonts staan.
 */
function kbj_font_face_css() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$map = KBJ_DIR . '/assets/fonts/';
	$css = '';

	foreach ( kbj_font_bestanden() as $font ) {
		if ( ! file_exists( $map . $font['bestand'] ) ) {
			continue;
		}

		$css .= sprintf(
			"@font-face{font-family:'%s';font-style:%s;font-weight:%s;font-display:swap;src:url('%s') format('woff2');}",
			$font['familie'],
			$font['stijl'],
			$font['gewicht'],
			esc_url( KBJ_URI . '/assets/fonts/' . $font['bestand'] )
		);
	}

	$cache = $css;

	return $cache;
}

/**
 * De regels meegeven aan de site.
 */
function kbj_fonts_inline() {
	$css = kbj_font_face_css();

	if ( '' === $css ) {
		return;
	}

	wp_add_inline_style( 'kbj-thema', $css );
}
add_action( 'wp_enqueue_scripts', 'kbj_fonts_inline', 20 );

/**
 * De lettertypes die meteen in beeld staan alvast ophalen.
 *
 * Zonder dit ontdekt de browser ze pas nadat de CSS binnen is, en zie je de kop
 * een tel in de reserveletter staan voordat hij omspringt.
 */
function kbj_fonts_preload() {
	if ( is_admin() ) {
		return;
	}

	foreach ( kbj_fonts_eerst() as $bestand ) {
		if ( ! file_exists( KBJ_DIR . '/assets/fonts/' . $bestand ) ) {
			continue;
		}

		printf(
			"<link rel=\"preload\" href=\"%s\" as=\"font\" type=\"font/woff2\" crossorigin>\n",
			esc_url( KBJ_URI . '/assets/fonts/' . $bestand )
		);
	}
}
add_action( 'wp_head', 'kbj_fonts_preload', 1 );

/**
 * Dezelfde regels in de blok-editor, anders ziet de klant daar de reservefonts.
 */
function kbj_fonts_editor() {
	$css = kbj_font_face_css();

	if ( '' === $css ) {
		return;
	}

	wp_register_style( 'kbj-editor-fonts', false, array(), KBJ_VERSION );
	wp_enqueue_style( 'kbj-editor-fonts' );
	wp_add_inline_style( 'kbj-editor-fonts', $css );
}
add_action( 'enqueue_block_assets', 'kbj_fonts_editor' );
