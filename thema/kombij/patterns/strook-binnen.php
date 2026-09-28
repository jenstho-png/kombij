<?php
/**
 * Title: Even binnenkijken
 * Slug: kombij/strook-binnen
 * Categories: kombij
 * Description: Vier foto's naast elkaar met een kort onderschrift. Op de telefoon veegt u er doorheen.
 */

$plekken = array(
	array( 'zitplek.webp', 'Een fauteuil met een kussen en bloesem op tafel', 'Een rustig plekje', 'Voor een kop koffie of een dutje.' ),
	array( 'slaapkamer.webp', 'Een slaapkamer onder een bakstenen gewelf met een glas-in-loodraam', 'Uw eigen kamer', 'Onder de oude gewelven.' ),
	array( 'samen-aan-tafel.webp', 'Bewoners aan tafel met een begeleider', 'Samen aan tafel', 'Eten, kletsen, een spelletje.' ),
	array( 'zoutkamer.webp', 'De zoutkamer met ligstoelen', 'De zoutkamer', 'Even rustig ademhalen.' ),
);

$kaarten = '';

foreach ( $plekken as $plek ) {
	$kaarten .= kbj_groep(
		kbj_foto( $plek[0], $plek[1], array( 'vorm' => 'recht', 'verhouding' => '3/4' ) )
		. kbj_p( '<strong>' . esc_html( $plek[2] ) . '</strong> ' . esc_html( $plek[3] ), 'kbj-binnen__tekst' ),
		'kbj-binnen__plek'
	);
}

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Even binnenkijken', 'kbj-schuif' )
	. kbj_groep( $kaarten, 'kbj-binnen__rij' ),
	array(
		'klasse' => 'kbj-strook-binnen',
		'boven'  => '50',
		'onder'  => '50',
	)
);
