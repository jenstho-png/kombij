<?php
/**
 * Title: Pagina niet gevonden
 * Slug: kombij/nietgevonden
 * Categories: kombij
 * Description: De 404. Geen doodlopende weg maar een afslag terug.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 1, 'Deze pagina bestaat niet (meer)', '', true )
	. kbj_p( 'Misschien is hij verhuisd, of stond er een tikfout in de link. Hieronder vindt u de belangrijkste pagina’s.', 'kbj-intro', true )
	. kbj_knoppen(
		array(
			array(
				'tekst' => 'Wonen',
				'url'   => '/wonen-met-zorg/',
				'rand'  => true,
			),
			array(
				'tekst' => 'Logeren',
				'url'   => '/logeren-met-zorg/',
				'rand'  => true,
			),
			array(
				'tekst' => 'Dagbesteding',
				'url'   => '/dagbesteding/',
				'rand'  => true,
			),
			array(
				'tekst' => 'Contact',
				'url'   => '/contact/',
			),
		),
		true
	),
	array(
		'klasse'   => 'kbj-404',
		'breed'    => false,
		'ornament' => true,
	)
);
