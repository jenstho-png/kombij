<?php
/**
 * Title: Pagina over ons
 * Slug: kombij/pagina-over
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Over ons.
 */

echo kbj_kort( array( array( '1869', 'gebouwd, nu een rijksmonument' ), array( '3', 'oprichters uit de regio' ), array( '6', 'bewoners, iedereen kent elkaar' ), array( 'Open deur', 'familie is altijd welkom' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$verhaal = kbj_kop( 2, 'Waarom KomBij?' )
	. kbj_p( 'Geen instelling met lange gangen, maar een klein huis waar iedereen elkaar kent. Dat wilden Corrie, Chantal en Edwin maken.' )
	. kbj_p( 'Toen de Lambertuskerk vrijkwam, zagen ze het voor zich. Edwin verbouwde het gebouw zelf, en de ramen, gewelven en pilaren bleven.' )
	. kbj_p( 'Geen instelling, maar een plek waar u uzelf kunt zijn.', 'kbj-nadruk' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $verhaal,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_foto( 'samen-aan-tafel.webp', 'Gasten en medewerkers aan de lange tafel in de kerk', array( 'positie' => '45% 50%' ) ),
				'breedte' => '45%',
			),
		),
		'kbj-duo kbj-duo--omgekeerd',
		true
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_beeldband( 'kerk-silhouet.webp', 'Ruim 150 jaar kwam het dorp hier samen. <span class="kbj-hl-licht">Nu is het een thuis.</span>', 'nacht', '60% 55%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Onze waarden', 'Waar wij voor staan', '', false )
	. kbj_lijst(
		array(
			'<strong>Persoonlijke aandacht</strong><span>We kennen iedereen bij naam, en weten wat iemand fijn vindt.</span>',
			'<strong>Huiselijkheid</strong><span>Samen koffie, samen eten, samen aan tafel. Zoals thuis.</span>',
			'<strong>Zelf bepalen</strong><span>U bepaalt hoe uw dag eruitziet. Wij kijken wat daarbij nodig is.</span>',
			'<strong>Rust en veiligheid</strong><span>Een vaste dag, vaste gezichten en 24 uur per dag zorg dichtbij.</span>',
			'<strong>Samen kijken wat past</strong><span>Met u en uw familie zoeken we wat bij u past. Nu, en als het verandert.</span>',
		),
		'kbj-regelingen kbj-regelingen--vrij'
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);

$gebouw = kbj_kop( 2, 'De Lambertuskerk' )
	. kbj_p( 'De kerk aan de Raadhuisdijk werd gebouwd in 1868 en 1869, in neogotische stijl. Het is een rijksmonument. Ruim 150 jaar kwam het dorp hier samen.' )
	. kbj_p( 'Boven kwamen lichte kamers, beneden de huiskamer, het atelier en de dagbesteding.' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => kbj_groep(
					kbj_foto( 'kerk-interieur.webp', 'De lichte gewelven en hoge ramen van het gebouw', array( 'vorm' => 'recht', 'verhouding' => '4/3', 'positie' => '50% 40%' ) )
					. kbj_foto( 'schip.webp', 'Het lichte schip met de nieuwe tussenverdieping', array( 'verhouding' => '3/4' ) ),
					'kbj-huis__beelden'
				),
				'breedte' => '52%',
			),
			array(
				'inhoud'  => $gebouw,
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

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'De mensen', 'De familie achter KomBij', 'Samen met een team van verzorgenden, verpleegkundigen, activiteitenbegeleiders en vrijwilligers.', false )
	. kbj_lijst(
		array(
			'<strong>Corrie Roelofsen</strong><em>Oprichter</em><span>Voor vragen, aanmeldingen en rondleidingen spreekt u meestal eerst Corrie.</span>',
			'<strong>Chantal</strong><em>Oprichter</em><span>Samen met haar moeder en haar man begon Chantal KomBij.</span>',
			'<strong>Edwin</strong><em>Oprichter</em><span>Edwin verbouwde de kerk zelf, van stof en puin tot een warm huis.</span>',
		),
		'kbj-regelingen kbj-regelingen--vrij kbj-mensen'
	)
	. kbj_p( '<a href="/werken-bij/">Komt u ons team versterken?</a>', 'kbj-verder' ),
	array( 'klasse' => 'kbj-reveal' )
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
