<?php
/**
 * Title: Startpagina, ontwerp rustig
 * Slug: kombij/home-rustig
 * Categories: kombij
 * Description: Een rustige, witte startpagina met grote letters, dunne lijnen en veel ruimte.
 */

$plek = function ( $soort ) {
	return '<!-- wp:' . kbj_blok_attrs( 'kbj/plek', array( 'soort' => $soort ) ) . " /-->\n";
};

$rij = function ( $nummer, $kop, $tekst, $url, $knop, $soort, $bestand, $alt ) use ( $plek ) {
	return kbj_groep(
		kbj_p( $nummer, 'kbj-hr-rij__nr' )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. $plek( $soort ),
			'kbj-hr-rij__tekst'
		)
		. kbj_knoppen( array( array( 'tekst' => $knop, 'url' => $url, 'rand' => true ) ) )
		. kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => '4/3' ) ),
		'kbj-hr-rij'
	);
};

// 1. De opening: de kop groot, de foto er breed onder.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_kop( 1, 'Een warm thuis, voor als thuis niet meer gaat.', 'kbj-schuif' )
		. kbj_groep(
			kbj_p( 'Wonen, logeren en dagbesteding met zorg in een monumentaal gebouw in Maasbommel. Klein, huiselijk en dichtbij.', 'kbj-intro' )
			. kbj_knoppen(
				array(
					array( 'tekst' => 'Plan een rondleiding', 'url' => '/contact/' ),
					kbj_tel_knop(),
				)
			),
			'kbj-hr-open__zij'
		),
		'kbj-hr-open__kop'
	)
	. kbj_groep(
		kbj_foto( 'samen-aan-tafel.webp', 'Gasten en medewerkers samen aan de lange tafel bij KomBij', array( 'vorm' => 'recht', 'verhouding' => '21/9', 'positie' => '55% 55%', 'eerst' => true ) )
		. '<!-- wp:kbj/raamlijn {"className":"kbj-hr-open__raam"} /-->',
		'kbj-hr-open__beeld'
	)
	. kbj_lijst(
		array(
			'<strong>24 uur</strong><span>zorg, dag en nacht</span>',
			'<strong>6 plekken</strong><span>om te wonen</span>',
			'<strong>5 dagen</strong><span>dagbesteding per week</span>',
			'<strong>Vaak vergoed</strong><span>via WLZ, WMO of PGB</span>',
		),
		'kbj-hr-feiten'
	),
	array( 'klasse' => 'kbj-hr kbj-hr-open', 'boven' => '50', 'onder' => '50' )
);

// 2. Het aanbod als drie rijen, als de inhoud van een boek.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Wat zoekt u voor uw naaste?', 'kbj-schuif' )
	. $rij( '01', 'Een nieuw thuis', 'Een eigen kamer in een huis voor zes bewoners, met dag en nacht zorg dichtbij.', '/wonen-met-zorg/', 'Wonen met zorg', 'wonen', 'slaapkamer.webp', 'Een lichte kamer met een hoog glas-in-loodraam' )
	. $rij( '02', 'Even op adem komen', 'Uw naaste logeert een paar nachten bij ons. U rust uit, wij zorgen.', '/logeren-met-zorg/', 'Logeren met zorg', 'logeren', 'zitplek.webp', 'Een fauteuil en een tafeltje met bloemen' )
	. $rij( '03', 'Nooit meer de hele dag alleen', 'Koffie, bewegen, schilderen en samen lunchen. Maandag tot en met vrijdag.', '/dagbesteding/', 'Dagbesteding', 'dagbesteding', 'dagbesteding.webp', 'Gasten en een begeleider lachen samen aan tafel' ),
	array( 'klasse' => 'kbj-hr kbj-hr-aanbod', 'anker' => 'aanbod', 'boven' => '50', 'onder' => '50' )
);

// 3. De zin die oplicht.
echo '<!-- wp:pattern {"slug":"kombij/strook-zin"} /-->' . "\n";

// 4. Het gebouw: een brede foto, met de kop en de tekst naast elkaar eronder.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_foto( 'kerk-luchtfoto.webp', 'Het monumentale gebouw van KomBij in Maasbommel, van bovenaf', array( 'vorm' => 'recht', 'verhouding' => '21/9' ) )
	. kbj_groep(
		kbj_kop( 2, 'Gebouwd in 1869. Nu weer vol leven.', 'kbj-schuif' )
		. kbj_groep(
			kbj_p( 'Waar het dorp vroeger samenkwam, wonen, logeren en lachen mensen nu samen. De hoge ramen en de gewelven bleven. De warmte kwam erbij.' )
			. kbj_knoppen( array( array( 'tekst' => 'Ons verhaal', 'url' => '/over-ons/', 'rand' => true ) ) )
		),
		'kbj-hr-twee'
	),
	array( 'klasse' => 'kbj-hr kbj-hr-gebouw', 'boven' => '50', 'onder' => '50' )
);

// 5. De kosten: links de boodschap, rechts de regelingen als rustige lijst.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_groep(
			kbj_kop( 2, 'Goed nieuws: het wordt meestal vergoed.', 'kbj-schuif' )
			. kbj_p( 'Zorg bij KomBij betaalt u vaak niet zelf. Wij zoeken samen met u uit welke regeling voor u geldt, en helpen met de aanvraag.' )
			. kbj_knoppen( array( array( 'tekst' => 'Zo werkt de vergoeding', 'url' => '/kosten-en-financiering/' ) ) )
		)
		. kbj_lijst(
			array(
				'<strong>WLZ</strong><span>Wet langdurige zorg, voor wie blijvend zorg nodig heeft</span>',
				'<strong>WMO</strong><span>Via uw gemeente, voor wie nog thuis woont</span>',
				'<strong>PGB</strong><span>Uw eigen budget, u kiest zelf</span>',
				'<strong>Particulier</strong><span>Zonder indicatie kan het ook</span>',
			),
			'kbj-hr-regels'
		),
		'kbj-hr-twee'
	),
	array( 'klasse' => 'kbj-hr kbj-hr-kosten', 'boven' => '50', 'onder' => '50' )
);

// 6. De familie, gecentreerd, met de toren erachter.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<!-- wp:kbj/illustratie {"naam":"toren","className":"kbj-hr-toren"} /-->' . "\n"
	. kbj_kop( 2, 'Een familie. Geen instelling.', 'kbj-schuif', true )
	. kbj_p( 'Een moeder, haar dochter en haar schoonzoon uit de regio begonnen KomBij samen. Bewust klein gehouden: hier kent iedereen elkaar bij naam.', 'kbj-intro', true )
	. kbj_knoppen( array( array( 'tekst' => 'Maak kennis met KomBij', 'url' => '/over-ons/', 'rand' => true ) ), true ),
	array( 'klasse' => 'kbj-hr kbj-hr-familie', 'breed' => false, 'boven' => '60', 'onder' => '60' )
);
?>
<!-- wp:kbj/vacaturestrook {"vorm":"sectie"} /-->
<!-- wp:pattern {"slug":"kombij/vragen"} /-->
<!-- wp:pattern {"slug":"kombij/slot"} /-->
