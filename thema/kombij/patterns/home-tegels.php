<?php
/**
 * Title: Startpagina, ontwerp tegels
 * Slug: kombij/home-tegels
 * Categories: kombij
 * Description: Een vrolijke startpagina van tegels in verschillende maten: alles wat u wilt weten in één oogopslag.
 */

$plek = function ( $soort ) {
	return '<!-- wp:' . kbj_blok_attrs( 'kbj/plek', array( 'soort' => $soort ) ) . " /-->\n";
};

$tegel = function ( $inhoud, $klasse ) {
	return kbj_groep( $inhoud, 'kbj-ht-tegel ' . $klasse );
};

// 1. De opening als raster van tegels.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		$tegel(
			'<!-- wp:kbj/illustratie {"naam":"raam","className":"kbj-ht-raam"} /-->' . "\n"
			. kbj_kop( 1, 'Een warm thuis, voor als thuis niet meer gaat.', 'kbj-schuif' )
			. kbj_p( 'Wonen, logeren en dagbesteding met zorg in Maasbommel. Klein, huiselijk en dichtbij.', 'kbj-intro' )
			. kbj_knoppen(
				array(
					array( 'tekst' => 'Plan een rondleiding', 'url' => '/contact/' ),
					kbj_tel_knop(),
				)
			),
			'kbj-ht-tegel--kop'
		)
		. $tegel( kbj_foto( 'samen-aan-tafel.webp', 'Gasten en medewerkers samen aan de lange tafel', array( 'vorm' => 'recht', 'verhouding' => '3/4', 'eerst' => true ) ), 'kbj-ht-tegel--foto kbj-ht-tegel--hoog' )
		. $tegel( kbj_p( '<strong>24 uur</strong> zorg, dag en nacht dichtbij' ), 'kbj-ht-tegel--feit' )
		. $tegel( kbj_p( '<strong>Vaak vergoed</strong> via WLZ, WMO of PGB' ) . kbj_p( '<a href="/kosten-en-financiering/">Zo werkt het</a>', 'kbj-ht-link' ), 'kbj-ht-tegel--groen' )
		. $tegel( kbj_foto( 'kerk-luchtfoto.webp', 'Het gebouw van KomBij in Maasbommel', array( 'vorm' => 'recht', 'verhouding' => '16/9' ) ), 'kbj-ht-tegel--foto kbj-ht-tegel--breed' )
		. $tegel( kbj_p( '<strong>Een monument</strong> uit 1869, met een hart van nu' ) . kbj_p( '<a href="/over-ons/">Ons verhaal</a>', 'kbj-ht-link' ), 'kbj-ht-tegel--donker' )
		. $tegel( kbj_p( '<strong>Dagbesteding</strong> maandag tot en met vrijdag, 10:30 tot 16:30' ) . kbj_p( '<a href="/dagbesteding/">Kom een keer meedraaien</a>', 'kbj-ht-link' ), 'kbj-ht-tegel--feit' ),
		'kbj-ht-raster kbj-ht-raster--open'
	),
	array( 'klasse' => 'kbj-ht kbj-ht-open', 'boven' => '30', 'onder' => '40' )
);

// 2. Het aanbod: drie tegels in drie kleuren.
$aanbod = function ( $kleur, $bestand, $alt, $kop, $tekst, $url, $knop, $soort, $tekening ) use ( $tegel, $plek ) {
	return $tegel(
		'<!-- wp:' . kbj_blok_attrs( 'kbj/illustratie', array( 'naam' => $tekening, 'className' => 'kbj-ht-tekening' ) ) . " /-->\n"
		. kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => '1/1' ) )
		. $plek( $soort )
		. kbj_kop( 3, $kop )
		. kbj_p( $tekst )
		. kbj_knoppen( array( array( 'tekst' => $knop, 'url' => $url ) ) ),
		'kbj-ht-aanbod kbj-ht-aanbod--' . $kleur
	);
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Wat zoekt u voor uw naaste?', 'kbj-schuif' )
	. kbj_groep(
		$aanbod( 'ijs', 'slaapkamer.webp', 'Een lichte kamer onder een gewelf', 'Een nieuw thuis', 'Een eigen kamer in een huis voor zes bewoners, met dag en nacht zorg.', '/wonen-met-zorg/', 'Wonen met zorg', 'wonen', 'gewelf' )
		. $aanbod( 'glas', 'zitplek.webp', 'Een fauteuil met bloemen op tafel', 'Even op adem komen', 'Uw naaste logeert een paar nachten bij ons. U rust uit, wij zorgen.', '/logeren-met-zorg/', 'Logeren met zorg', 'logeren', 'maas' )
		. $aanbod( 'nacht', 'dagbesteding.webp', 'Gasten en een begeleider aan tafel', 'Nooit meer de hele dag alleen', 'Koffie, bewegen, schilderen en samen lunchen. Maandag tot en met vrijdag.', '/dagbesteding/', 'Dagbesteding', 'dagbesteding', 'koffie' ),
		'kbj-ht-raster kbj-ht-raster--drie'
	),
	array( 'klasse' => 'kbj-ht kbj-ht-aanbodsectie', 'anker' => 'aanbod', 'boven' => '40', 'onder' => '40' )
);
?>
<!-- wp:pattern {"slug":"kombij/strook-dag"} /-->
<!-- wp:pattern {"slug":"kombij/strook-binnen"} /-->
<?php
// 3. De familie en de kosten in één raster.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		$tegel(
			'<!-- wp:kbj/illustratie {"naam":"toren","className":"kbj-ht-toren"} /-->' . "\n"
			. kbj_kop( 2, 'Een familie. Geen instelling.' )
			. kbj_p( 'Een moeder, haar dochter en haar schoonzoon uit de regio begonnen KomBij samen. Bewust klein gehouden: hier kent iedereen elkaar bij naam.' )
			. kbj_knoppen( array( array( 'tekst' => 'Maak kennis met KomBij', 'url' => '/over-ons/', 'rand' => true ) ) ),
			'kbj-ht-tegel--familie'
		)
		. $tegel( kbj_foto( 'koffie.webp', 'Een kopje koffie wordt aangereikt', array( 'vorm' => 'recht', 'verhouding' => '1/1' ) ), 'kbj-ht-tegel--foto' )
		. $tegel(
			kbj_kop( 2, 'Meestal vergoed.' )
			. kbj_p( 'Wij zoeken samen met u uit welke regeling voor u geldt, en helpen met de aanvraag.' )
			. kbj_knoppen( array( array( 'tekst' => 'Zo werkt de vergoeding', 'url' => '/kosten-en-financiering/' ) ) ),
			'kbj-ht-tegel--kosten'
		),
		'kbj-ht-raster kbj-ht-raster--familie'
	),
	array( 'klasse' => 'kbj-ht', 'boven' => '40', 'onder' => '40' )
);
?>
<!-- wp:kbj/vacaturestrook {"vorm":"sectie"} /-->
<!-- wp:pattern {"slug":"kombij/vragen"} /-->
<!-- wp:pattern {"slug":"kombij/slot"} /-->
