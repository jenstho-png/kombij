<?php
/**
 * Title: Wat zoekt u?
 * Slug: kombij/aanbod
 * Categories: kombij
 * Description: Wonen, logeren en dagbesteding als drie kaarten met een foto, een pakkende kop, één zin en een knop.
 */

$kaart = function ( $bestand, $alt, $kop, $tekst, $link, $knop ) {
	return kbj_groep(
		kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => '4/3' ) )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. kbj_knoppen(
				array(
					array(
						'tekst' => $knop,
						'url'   => $link,
					),
				)
			),
			'kbj-kaart3__inhoud'
		),
		'kbj-kaart3'
	);
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_kop( 2, 'Wat zoekt u voor uw vader of moeder?', 'kbj-schuif' )
		. kbj_p( 'Drie manieren om bij ons te zijn. Hetzelfde huis, hetzelfde vertrouwde team.' ),
		'kbj-kop-groot'
	)
	. kbj_groep(
		$kaart( 'slaapkamer.webp', 'Een lichte kamer met een hoog glas-in-loodraam', 'Een nieuw thuis', 'Een eigen kamer in een huis voor zes bewoners, met dag en nacht zorg dichtbij.', '/wonen-met-zorg/', 'Wonen met zorg' )
		. $kaart( 'zitplek.webp', 'Een fauteuil en een tafeltje met bloemen in een logeerkamer', 'Even op adem komen', 'Uw naaste logeert een paar nachten bij ons. U rust uit, wij zorgen.', '/logeren-met-zorg/', 'Logeren met zorg' )
		. $kaart( 'dagbesteding.webp', 'Gasten en een begeleider lachen samen aan tafel', 'Nooit meer de hele dag alleen', 'Koffie, bewegen, schilderen en samen lunchen. Maandag tot en met vrijdag.', '/dagbesteding/', 'Dagbesteding' ),
		'kbj-kaarten3'
	),
	array(
		'klasse' => 'kbj-aanbod',
		'anker'  => 'aanbod',
		'boven'  => '60',
		'onder'  => '50',
	)
);
