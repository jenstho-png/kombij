<?php
/**
 * Doorsturen vanaf de oude site.
 *
 * De vorige site van KomBij had andere adressen. Google en mensen met een
 * bladwijzer kennen die nog. Elk oud adres stuurt daarom blijvend (301) door
 * naar de pagina die er nu het best bij past. Zo blijft de plek in Google
 * behouden en komt niemand op een 404 terecht. Niet alles naar de startpagina:
 * dan raakt een pagina zijn positie kwijt.
 *
 * De lijst komt uit de sitemap en de links van kombijmaasbommel.nl, opgehaald
 * op 5 oktober 2026.
 *
 * @package KomBij
 */

defined( 'ABSPATH' ) || exit;

/**
 * Oude adressen en waar ze nu heen gaan.
 *
 * Een doel dat met "vacatures/" begint, is een vacature. Bestaat die niet meer,
 * dan gaat het naar Werken bij.
 *
 * @return array Oud pad (zonder domein, zonder slash aan het eind) => nieuw pad.
 */
function kbj_oude_adressen() {
	return array(
		// Pagina's.
		'zorgeloos-wonen'                                          => 'wonen-met-zorg',
		'zorgeloos-logeren'                                        => 'logeren-met-zorg',
		'werken-in-de-zorg-bij-kombij-maasbommel'                  => 'werken-bij',
		'openingstijden'                                           => 'contact',

		// Pdf's van de oude site.
		'wp-content/uploads/2026/01/vacature-nachtdienst.pdf'      => 'vacatures/nachtdienst',
		'wp-content/uploads/2025/07/verzorgende-ig.pdf'            => 'vacatures/verzorgende-ig',
		'wp-content/uploads/2025/07/verpleegkundige-hbo.pdf'       => 'vacatures/verpleegkundige-hbo',
		'wp-content/uploads/2025/07/helpende-plus.pdf'             => 'vacatures/helpende-plus',
		'wp-content/uploads/2025/06/veel-gestelde-vragen-zoutkamer.pdf' => 'zoutkamer',
		'wp-content/uploads/2025/06/privacyreglement-avg-de-kombij.pdf' => 'voorwaarden',
		'wp-content/uploads/2025/06/huisregels-de-kombij.pdf'      => 'voorwaarden',
		'wp-content/uploads/2025/06/algemene-voorwaarden-logeren-met-zorg.pdf' => 'voorwaarden',
	);
}

/**
 * Stuurt een oud adres door.
 *
 * Draait vroeg, en ook als er nog een oude pagina met dat adres bestaat: staat
 * de nieuwe site in dezelfde WordPress als de oude, dan zouden de oude pagina's
 * anders gewoon blijven verschijnen.
 */
function kbj_oud_doorsturen() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	$pad = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	// Werkt ook als de site in een submap staat.
	$basis = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

	if ( '' !== $basis && '/' !== $basis && 0 === strpos( $pad, $basis ) ) {
		$pad = substr( $pad, strlen( $basis ) );
	}

	$pad    = strtolower( trim( rawurldecode( $pad ), '/' ) );
	$lijst  = kbj_oude_adressen();

	if ( '' === $pad || ! isset( $lijst[ $pad ] ) ) {
		return;
	}

	$doel = $lijst[ $pad ];

	// Een vacature die er niet meer is: dan naar het overzicht van werken bij.
	if ( 0 === strpos( $doel, 'vacatures/' ) && ! get_page_by_path( substr( $doel, 10 ), OBJECT, KBJ_VACATURE ) ) {
		$doel = 'werken-bij';
	}

	wp_safe_redirect( home_url( '/' . $doel . '/' ), 301, 'KomBij' );
	exit;
}
add_action( 'template_redirect', 'kbj_oud_doorsturen', 1 );
