<?php
/**
 * Title: De familie achter KomBij
 * Slug: kombij/familie
 * Categories: kombij
 * Description: Wie er achter KomBij zit, met een foto in kerkvorm ernaast.
 */

$tekst = kbj_p( 'De familie achter KomBij', 'kbj-boven' )
	. kbj_kop( 2, 'Zorg vanuit ons hart' )
	. kbj_p( 'KomBij is begonnen door Corrie Roelofsen, haar dochter Chantal en schoonzoon Edwin. Een familiebedrijf, en zo voelt het ook.' )
	. kbj_p( 'U belt niet met een afdeling, maar met Corrie. U kent de mensen die voor uw vader of moeder zorgen. En wie binnenloopt, krijgt eerst een kop koffie.' )
	. kbj_p( 'Geen instelling, maar een plek waar u uzelf kunt zijn.', 'kbj-nadruk' )
	. kbj_p( '<a href="/over-ons/">Maak kennis met KomBij</a>', 'kbj-verder' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => kbj_foto(
					'samen-aan-tafel.webp',
					'Gasten en medewerkers aan de lange tafel in de kerk, onder de tussenverdieping',
					array( 'positie' => '45% 50%' )
				),
				'breedte' => '45%',
			),
			array(
				'inhoud'  => $tekst,
				'breedte' => '55%',
			),
		),
		'kbj-duo',
		true
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
		'ornament'    => true,
	)
);
