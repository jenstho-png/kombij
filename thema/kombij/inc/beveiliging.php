<?php
/**
 * Beveiliging en vertrouwen.
 *
 * Zorginstellingen hebben strenge webfilters op hun laptops. Die kijken naar de
 * reputatie van een site: is hij netjes onderhouden, praat hij niet met vreemde
 * servers, en kondigt hij zich goed aan? Deze regels helpen daarbij, en ze
 * maken de site ook gewoon veiliger voor bezoekers.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Beveiligingsregels die met elke pagina meegaan.
 *
 * De regel voor HTTPS (Strict-Transport-Security) gaat alleen mee als de site
 * al over HTTPS draait. Anders sluit je bezoekers buiten op een server waar het
 * certificaat nog niet goed staat.
 */
function kbj_beveiligingsregels() {
	if ( headers_sent() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()' );
	header( 'Cross-Origin-Opener-Policy: same-origin' );

	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
	}
}
add_action( 'send_headers', 'kbj_beveiligingsregels' );

/**
 * /.well-known/security.txt: waar iemand een beveiligingsprobleem kan melden.
 *
 * Het is een kleine moeite en een signaal dat de site onderhouden wordt. Een
 * aantal filters en scanners kijkt er ook naar.
 */
function kbj_security_txt() {
	if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$pad = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );

	if ( '/.well-known/security.txt' !== (string) $pad ) {
		return;
	}

	$email = kbj_optie( 'email', (string) get_option( 'admin_email' ) );

	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );

	echo 'Contact: mailto:' . esc_html( $email ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo 'Expires: ' . esc_html( gmdate( 'Y-m-d\TH:i:s\Z', time() + YEAR_IN_SECONDS ) ) . "\n";
	echo "Preferred-Languages: nl, en\n";
	echo 'Canonical: ' . esc_url( home_url( '/.well-known/security.txt' ) ) . "\n";
	exit;
}
add_action( 'template_redirect', 'kbj_security_txt', 1 );

/**
 * Minder prijsgeven over de techniek erachter.
 *
 * Een lijst van gebruikers via de REST-api en het versienummer van WordPress
 * helpen alleen iemand die kwaad wil.
 *
 * @param array $eindpunten De eindpunten.
 * @return array
 */
function kbj_gebruikers_verbergen( $eindpunten ) {
	if ( is_user_logged_in() ) {
		return $eindpunten;
	}

	unset( $eindpunten['/wp/v2/users'], $eindpunten['/wp/v2/users/(?P<id>[\d]+)'] );

	return $eindpunten;
}
add_filter( 'rest_endpoints', 'kbj_gebruikers_verbergen' );

add_filter( 'xmlrpc_enabled', '__return_false' );
