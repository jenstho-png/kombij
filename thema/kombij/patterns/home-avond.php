<?php
/**
 * Title: Startpagina, ontwerp avondlicht
 * Slug: kombij/home-avond
 * Categories: kombij
 * Description: Een donkere, filmische startpagina: het raam als lichtbron in het midden, foto's als een filmstrook.
 */

$plek = function ( $soort ) {
	return '<!-- wp:' . kbj_blok_attrs( 'kbj/plek', array( 'soort' => $soort ) ) . " /-->\n";
};

$kaart = function ( $bestand, $alt, $kop, $tekst, $url, $soort ) use ( $plek ) {
	return kbj_groep(
		kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => '3/4' ) )
		. $plek( $soort )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. kbj_knoppen( array( array( 'tekst' => 'Meer over ' . strtolower( $kop ), 'url' => $url ) ) ),
			'kbj-ha-kaart__tekst'
		),
		'kbj-ha-kaart'
	);
};

// 1. De opening: donker, met het raam als lichtbron in het midden.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep( '<!-- wp:kbj/raamlijn {"className":"kbj-ha-raam"} /-->', 'kbj-ha-raamvlak' )
	. kbj_kop( 1, 'Een warm thuis in een monument aan de Maas.', 'kbj-schuif', true )
	. kbj_p( 'Wonen, logeren en dagbesteding met zorg in Maasbommel. Klein, huiselijk en dichtbij.', 'kbj-intro', true )
	. kbj_knoppen(
		array(
			array( 'tekst' => 'Plan een rondleiding', 'url' => '/contact/' ),
			array( 'tekst' => 'Bekijk wat we doen', 'url' => '#aanbod', 'rand' => true ),
		),
		true
	)
	. kbj_groep(
		kbj_foto( 'slaapkamer.webp', 'Een kamer onder een bakstenen gewelf', array( 'vorm' => 'recht', 'verhouding' => '4/5' ) )
		. kbj_foto( 'samen-aan-tafel.webp', 'Gasten samen aan de lange tafel', array( 'vorm' => 'recht', 'verhouding' => '4/5', 'eerst' => true ) )
		. kbj_foto( 'kerk-interieur.webp', 'De lichte gewelven van het gebouw', array( 'vorm' => 'recht', 'verhouding' => '4/5' ) ),
		'kbj-ha-waaier'
	),
	array( 'achtergrond' => 'nachtblauw', 'klasse' => 'kbj-ha kbj-ha-open', 'breed' => true, 'boven' => '70', 'onder' => '60' )
);

// 2. Het aanbod: drie hoge foto's met de tekst eroverheen.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Wat zoekt u voor uw naaste?', 'kbj-schuif', true )
	. kbj_groep(
		$kaart( 'slaapkamer-raam.webp', 'Een raam met glas-in-lood en gordijnen', 'Wonen', 'Een eigen kamer, dag en nacht zorg dichtbij.', '/wonen-met-zorg/', 'wonen' )
		. $kaart( 'zitplek.webp', 'Een fauteuil met bloemen op tafel', 'Logeren', 'Een paar nachten bij ons. U rust uit, wij zorgen.', '/logeren-met-zorg/', 'logeren' )
		. $kaart( 'dagbesteding.webp', 'Gasten en een begeleider aan tafel', 'Dagbesteding', 'Nooit meer de hele dag alleen. Maandag tot en met vrijdag.', '/dagbesteding/', 'dagbesteding' ),
		'kbj-ha-kaarten'
	),
	array( 'achtergrond' => 'nachtblauw', 'klasse' => 'kbj-ha kbj-ha-aanbod', 'anker' => 'aanbod', 'boven' => '40', 'onder' => '60' )
);

// 3. Drie grote getallen die oplichten.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_lijst(
		array(
			'<strong>1869</strong><span>gebouwd in het hart van het dorp</span>',
			'<strong>24/7</strong><span>zorg, dag en nacht dichtbij</span>',
			'<strong>6</strong><span>bewoners, dus iedereen kent elkaar</span>',
		),
		'kbj-ha-getallen'
	),
	array( 'achtergrond' => 'nachtblauw', 'klasse' => 'kbj-ha kbj-ha-cijfers', 'boven' => '50', 'onder' => '60' )
);

// 4. Het gebouw als verhaal: een foto die groot blijft staan, drie korte hoofdstukken ernaast.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_groep(
			kbj_foto( 'kerk-interieur.webp', 'De witte gewelven en hoge ramen van het gebouw', array( 'vorm' => 'recht', 'verhouding' => '4/5', 'positie' => '62% 50%' ) )
			. '<!-- wp:kbj/raamlijn /-->',
			'kbj-ha-verhaal__beeld'
		)
		. kbj_groep(
			kbj_kop( 2, 'Gebouwd in 1869. Nu weer vol leven.', 'kbj-schuif' )
			. kbj_groep( kbj_kop( 3, 'Het dorp kwam hier samen' ) . kbj_p( 'Ruim 150 jaar was dit het hart van Maasbommel. Het gebouw is een rijksmonument.' ), 'kbj-ha-hoofdstuk' )
			. kbj_groep( kbj_kop( 3, 'Met eigen handen verbouwd' ) . kbj_p( 'Een familie uit de regio gaf het een nieuwe bestemming. De ramen, de gewelven en de pilaren bleven.' ), 'kbj-ha-hoofdstuk' )
			. kbj_groep( kbj_kop( 3, 'Nu een huis vol leven' ) . kbj_p( 'Er wordt weer gegeten, gelachen en samen koffie gedronken. Iedere dag.' ), 'kbj-ha-hoofdstuk' )
			. kbj_knoppen( array( array( 'tekst' => 'Ons verhaal', 'url' => '/over-ons/', 'rand' => true ) ) ),
			'kbj-ha-verhaal__tekst'
		),
		'kbj-ha-verhaal'
	),
	array( 'achtergrond' => 'nachtblauw', 'klasse' => 'kbj-ha kbj-ha-gebouw', 'boven' => '50', 'onder' => '70' )
);
?>
<!-- wp:pattern {"slug":"kombij/vergoed"} /-->
<!-- wp:kbj/vacaturestrook {"vorm":"sectie"} /-->
<!-- wp:pattern {"slug":"kombij/vragen"} /-->
<!-- wp:pattern {"slug":"kombij/slot"} /-->
