<?php
/**
 * Title: Startpagina, ontwerp levendig
 * Slug: kombij/home-levendig
 * Categories: kombij
 * Description: Een startpagina vol beeld: foto's met een zachte laag merkkleur erover, een collage en grote beeldvlakken.
 */

$plek = function ( $soort ) {
	return '<!-- wp:' . kbj_blok_attrs( 'kbj/plek', array( 'soort' => $soort ) ) . " /-->\n";
};

$foto = function ( $bestand, $alt, $klasse = '', $positie = '50% 50%', $verhouding = '4/5' ) {
	return kbj_groep( kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => $verhouding, 'positie' => $positie ) ), 'kbj-hl-foto ' . $klasse );
};

// 1. De opening: een grote foto met een laag kerkblauw, en twee kleine foto's die eroverheen zweven.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$foto( 'samen-aan-tafel.webp', 'Gasten en medewerkers samen aan de lange tafel bij KomBij', 'kbj-hl-tint kbj-hl-tint--nacht kbj-hl-open__achter', '55% 50%', '16/9' )
	. '<!-- wp:kbj/raamlijn {"className":"kbj-hl-open__raam"} /-->' . "\n"
	. kbj_groep(
		kbj_kop( 1, 'Een warm thuis, voor als thuis niet meer gaat.', 'kbj-schuif' )
		. kbj_p( 'Wonen, logeren en dagbesteding met zorg in een monumentaal gebouw in Maasbommel. Klein, huiselijk en dichtbij.', 'kbj-intro' )
		. kbj_knoppen(
			array(
				array( 'tekst' => 'Plan een rondleiding', 'url' => '/contact/' ),
				array( 'tekst' => 'Bekijk wat we doen', 'url' => '#aanbod', 'rand' => true ),
			)
		),
		'kbj-hl-open__tekst'
	)
	. kbj_groep(
		$foto( 'kerk-bloesem.webp', 'Het gebouw met een bloeiende boom ervoor', 'kbj-hl-zweef kbj-hl-zweef--een', '50% 60%', '1/1' )
		. $foto( 'slaapkamer.webp', 'Een kamer onder een bakstenen gewelf', 'kbj-hl-zweef kbj-hl-zweef--twee', '50% 50%', '3/4' ),
		'kbj-hl-open__zweef'
	),
	array( 'klasse' => 'kbj-hl kbj-hl-open', 'boven' => '30', 'onder' => '30' )
);

// 2. Het aanbod: drie hoge foto's, elk met een eigen kleur eroverheen.
$kaart = function ( $kleur, $bestand, $alt, $kop, $tekst, $url, $knop, $soort ) use ( $plek, $foto ) {
	return kbj_groep(
		$foto( $bestand, $alt, 'kbj-hl-tint kbj-hl-tint--' . $kleur, '50% 50%', '3/4' )
		. $plek( $soort )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. kbj_knoppen( array( array( 'tekst' => $knop, 'url' => $url ) ) ),
			'kbj-hl-kaart__tekst'
		),
		'kbj-hl-kaart'
	);
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Wat zoekt u voor uw naaste?', 'kbj-schuif', true )
	. kbj_p( 'Drie manieren om bij ons te zijn. Hetzelfde huis, hetzelfde vertrouwde team.', 'kbj-intro', true )
	. kbj_groep(
		$kaart( 'kerk', 'slaapkamer-raam.webp', 'Een hoog glas-in-loodraam met gordijnen', 'Een nieuw thuis', 'Een eigen kamer, met dag en nacht zorg dichtbij.', '/wonen-met-zorg/', 'Wonen met zorg', 'wonen' )
		. $kaart( 'zee', 'zitplek.webp', 'Een fauteuil met bloemen op tafel', 'Even op adem komen', 'Uw naaste logeert een paar nachten bij ons. U rust uit, wij zorgen.', '/logeren-met-zorg/', 'Logeren met zorg', 'logeren' )
		. $kaart( 'helder', 'dagbesteding.webp', 'Gasten en een begeleider lachen aan tafel', 'Nooit meer de hele dag alleen', 'Koffie, bewegen en samen lunchen. Maandag tot en met vrijdag.', '/dagbesteding/', 'Dagbesteding', 'dagbesteding' ),
		'kbj-hl-kaarten'
	),
	array( 'klasse' => 'kbj-hl kbj-hl-aanbod', 'anker' => 'aanbod', 'boven' => '60', 'onder' => '50' )
);

