<?php
/**
 * Title: Herkent u dit?
 * Slug: kombij/herkenning
 * Categories: kombij
 * Description: De vragen die een mantelzorger zichzelf stelt, en wat KomBij daarop antwoordt.
 */

$links = kbj_p( 'Voor mantelzorgers en familie', 'kbj-boven' )
	. kbj_kop( 2, 'Herkent u dit?' )
	. kbj_p( 'Voor iemand zorgen doet u uit liefde. Maar het kost ook veel. Veel mensen die bij ons aanbellen, hebben lang gewacht. Ze wilden het zelf blijven doen.' )
	. kbj_p( 'Samen kijken we wat past. Soms is dat een paar dagen dagbesteding per week. Soms een logeerweekend, zodat u even kunt bijkomen. En soms is het tijd voor een nieuw thuis.' )
	. kbj_p( '<a href="/logeren-met-zorg/">Lees wat logeren voor u kan doen</a>', 'kbj-verder' );

$rechts = kbj_lijst(
	array(
		'“Mijn moeder vergeet steeds meer. Ik lig wakker als ze alleen thuis is.”',
		'“Ik word ’s nachts steeds wakker van mijn man. Ik ben zo moe.”',
		'“Ik wil een week weg, maar wie zorgt er dan voor mijn vader?”',
		'“Thuis gaat het niet meer. Maar een groot verpleeghuis voelt niet goed.”',
	),
	'kbj-herkenning__vragen'
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $links,
				'breedte' => '45%',
			),
			array(
				'inhoud'  => $rechts,
				'breedte' => '55%',
			),
		),
		'',
		true
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-herkenning kbj-reveal',
	)
);
