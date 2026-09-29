<?php
/**
 * Title: Pagina kosten en financiering
 * Slug: kombij/pagina-kosten
 * Categories: kombij
 * Description: Alle secties onder de kop van de pagina Kosten en financiering.
 */

echo kbj_kort( array( array( '3', 'manieren om zorg te betalen' ), array( 'Vaak', 'grotendeels vergoed' ), array( '€ 985', 'per maand voor een kamer, vanaf' ), array( 'Hulp', 'bij uw aanvraag' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'De regelingen',
		'Drie manieren om zorg te betalen',
		'Meestal loopt het via de WLZ of de WMO. Zonder indicatie kan het ook: dan betaalt u zelf.',
		false
	)
	. kbj_lijst( kbj_regelingen(), 'kbj-regelingen kbj-regelingen--drie' )
	. kbj_p( 'Heeft u een persoonsgebonden budget (PGB)? Ook dan bent u welkom. Bel ons, dan kijken we samen hoe het werkt.', 'kbj-noot' ),
	array( 'klasse' => 'kbj-reveal' )
);

$aanbod = kbj_kolommen(
	array(
		array(
			'inhoud' => kbj_kader(
				kbj_kop( 3, 'Wonen' )
				. kbj_p( 'Met een WLZ-indicatie. De kamer kost vanaf € 985 per maand, exclusief servicekosten en zorg. Voor de zorg betaalt u een eigen bijdrage aan het CAK.' )
				. kbj_p( '<a href="/wonen-met-zorg/">Meer over wonen</a>', 'kbj-verder' )
			),
		),
		array(
			'inhoud' => kbj_kader(
				kbj_kop( 3, 'Logeren' )
				. kbj_p( 'Via de WLZ, de WMO of particulier. Vaak wordt het grootste deel vergoed.' )
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

echo kbj_beeldband( 'schip.webp', 'Zorg bij KomBij wordt meestal vergoed. <span class="kbj-hl-licht">Wij helpen u met de aanvraag.</span>', 'kerk', '50% 60%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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
