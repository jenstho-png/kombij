<?php
/**
 * Title: Bedankt
 * Slug: kombij/bedankt
 * Categories: kombij
 * Description: De pagina waar iemand belandt nadat het formulier verstuurd is.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_p( 'Bericht ontvangen', 'kbj-boven has-text-align-center', true )
	. kbj_kop( 1, 'Dank u wel, uw bericht is onderweg', '', true )
	. kbj_p( 'We nemen zo snel mogelijk contact met u op. Heeft u haast? Bel ons gerust op <a href="' . esc_attr( kbj_tel_url() ) . '">' . esc_html( kbj_tel_tekst() ) . '</a>.', 'kbj-intro', true )
	. kbj_knoppen(
		array(
			array(
				'tekst' => 'Terug naar de startpagina',
				'url'   => '/',
				'rand'  => true,
			),
		),
		true
	),
	array(
		'klasse'   => 'kbj-bedankt',
		'breed'    => false,
		'ornament' => true,
	)
);
