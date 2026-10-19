<?php
/**
 * Title: Even binnenkijken
 * Slug: kombij/strook-binnen
 * Categories: kombij
 * Description: Vier foto's naast elkaar met een kort onderschrift. Op de telefoon veegt u er doorheen.
 */

$plekken = array(
	array( 'shoot-kamer-stoel.webp', 'Een fauteuil bij een hoog raam onder het gewelf', 'Een rustig plekje', 'Voor een kop koffie of een dutje.' ),
	array( 'shoot-kamer-raam.webp', 'Een slaapkamer onder een bakstenen gewelf met een glas-in-loodraam', 'Uw eigen kamer', 'Onder de oude gewelven.' ),
	array( 'shoot-spel-lachen.webp', 'Twee gasten lachen samen aan tafel', 'Samen aan tafel', 'Eten, kletsen, een spelletje.' ),
	array( 'shoot-huiskamer.webp', 'De huiskamer met de lange tafel en hoge ramen', 'De huiskamer', 'Samen koffie, samen eten.' ),
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