// 3. De zin, groot over een foto met een laag nachtblauw.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$foto( 'kerk-interieur.webp', '', 'kbj-hl-tint kbj-hl-tint--nacht kbj-hl-band__beeld', '50% 45%', '21/9' )
	. kbj_p( 'Een eigen kamer. Vaste gezichten. Koffie als u wakker bent. En <span class="kbj-hl-licht">altijd iemand dichtbij.</span>', 'kbj-hl-band__zin' ),
	array( 'klasse' => 'kbj-hl kbj-hl-band', 'boven' => '30', 'onder' => '30' )
);

// 4. Een collage: zo ziet het er binnen uit.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_groep(
			kbj_kop( 2, 'Gebouwd in 1869. Nu weer vol leven.', 'kbj-schuif' )
			. kbj_p( 'Waar het dorp vroeger samenkwam, wonen, logeren en lachen mensen nu samen. De hoge ramen en de gewelven bleven. De warmte kwam erbij.' )
			. kbj_knoppen( array( array( 'tekst' => 'Ons verhaal', 'url' => '/over-ons/', 'rand' => true ) ) ),
			'kbj-hl-collage__tekst'
		)
		. $foto( 'kerk-luchtfoto.webp', 'Het gebouw van KomBij in Maasbommel, van bovenaf', 'kbj-hl-c1', '50% 35%', '4/3' )
		. $foto( 'kerk-silhouet.webp', 'Het gebouw in de ochtendzon, gezien over de velden', 'kbj-hl-c2 kbj-hl-tint kbj-hl-tint--glas', '55% 50%', '3/4' )
		. $foto( 'zoutkamer.webp', 'De zoutkamer met ligstoelen', 'kbj-hl-c3', '50% 50%', '1/1' )
		. $foto( 'schip.webp', 'Het lichte schip met de tussenverdieping', 'kbj-hl-c4', '50% 50%', '4/3' )
		. kbj_lijst(
			array(
				'<strong>24/7</strong><span>zorg, dag en nacht</span>',
				'<strong>6</strong><span>plekken om te wonen</span>',
			),
			'kbj-hl-cijfers'
		),
		'kbj-hl-collage'
	),
	array( 'klasse' => 'kbj-hl kbj-hl-gebouw', 'boven' => '60', 'onder' => '60' )
);

// 5. Vergoed, op een foto met een laag zeegroen.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$foto( 'maas-avond.webp', '', 'kbj-hl-tint kbj-hl-tint--kerk kbj-hl-vlak__beeld', '50% 50%', '21/9' )
	. kbj_groep(
		kbj_kop( 2, 'Goed nieuws: het wordt meestal vergoed.', 'kbj-schuif' )
		. kbj_p( 'Via de WLZ, de WMO of een PGB. Wij zoeken samen met u uit welke regeling voor u geldt, en helpen met de aanvraag.' )
		. kbj_knoppen( array( array( 'tekst' => 'Zo werkt de vergoeding', 'url' => '/kosten-en-financiering/' ) ) ),
		'kbj-hl-vlak__tekst'
	),
	array( 'klasse' => 'kbj-hl kbj-hl-vlak', 'boven' => '30', 'onder' => '30' )
);

// 6. De familie: twee foto's die elkaar overlappen, de tekst ernaast in een kaart.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_groep(
			$foto( 'koffie.webp', 'Een kopje koffie wordt met een glimlach aangereikt', 'kbj-hl-f1', '50% 45%', '4/5' )
			. $foto( 'kerk-groen.webp', 'Het gebouw tussen het groen', 'kbj-hl-f2 kbj-hl-tint kbj-hl-tint--zee', '50% 50%', '1/1' ),
			'kbj-hl-familie__beelden'
		)
		. kbj_groep(
			kbj_kop( 2, 'Een familie. Geen instelling.', 'kbj-schuif' )
			. kbj_p( 'Een moeder, haar dochter en haar schoonzoon uit de regio begonnen KomBij samen. Bewust klein gehouden: hier kent iedereen elkaar bij naam.' )
			. kbj_knoppen( array( array( 'tekst' => 'Maak kennis met KomBij', 'url' => '/over-ons/' ) ) ),
			'kbj-hl-familie__tekst'
		),
		'kbj-hl-familie'
	),
	array( 'klasse' => 'kbj-hl kbj-hl-familiesectie', 'boven' => '60', 'onder' => '60' )
);
?>
<!-- wp:kbj/vacaturestrook {"vorm":"sectie"} /-->
<!-- wp:pattern {"slug":"kombij/vragen"} /-->
<!-- wp:pattern {"slug":"kombij/slot"} /-->
