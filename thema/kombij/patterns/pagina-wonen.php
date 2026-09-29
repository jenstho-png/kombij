<?php
/**
 * Title: Pagina wonen met zorg
 * Slug: kombij/pagina-wonen
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Wonen met zorg.
 */

$verhaal = kbj_kop( 2, 'Leven zoals u gewend bent' )
	. kbj_p( 'U wilt zelf bepalen hoe uw dag eruitziet. Maar wat als dat steeds lastiger wordt? Als u de weg kwijtraakt, woorden niet meer vindt of het alleen zijn te zwaar wordt?' )
	. kbj_p( 'Dan is het fijn als er iemand met u meekijkt. Iemand die regelt wat niet meer lukt, zonder het ingewikkelder te maken dan nodig.' )
	. kbj_p( 'U mag meedoen, u mag rust nemen, en u mag uzelf zijn.', 'kbj-nadruk' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $verhaal,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_foto( 'slaapkamer.webp', 'Een zit-slaapkamer met een bed, twee stoelen en een glas-in-loodraam onder een gewelf' ),
				'breedte' => '45%',
			),
		),
		'kbj-duo kbj-duo--omgekeerd',
		true
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_beeldband( 'kerk-bloesem.webp', 'Uw eigen plek in een monument. <span class="kbj-hl-licht">Met zorg die dag en nacht dichtbij is.</span>', 'nacht', '50% 40%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Wonen bij KomBij',
		'Een eigen kamer in een klein huis',
		'Wij bieden plaats aan zes bewoners met een WLZ-indicatie. U heeft een eigen zit-slaapkamer en deelt de rest van het huis met de andere bewoners.',
		false
	)
	. kbj_lijst(
		array(
			'Een eigen zit-slaapkamer',
			'Een ruime huiskamer om samen te eten en te praten',
			'Een atelier om te schilderen en te handwerken',
			'Een fitnessruimte om elke dag te bewegen',
			'Elke dag gebruik van de zoutkamer',
			'Badkamers en toiletten in het huis',
			'Een buitenruimte en wandelpaden langs de Maas',
			'Een open deur: familie en vrienden zijn altijd welkom',
		),
		'kbj-lijst kbj-lijst--twee'
	)
	. kbj_groep(
		kbj_foto( 'slaapkamer-raam.webp', 'Het glas-in-loodraam van een kamer, met donkere gordijnen', array( 'vorm' => 'recht', 'verhouding' => '3/4' ) )
		. kbj_foto( 'zitplek.webp', 'Een fauteuil en een ronde tafel met bloemen', array( 'vorm' => 'recht', 'verhouding' => '3/4' ) )
		. kbj_foto( 'schip.webp', 'Het schip van de kerk met de nieuwe tussenverdieping', array( 'vorm' => 'recht', 'verhouding' => '3/4' ) )
		. kbj_foto( 'kerk-interieur.webp', 'De gewelven en ramen van de kerk', array( 'vorm' => 'recht', 'verhouding' => '3/4', 'positie' => '80% 50%' ) ),
		'kbj-strook'
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);
?>
<!-- wp:pattern {"slug":"kombij/dag"} /-->
<?php
$voor_wie = kbj_kop( 2, 'Voor wie is wonen bij KomBij?' )
	. kbj_p( 'Voor ouderen en volwassenen die niet meer zelfstandig kunnen wonen en 24 uur per dag zorg en toezicht nodig hebben. Bijvoorbeeld bij dementie, of als u intensieve verzorging of verpleging nodig heeft.' )
	. kbj_p( 'Voordat u komt wonen, maken we kennis. Met u en met uw familie. We maken samen een zorgplan, met daarin ook wat u leuk vindt en wat u belangrijk vindt.' );

$kosten = kbj_kader(
	kbj_kop( 3, 'Wat kost wonen?' )
	. kbj_p( 'Wonen vanaf € 985 per maand', 'kbj-prijs' )
	. kbj_p( 'Dat is voor de kamer, exclusief servicekosten en zorg. De zorg wordt betaald vanuit de WLZ. Daarnaast betaalt u een eigen bijdrage aan het CAK, die hangt af van uw inkomen.' )
	. kbj_p( 'We rekenen het graag samen met u uit, zodat u vooraf weet waar u aan toe bent.' )
	. kbj_p( '<a href="/kosten-en-financiering/">Meer over kosten en financiering</a>', 'kbj-verder' )
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $voor_wie,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => $kosten,
				'breedte' => '45%',
			),
		)
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_vragenblok( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'Vragen over wonen',
	array(
		array( 'Hoe kom ik aan een WLZ-indicatie?', 'Die vraagt u aan bij het CIZ, het Centrum indicatiestelling zorg. Vindt u dat lastig? Wij helpen u met de aanvraag, of we kijken samen met uw huisarts of casemanager wat de beste stap is.' ),
		array( 'Kan ik blijven wonen als mijn zorg zwaarder wordt?', 'Dat bespreken we altijd samen, met u en uw familie. Zolang wij de zorg goed en veilig kunnen geven, blijft u bij ons wonen.' ),
		array( 'Mag mijn familie op bezoek komen wanneer ze willen?', 'Ja. Wij hebben een opendeurbeleid. Familie en vrienden zijn altijd welkom.' ),
		array( 'Is er een wachtlijst?', 'Bel ons voor de plekken die nu vrij zijn. Is er nog geen plek, dan kunt u vaak al wel logeren of naar de dagbesteding komen. Zo leert u ons alvast kennen.' ),
	)
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
