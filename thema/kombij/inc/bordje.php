<?php
/**
 * Het bordje onder de balk: open of gesloten, voor de dagbesteding.
 *
 * Een klein bordje in de vorm van een spitsboog, met de vierpas erop, dat aan
 * twee koordjes onder de menubalk hangt en zachtjes meebeweegt. Het leest zelf
 * de klok, in de tijd van Amsterdam en niet die van de bezoeker, en zegt dan
 * "Open, tot 16:30" of "Gesloten, opent morgen om 10:30".
 *
 * De tijden komen uit Gegevens (Dagbesteding: tijden). Zo staat er nooit iets
 * anders op het bordje dan in de voet en op de contactpagina.
 *
 * Zonder JavaScript staat er de vaste tekst met de openingstijden, zodat het
 * bordje nooit iets verkeerds zegt.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tekent het bordje, als de tijden goed zijn ingevuld.
 *
 * @return string
 */
function kbj_bordje() {
	$tijd = kbj_optie( 'dagbesteding_tijd', '10:30-16:30' );

	if ( ! preg_match( '/^(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', $tijd, $treffer ) ) {
		return '';
	}

	return sprintf(
		'<div class="kbj-bordje" data-open="%1$s" data-dicht="%2$s" data-dagen="1,2,3,4,5">'
			. '<span class="kbj-bordje__koord kbj-bordje__koord--links" aria-hidden="true"></span>'
			. '<span class="kbj-bordje__koord kbj-bordje__koord--rechts" aria-hidden="true"></span>'
			. '<p class="kbj-bordje__plaat" role="status">'
				. '<span class="kbj-bordje__wat">%3$s</span>'
				. '<span class="kbj-bordje__stand" data-stand>%4$s</span>'
				. '<span class="kbj-bordje__wanneer" data-wanneer>%5$s</span>'
			. '</p>'
		. '</div>',
		esc_attr( $treffer[1] ),
		esc_attr( $treffer[2] ),
		esc_html__( 'Dagbesteding', 'kombij' ),
		esc_html__( 'Ma t/m vr', 'kombij' ),
		esc_html( $treffer[1] . ' tot ' . $treffer[2] )
	);
}
