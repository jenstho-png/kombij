<?php
/**
 * Title: Een familie, geen instelling
 * Slug: kombij/familie
 * Categories: kombij
 * Description: Wie er achter KomBij zitten, in twee zinnen, met een grote foto.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_foto( 'koffie.webp', 'Een kopje koffie wordt met een glimlach aangereikt', array( 'vorm' => 'recht', 'verhouding' => '4/5', 'positie' => '50% 45%' ) )
		. kbj_groep(
			kbj_kop( 2, 'Een familie. Geen instelling.', 'kbj-schuif' )
			. kbj_p( 'KomBij is opgezet door een moeder, haar dochter en haar schoonzoon uit de regio. Bewust klein gehouden: hier kent iedereen elkaar bij naam.' )
			. kbj_knoppen(
				array(
					array(
						'tekst' => 'Maak kennis met KomBij',
						'url'   => '/over-ons/',
					),
				)
			)
		),
		'kbj-familie2'
	),
	array( 'klasse' => 'kbj-reveal' )
);
