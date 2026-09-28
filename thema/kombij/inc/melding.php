<?php
/**
 * De melding: een klein kaartje rechtsonder dat na een tijdje verschijnt.
 *
 * Voor nieuws dat even aandacht verdient: plekken vrij in de zoutkamer, het
 * café dat open is, een open dag. KomBij zet hem zelf aan en uit onder
 * Gegevens, met een eigen kop, tekst en knop.
 *
 * Bewust geen venster dat de pagina blokkeert: wie hier komt zoekt informatie,
 * vaak over iets wat zwaar is. Het kaartje schuift rustig in beeld, steelt de
 * focus niet, sluit met één klik of met Escape, en komt daarna niet terug.
 * Verandert de tekst, dan ziet iedereen hem opnieuw een keer.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tekent de melding onderaan de pagina, als hij aan staat.
 */
function kbj_melding() {
	if ( is_admin() || 'aan' !== kbj_optie( 'melding_aan', 'uit' ) || is_page( array( 'bedankt', 'contact' ) ) ) {
		return;
	}

	$titel = kbj_optie( 'melding_titel' );
	$tekst = kbj_optie( 'melding_tekst' );

	if ( '' === $titel && '' === $tekst ) {
		return;
	}

	$knop     = kbj_optie( 'melding_knop' );
	$link     = kbj_optie( 'melding_link' );
	$seconden = max( 3, min( 120, (int) kbj_optie( 'melding_seconden', '12' ) ) );

	if ( '' !== $link && 0 === strpos( $link, '/' ) ) {
		$link = home_url( $link );
	}

	printf(
		'<aside class="kbj-melding" aria-label="%1$s" aria-live="polite" data-seconden="%2$d" data-sleutel="%3$s" hidden>'
			. '<button class="kbj-melding__sluit" type="button" aria-label="%4$s"><span aria-hidden="true">&times;</span></button>'
			. '%5$s%6$s%7$s'
		. '</aside>',
		esc_attr__( 'Melding', 'kombij' ),
		(int) $seconden,
		esc_attr( substr( md5( $titel . $tekst . $knop . $link ), 0, 10 ) ),
		esc_attr__( 'Melding sluiten', 'kombij' ),
		'' !== $titel ? '<p class="kbj-melding__titel">' . esc_html( $titel ) . '</p>' : '',
		'' !== $tekst ? '<p class="kbj-melding__tekst">' . esc_html( $tekst ) . '</p>' : '',
		'' !== $knop && '' !== $link ? '<a class="kbj-knop kbj-melding__knop" href="' . esc_url( $link ) . '">' . esc_html( $knop ) . '</a>' : ''
	);
}
add_action( 'wp_footer', 'kbj_melding' );
