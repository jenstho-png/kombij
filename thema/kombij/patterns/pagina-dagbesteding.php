<?php
/**
 * Title: Pagina dagbesteding
 * Slug: kombij/pagina-dagbesteding
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Dagbesteding.
 */

echo kbj_kort( array( array( 'Ma t/m vr', 'van 10:30 tot 16:30' ), array( '2 dagen', 'per week, u kiest welke' ), array( 'WMO', 'of WLZ, of particulier' ), array( 'Samen', 'koffie, lunch en activiteiten' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$verhaal = kbj_kop( 2, 'Wat kunt u nog, en wat vindt u leuk?' )
	. kbj_p( 'Misschien kwam het er nooit van om hobby’s te hebben, of lukt wat u altijd deed niet meer. Samen zoeken we wat bij u past.' )
	. kbj_p( 'Nieuwe mensen, een nieuwe hobby, aandacht voor uw verhaal. En wie rust nodig heeft, zit heerlijk in een relaxstoel.', 'kbj-nadruk' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $verhaal,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_foto( 'koffie.webp', 'Een kopje koffie op een schoteltje wordt aangereikt', array( 'positie' => '50% 40%' ) ),
				'breedte' => '45%',
			),
		),
		'kbj-duo kbj-duo--omgekeerd',
		true
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_beeldband( 'samen-aan-tafel.webp', 'Samen koffie. Samen lunchen. <span class="kbj-hl-licht">Samen de dag door.</span>', 'nacht', '55% 50%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Het programma',
		'Iedere dag iets anders',
		'We beginnen samen met koffie, thee of fris. Daarna kiest u uit de activiteiten van die dag.',
		false
	)
	. kbj_lijst(
		array(
			'Bewegen en wandelen',
			'Muziek luisteren en samen zingen',
			'De krant lezen en bespreken',
			'Schilderen, tekenen en kleuren',
			'Handwerken en kaarten maken',
			'Geheugentraining',
			'Gezelschaps&shy;spellen',
			'Samen aan tafel voor de lunch',
		),
		'kbj-lijst kbj-lijst--twee'
	)
	. kbj_p( 'Liever iets voor uzelf? Dat kan ook. En op vaste momenten kunt u de zoutkamer gebruiken.' ),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-reveal',
	)
);

$voor_wie = kbj_kop( 2, 'Voor wie is de dagbesteding?' )
	. kbj_p( 'Voor ouderen die nog thuis wonen. Iedereen komt met een eigen reden:' )
	. kbj_lijst( array( 'U komt moeilijk de deur uit of voelt zich eenzaam', 'U wordt wat vergeetachtig', 'Als aanvulling op de zorg van familie of thuiszorg' ), 'kbj-lijst' )
	. kbj_kop( 3, 'Wie begeleidt u?' )
	. kbj_p( 'Activiteitenbegeleiders, verzorgenden en verpleegkundigen. Zij werken samen met vrijwilligers en stagiaires.' );

$tijden = kbj_kader(
	kbj_kop( 3, 'Dagen en tijden' )
	. kbj_lijst(
		array(
			'<span>Maandag tot en met vrijdag</span><span>10:30 tot 16:30</span>',
			'<span>Zaterdag en zondag</span><span>gesloten</span>',
		),
		'kbj-tijden'
	)
	. kbj_p( 'U komt minimaal 2 dagen per week. Welke dagen, dat kiest u zelf.' )
	. kbj_kop( 3, 'Vervoer' )
	. kbj_p( 'Kunt u niet zelf komen? Dan kunt u een indicatie voor vervoer aanvragen. Wij helpen u daarbij.' )
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $voor_wie,
				'breedte' => '55%',
			),
			array(
				'inhoud'  => $tijden,
				'breedte' => '45%',
			),
		)
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Kosten',
		'Wat kost dagbesteding?',
		'Meestal vergoed via de WMO van uw gemeente, of via de WLZ. Zonder indicatie kan het ook particulier. Bel ons, dan zoeken we uit wat voor u geldt.',
		false
	)
	. kbj_p( '<a href="/kosten-en-financiering/">Meer over kosten en financiering</a>', 'kbj-verder' ),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
		'boven'       => '50',
		'onder'       => '50',
	)
);

echo kbj_vragenblok( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'Vragen over dagbesteding',
	array(
		array( 'Mag ik eerst een keer meedraaien?', 'Ja, dat raden we zelfs aan. Kom een dag kijken, dan merkt u vanzelf of het bij u past.' ),
		array( 'Hoe vraag ik dagbesteding aan via de WMO?', 'U neemt contact op met het WMO-loket van uw gemeente. Woont u in Maasbommel of omgeving, dan is dat de gemeente West Maas en Waal. Iemand van de gemeente gaat dan met u in gesprek. Wij denken graag met u mee.' ),
		array( 'Moet ik elke week dezelfde dagen komen?', 'U komt minimaal 2 dagen per week. Vaste dagen geven rust, maar in overleg kunt u ook schuiven.' ),
		array( 'Kan ik ook komen als ik vergeetachtig ben?', 'Ja. Veel gasten komen juist daarom. Onze begeleiders weten hoe ze u daarbij kunnen helpen, zonder dat het de dag bepaalt.' ),
	)
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
