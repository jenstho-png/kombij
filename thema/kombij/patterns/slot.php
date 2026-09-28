<?php
/**
 * Title: Slotsectie
 * Slug: kombij/slot
 * Categories: kombij
 * Description: De laatste stap onderaan elke pagina: een rondleiding plannen of bellen.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<!-- wp:kbj/raamlijn /-->' . "\n"
	. kbj_groep(
		kbj_kop( 2, 'Kom een kop koffie drinken.', 'kbj-schuif', true )
		. kbj_p( 'Zien is ervaren. Plan een rondleiding en voel zelf hoe het is bij KomBij. Liever eerst even bellen? Dat kan natuurlijk ook.', 'kbj-intro', true )
		. kbj_knoppen(
			array(
				array(
					'tekst' => 'Plan een rondleiding',
					'url'   => '/contact/',
				),
				kbj_tel_knop(),
			),
			true
		),
		'kbj-slot__binnen'
	),
	array(
		'achtergrond' => 'glasblauw',
		'klasse'      => 'kbj-slot kbj-lood',
		'breed'       => false,
	)
);
