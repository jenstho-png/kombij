<?php
/**
 * Title: Contact
 * Slug: kombij/contact
 * Categories: kombij
 * Description: Het formulier met de gegevens, de openingstijden en de route ernaast.
 */

$naast = kbj_kop( 2, 'Liever direct?' )
	. '<!-- wp:kbj/contactgegevens /-->' . "\n"
	. kbj_kop( 3, 'Openingstijden' )
	. kbj_lijst(
		array(
			'<span>Wonen en logeren</span><span>24 uur per dag zorg</span>',
			'<span>Dagbesteding</span><span>ma t/m vr, 10:30 tot 16:30</span>',
			'<span>Rondleiding</span><span>op afspraak</span>',
		),
		'kbj-tijden'
	)
	. kbj_p( '<a href="https://www.google.com/maps/dir/?api=1&amp;destination=Raadhuisdijk+44+6627+AD+Maasbommel" rel="noopener">Route plannen</a>', 'kbj-verder' );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => kbj_kop( 2, 'Stuur een bericht' )
					. kbj_p( 'Vertel kort waar u aan denkt. We nemen zo snel mogelijk contact met u op.' )
					. '<!-- wp:kbj/contactformulier /-->' . "\n",
				'breedte' => '58%',
			),
			array(
				'inhoud'  => $naast,
				'breedte' => '42%',
				'klasse'  => 'kbj-contact__naast',
			),
		),
		'kbj-contact__kolommen'
	),
	array(
		'klasse' => 'kbj-contact',
		'boven'  => '50',
		'anker'  => 'openingstijden',
	)
);
