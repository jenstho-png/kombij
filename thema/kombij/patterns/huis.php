<?php
/**
 * Title: Het huis
 * Slug: kombij/huis
 * Categories: kombij
 * Description: Het verhaal van de Lambertuskerk op een nachtblauw vlak, met twee foto's die over elkaar vallen.
 */

$beelden = kbj_groep(
	kbj_foto(
		'maas-avond.webp',
		'De Maas bij Maasbommel in het avondlicht, met de kerktoren in de verte',
		array(
			'vorm'       => 'recht',
			'verhouding' => '4/3',
			'positie'    => '60% 50%',
		)
	)
	. kbj_foto(
		'kerk-bloesem.webp',
		'De toren van de Lambertuskerk achter een bloeiende boom',
		array( 'verhouding' => '3/4' )
	),
	'kbj-huis__beelden'
);

$tekst = kbj_p( 'Het huis', 'kbj-boven' )
	. kbj_kop( 2, 'Een kerk uit 1869, nu een plek om te wonen' )
	. kbj_p( 'KomBij zit in de voormalige H. Lambertuskerk aan de Raadhuisdijk in Maasbommel. Waar het dorp vroeger samenkwam, is nu plek voor wonen, logeren en dagbesteding met zorg.' )
	. kbj_p( 'Edwin verbouwde het monumentale pand zelf. De glas-in-loodramen, de gewelven en de pilaren bleven bewaard. Nieuw zijn de lichte kamers, de huiskamer en het atelier. Buiten stroomt de Maas, met wandelpaden langs de dijk.' )
	. kbj_p( '<a href="/over-ons/">Het verhaal van KomBij</a>', 'kbj-verder' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $beelden,
				'breedte' => '52%',
			),
			array(
				'inhoud'  => $tekst,
				'breedte' => '48%',
			),
		),
		'kbj-duo',
		true
	),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-huis kbj-lood kbj-reveal',
		'ornament'    => true,
	)
);
