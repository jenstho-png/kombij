<?php
/**
 * Title: Pagina kosten en financiering
 * Slug: kombij/pagina-kosten
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Kosten en financiering.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'De regelingen',
		'Vier manieren om zorg te betalen',
		'In Nederland zijn er vier manieren om zorg zoals bij KomBij te betalen. Hieronder staat per regeling wat het is en voor wie.',
		false
	)
	. kbj_lijst( kbj_regelingen(), 'kbj-regelingen' ),
	array( 'klasse' => 'kbj-reveal' )
);

$aanbod = kbj_kolommen(
	array(
		array(
			'inhoud' => kbj_kader(
				kbj_kop( 3, 'Wonen' )
				. kbj_p( 'Met een WLZ-indicatie. U betaalt een eigen bijdrage aan het CAK, en een vast bedrag per maand voor de kamer en de service.' )
				. kbj_p( '<a href="/wonen-met-zorg/">Meer over wonen</a>', 'kbj-verder' )
			),
		),
		array(
			'inhoud' => kbj_kader(
				kbj_kop( 3, 'Logeren' )
				. kbj_p( 'Via de WLZ, de WMO, een PGB of particulier. Met een PGB vanuit de WLZ kunt u tot 156 etmalen per jaar logeren.' )
				. kbj_p( '<a href="/logeren-met-zorg/">Meer over logeren</a>', 'kbj-verder' )
			),
		),
		array(
			'inhoud' => kbj_kader(
				kbj_kop( 3, 'Dagbesteding' )
				. kbj_p( 'Meestal via de WMO van uw gemeente, of via de WLZ. Zonder indicatie kan het particulier.' )
				. kbj_p( '<a href="/dagbesteding/">Meer over dagbesteding</a>', 'kbj-verder' )
			),
		),
	),
	'kbj-drie'
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Per onderdeel', 'Wat geldt voor wonen, logeren en dagbesteding?', '', false )
	. $aanbod,
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Een indicatie aanvragen', 'Hoe vraagt u een indicatie aan?', '', false )
	. kbj_lijst(
		array(
			'<strong>WLZ via het CIZ</strong><span>Heeft u blijvend 24 uur per dag zorg of toezicht nodig? Dan vraagt u een WLZ-indicatie aan bij het CIZ.</span>',
			'<strong>WMO via de gemeente</strong><span>Woont u thuis en zoekt u dagbesteding of logeeropvang? Neem dan contact op met het WMO-loket van uw gemeente.</span>',
			'<strong>Een PGB</strong><span>Met een indicatie kunt u kiezen voor een PGB. Dat regelt u via het zorgkantoor bij de WLZ, of via de gemeente bij de WMO.</span>',
			'<strong>De eigen bijdrage</strong><span>Bij de WLZ en de WMO betaalt u een eigen bijdrage aan het CAK. Op cak.nl rekent u uit hoeveel.</span>',
		),
		'kbj-stappen',
		true
	)
	. kbj_p( 'Vindt u het ingewikkeld? Dat is het ook. Bel ons gerust op <a href="' . esc_attr( kbj_tel_url() ) . '">' . esc_html( kbj_tel_tekst() ) . '</a>, dan lopen we het samen met u door.', 'kbj-nadruk' ),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_vragenblok( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'Vragen over kosten',
	array(
		array( 'Wat is het CAK?', 'Het CAK int de eigen bijdrage voor zorg uit de WLZ en de WMO. Hoe hoog die is, hangt af van uw inkomen en vermogen. Op cak.nl kunt u het zelf uitrekenen.' ),
		array( 'Wat is het verschil tussen de WLZ en de WMO?', 'De WLZ is voor mensen die blijvend 24 uur per dag zorg of toezicht nodig hebben. De WMO is voor mensen die thuis wonen en wat hulp nodig hebben om dat vol te houden. De WLZ regelt het zorgkantoor, de WMO regelt de gemeente.' ),
		array( 'Kan ik ook zonder indicatie bij KomBij terecht?', 'Ja. U kunt particulier logeren of naar de dagbesteding komen. U hoort vooraf wat het kost.' ),
		array( 'Helpen jullie met de aanvraag?', 'Ja. We weten hoe het werkt en denken met u mee. Bel of mail ons, dan kijken we samen wat voor u de beste weg is.' ),
	)
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
