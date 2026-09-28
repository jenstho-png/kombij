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
 * @package creajt-starter
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
function cjt_font_bestanden() {
	return array(
		array(
			'bestand' => 'kop-light.woff2',
			'familie' => 'Kopletter',
			'gewicht' => '300',
			'stijl'   => 'normal',
		),
		array(
			'bestand' => 'kop-regular.woff2',
			'familie' => 'Kopletter',
			'gewicht' => '400',
			'stijl'   => 'normal',
		),
		array(
			'bestand' => 'kop-italic.woff2',
			'familie' => 'Kopletter',
			'gewicht' => '400',
			'stijl'   => 'italic',
		),
		array(
			'bestand' => 'tekst-regular.woff2',
			'familie' => 'Tekstletter',
			'gewicht' => '400',
			'stijl'   => 'normal',
		),
		array(
			'bestand' => 'tekst-medium.woff2',
			'familie' => 'Tekstletter',
			'gewicht' => '500',
			'stijl'   => 'normal',
		),
	);
}

/**
 * De bestanden die als eerste in beeld staan en dus vooruit opgehaald worden.
 *
 * @return string[]
 */
function cjt_fonts_eerst() {
	return array( 'kop-light.woff2', 'tekst-regular.woff2' );
}

/**
 * Bouwt de @font-face-regels voor de bestanden die er daadwerkelijk staan.
 *
 * @return string Lege string als er nog geen fonts staan.
 */
function cjt_font_face_css() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$map = CJT_DIR . '/assets/fonts/';
	$css = '';

	foreach ( cjt_font_bestanden() as $font ) {
		if ( ! file_exists( $map . $font['bestand'] ) ) {
			continue;
		}

		$css .= sprintf(
			"@font-face{font-family:'%s';font-style:%s;font-weight:%s;font-display:swap;src:url('%s') format('woff2');}",
			$font['familie'],
			$font['stijl'],
			$font['gewicht'],
			esc_url( CJT_URI . '/assets/fonts/' . $font['bestand'] )
		);
	}

	$cache = $css;

	return $cache;
}

/**
 * De regels meegeven aan de site.
 */
function cjt_fonts_inline() {
	$css = cjt_font_face_css();

	if ( '' === $css ) {
		return;
	}

	wp_add_inline_style( 'cjt-thema', $css );
}
add_action( 'wp_enqueue_scripts', 'cjt_fonts_inline', 20 );

/**
 * De lettertypes die meteen in beeld staan alvast ophalen.
 *
 * Zonder dit ontdekt de browser ze pas nadat de CSS binnen is, en zie je de kop
 * een tel in de reserveletter staan voordat hij omspringt.
 */
function cjt_fonts_preload() {
	if ( is_admin() ) {
		return;
	}

	foreach ( cjt_fonts_eerst() as $bestand ) {
		if ( ! file_exists( CJT_DIR . '/assets/fonts/' . $bestand ) ) {
			continue;
		}

		printf(
			"<link rel=\"preload\" href=\"%s\" as=\"font\" type=\"font/woff2\" crossorigin>\n",
			esc_url( CJT_URI . '/assets/fonts/' . $bestand )
		);
	}
}
add_action( 'wp_head', 'cjt_fonts_preload', 1 );

/**
 * Dezelfde regels in de blok-editor, anders ziet de klant daar de reservefonts.
 */
function cjt_fonts_editor() {
	$css = cjt_font_face_css();

	if ( '' === $css ) {
		return;
	}

	wp_register_style( 'cjt-editor-fonts', false, array(), CJT_VERSION );
	wp_enqueue_style( 'cjt-editor-fonts' );
	wp_add_inline_style( 'cjt-editor-fonts', $css );
}
add_action( 'enqueue_block_assets', 'cjt_fonts_editor' );
