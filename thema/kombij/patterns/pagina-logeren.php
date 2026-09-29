<?php
/**
 * Title: Pagina logeren met zorg
 * Slug: kombij/pagina-logeren
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Logeren met zorg.
 */

echo kbj_kort( array( array( '2 nachten', 'of langer per keer' ), array( '24 uur', 'zorg, net als thuis' ), array( '3', 'vaste logeerblokken per week' ), array( 'Vaak', 'grotendeels vergoed' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$verhaal = kbj_kop( 2, 'Zorgen voor een ander is mooi, maar ook zwaar' )
	. kbj_p( 'Veel mantelzorgers gaan lang door, tot het niet meer gaat. Logeren met zorg, ook wel respijtzorg, geeft u even ruimte. Om te slapen, op vakantie te gaan of even niets te hoeven.' )
	. kbj_p( 'De zorg uit handen geven voelt soms als tekortschieten. Dat is het niet. De meeste gasten voelen zich hier snel thuis.', 'kbj-nadruk' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $verhaal,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_foto( 'slaapkamer.webp', 'Een logeerkamer met een bed, twee stoelen en een glas-in-loodraam' ),
				'breedte' => '45%',
			),
		),
		'kbj-duo kbj-duo--omgekeerd',
		true
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_beeldband( 'maas-avond.webp', 'U mag even niets. <span class="kbj-hl-licht">Wij zorgen voor uw naaste.</span>', 'kerk', '50% 50%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Waarom logeren', 'Wat logeren u kan opleveren', '', false )
	. kbj_lijst(
		array(
			'U kunt even bijkomen, zonder zorgen',
			'Uw naaste kan langer thuis blijven wonen',
			'Een vast logeermoment om naar uit te kijken',
			'Een rustige overgang als verhuizen later nodig is',
			'Een brug naar een plek in het verpleeghuis',
			'Hulp tijdens het herstel van uw partner',
			'Zorgeloos op vakantie, want de zorg is geregeld',
			'In overleg logeert u samen met uw partner',
		),
		'kbj-lijst kbj-lijst--twee'
	),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-reveal',
	)
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Zo werkt het', 'Van eerste telefoontje tot logeernacht', '', false )
	. kbj_lijst(
		array(
			'<strong>Kennismaken</strong><span>U belt of mailt ons. We maken een afspraak en laten u het huis zien.</span>',
			'<strong>Zorgplan</strong><span>Samen maken we afspraken over de zorg en over wat uw naaste leuk vindt. De zorg van thuis gaat bij ons gewoon door.</span>',
			'<strong>Vaste dagen</strong><span>Een logeerperiode is minimaal 2 nachten. Liefst elke week op dezelfde dagen, dat geeft rust.</span>',
			'<strong>Welkom</strong><span>Op de dag van aankomst is de kamer vanaf 10:00 klaar. Medicijnen neemt u mee in de verpakking van de apotheek.</span>',
		),
		'kbj-stappen',
		true
	)
	. kbj_kader(
		kbj_kop( 3, 'De vaste logeerblokken' )
		. kbj_lijst(
			array(
				'Maandag tot en met woensdag',
				'Woensdag tot en met vrijdag',
				'Vrijdag tot en met maandag, het weekend',
			),
			'kbj-lijst'
		),
		'kbj-kader--ijs kbj-kader--ruimte'
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Kosten',
		'Wat kost logeren?',
		'Meestal via de WLZ of de WMO, en anders particulier. Vaak wordt het grootste deel vergoed.',
		false
	)
	. kbj_lijst(
		array(
			'<strong>WLZ</strong><em>Met een WLZ-indicatie</em><span>Logeren is dan meestal vergoed. U betaalt wel een eigen bijdrage aan het CAK, die hangt af van uw inkomen.</span>',
			'<strong>WMO</strong><em>Via uw gemeente</em><span>Voor logeren is de WMO vaak niet genoeg. U betaalt dan zelf een deel bij.</span>',
			'<strong>Particulier</strong><em>Zelf betalen</em><span>Zonder indicatie betaalt u zelf. U hoort vooraf wat het kost, en kunt betalen met een automatische incasso.</span>',
		),
		'kbj-regelingen kbj-regelingen--drie'
	)
	. kbj_p( 'Heeft u een PGB vanuit de WLZ? Dan kunt u tot 156 etmalen per jaar bij ons logeren. Bel ons gerust.', 'kbj-noot' )
	. kbj_p( '<a href="' . esc_url( KBJ_URI . '/assets/documenten/algemene-voorwaarden-logeren.pdf' ) . '">Lees de voorwaarden voor logeren (pdf)</a>', 'kbj-verder' ),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);

echo kbj_vragenblok( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'Vragen over logeren',
	array(
		array( 'Hoe lang kan iemand logeren?', 'Minimaal 2 nachten. De meeste gasten komen elke week op vaste dagen. Langer logeren, bijvoorbeeld tijdens uw vakantie, kan in overleg.' ),
		array( 'Welke zorg krijgt mijn naaste tijdens het logeren?', 'Dezelfde zorg als thuis, en meer als dat nodig is. Er is 24 uur per dag zorg. Vooraf maken we een zorgplan met de afspraken en doelen die voor uw naaste belangrijk zijn.' ),
		array( 'Moet ik bereikbaar zijn tijdens het logeren?', 'Ja. Er moet altijd iemand telefonisch bereikbaar zijn. Meestal is dat de mantelzorger of een ander familielid.' ),
		array( 'Wat als we moeten annuleren?', 'Laat het ons zo snel mogelijk weten. Bij een medische reden rekenen we niets. In andere gevallen gelden de afspraken uit onze voorwaarden voor logeren.' ),
	)
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
