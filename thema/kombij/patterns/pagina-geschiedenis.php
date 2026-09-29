<?php
/**
 * Title: Pagina geschiedenis
 * Slug: kombij/pagina-geschiedenis
 * Categories: kombij
 * Description: De geschiedenis van het gebouw als tijdlijn, met een album vol foto's.
 */

echo kbj_kort( array( array( '1869', 'gebouwd aan de Raadhuisdijk' ), array( 'Van Dijk', 'Cornelis van Dijk, de architect' ), array( '2001', 'een beschermd rijksmonument' ), array( '2026', 'KomBij opent de deuren' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

/*
 * De feiten komen van de Rijksdienst voor het Cultureel Erfgoed (monument
 * 523092), Mijn Gelderland en De Maas en Waler. Een jaartal dat we niet zeker
 * weten, staat er niet bij.
 */
$moment = function ( $jaar, $kop, $tekst, $bestand = '', $alt = '', $bron = '' ) {
	$opties = array( 'vorm' => 'recht', 'verhouding' => '16/10' );

	if ( '' !== $bron ) {
		$opties['bijschrift'] = $bron;
	}

	return kbj_groep(
		kbj_p( $jaar, 'kbj-tijd__jaar' )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. ( '' !== $bestand ? kbj_foto( $bestand, $alt, $opties ) : '' ),
			'kbj-tijd__inhoud'
		),
		'kbj-tijd'
	);
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Tijdlijn', 'Ruim 150 jaar in een paar stappen', '', true )
	. kbj_groep(
		$moment( '13e eeuw', 'Een plek om samen te komen', 'De oudste vermelding van een kerk in Maasbommel komt uit de tweede helft van de dertiende eeuw.' )
		. $moment( 'Rond 1750', 'Zo zag het oude gebouw eruit', 'Een tekenaar legde het middeleeuwse gebouw vast in zijn schetsboek, met de toren aan de voorkant en het hoge koor erachter.', 'historie-tekening-1750.webp', 'Een potloodtekening van het middeleeuwse kerkgebouw van Maasbommel', 'Tekening uit een schetsboek, tussen 1701 en 1759. Rijksmuseum Amsterdam, publiek domein.' )
		. $moment( '1812', 'Het oude gebouw verdwijnt', 'Het middeleeuwse gebouw is zo bouwvallig dat het wordt gesloopt. Er is niets meer van te zien.' )
		. $moment( '1868', 'De bouw begint', 'Aan de Raadhuisdijk verrijst een nieuw gebouw, naar een ontwerp van Cornelis van Dijk. Het is zijn eerste ontwerp als zelfstandig architect.' )
		. $moment( '1869', 'Een neogotische basiliek met toren', 'Hoge ramen van gebrandschilderd glas, gewelven en pilaren. Ruim 150 jaar is dit het hart van het dorp.', 'historie-1978-velden.webp', 'Zwart-witfoto van het gebouw met de hoge toren, gezien over de weilanden', 'Het gebouw in 1978. Foto A. J. van der Wal, Rijksdienst voor het Cultureel Erfgoed, CC BY-SA 4.0.' )
		. $moment( '1978', 'Vastgelegd voor een boek', 'Een fotograaf van de Rijksdienst fotografeert het gebouw voor een boek over het Land van Maas en Waal. Zo staat het er dan al ruim honderd jaar.', 'historie-1978-dijk.webp', 'Zwart-witfoto van de dijk met een grote boom en het gebouw met de toren', 'Gezien vanaf de dijk, 1978. Foto A. J. van der Wal, Rijksdienst voor het Cultureel Erfgoed, CC BY-SA 4.0.' )
		. $moment( '2001', 'Een rijksmonument', 'Op 17 december 2001 wordt het gebouw een rijksmonument. Sindsdien is het beschermd.' )
		. $moment( '2023', 'Op zoek naar een nieuwe bestemming', 'Het gebouw is niet langer in gebruik als kerk. Het komt leeg te staan, en de vraag is wat ermee gebeurt.' )
		. $moment( 'De verbouwing', 'Een familie ziet er een thuis in', 'Corrie Roelofsen, haar dochter Chantal en schoonzoon Edwin beginnen KomBij. Edwin neemt de verbouwing op zich. In de zijbeuken komt een tussenverdieping met lichte kamers, en er komen twee zoutkamers met wanden van zoutsteen.', 'schip.webp', 'Het schip tijdens de verbouwing, met de nieuwe tussenverdieping', 'Tijdens de verbouwing.' )
		. $moment( '29 nov. 2025', 'De officiële opening', 'Wethouder Marieke van den Boom knipt het lint door. KomBij is officieel geopend.' )
		. $moment( '2026', 'Weer vol leven', 'Vanaf januari wonen, logeren en komen er mensen voor de dagbesteding. Het dorp komt er weer samen.', 'samen-aan-tafel.webp', 'Gasten en medewerkers samen aan de lange tafel' ),
		'kbj-tijdlijn'
	),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-tijdlijn-sectie',
	)
);

echo kbj_beeldband( 'maas-avond.webp', 'Waar het dorp altijd samenkwam, <span class="kbj-hl-licht">is het nu weer thuis.</span>', 'kerk', '50% 50%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$vroeger = array(
	array( 'historie-tekening-1750.webp', 'Potloodtekening van het middeleeuwse gebouw, rond 1750' ),
	array( 'historie-1978-dijk.webp', 'Het gebouw gezien vanaf de dijk, 1978' ),
	array( 'historie-1978-boom.webp', 'De dijk met de grote boom en het gebouw, 1978' ),
	array( 'historie-1978-velden.webp', 'Het gebouw over de weilanden gezien, 1978' ),
	array( 'historie-1978-dorp.webp', 'Een straat in Maasbommel met de toren in de verte, 1978' ),
);

$nu = array(
	array( 'kerk-luchtfoto.webp', 'Het gebouw van bovenaf, met de Maas op de achtergrond' ),
	array( 'kerk-silhouet.webp', 'Het gebouw in de ochtendzon, over de velden gezien' ),
	array( 'kerk-bloesem.webp', 'De toren met een bloeiende boom ervoor' ),
	array( 'kerk-interieur.webp', 'De gewelven en hoge ramen, na de verbouwing' ),
	array( 'schip.webp', 'Het schip met de nieuwe tussenverdieping' ),
	array( 'slaapkamer.webp', 'Een kamer onder een bakstenen gewelf' ),
);

$album = function ( $fotos ) {
	$html = '';

	foreach ( $fotos as $foto ) {
		$html .= kbj_foto( $foto[0], $foto[1], array( 'vorm' => 'recht', 'verhouding' => '4/3' ) );
	}

	return kbj_groep( $html, 'kbj-album' );
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Album', 'Vroeger en nu', 'Klik op een foto om hem groot te bekijken.', true )
	. kbj_kop( 3, 'Vroeger', 'kbj-album__kop' )
	. $album( $vroeger )
	. kbj_kop( 3, 'Nu', 'kbj-album__kop' )
	. $album( $nu ),
	array( 'klasse' => 'kbj-album-sectie' )
);

// Waar de feiten en de oude beelden vandaan komen.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 3, 'Bronnen' )
	. kbj_lijst(
		array(
			'Feiten: <a href="https://kennis.cultureelerfgoed.nl/index.php/Monumenten/523092" rel="noopener">Rijksdienst voor het Cultureel Erfgoed, monument 523092</a>, <a href="https://mijngelderland.nl/inhoud/routes/cultuurhistorie-in-het-land-van-maas-en-waal/h-lambertus-kerk-maasbommel" rel="noopener">Mijn Gelderland</a> en <a href="https://www.demaasenwaler.nl/zaken/zaken/59760/officiele-opening-kombij-maasbommel" rel="noopener">De Maas en Waler</a>.',
			'Tekening rond 1750: <a href="https://commons.wikimedia.org/wiki/File:Kerk_van_Maasbommel,_RP-T-1918-230A-7(R).jpg" rel="noopener">Rijksmuseum Amsterdam, RP-T-1918-230A-7</a>, publiek domein (CC0).',
			'Foto’s uit 1978: A. J. van der Wal, <a href="https://commons.wikimedia.org/wiki/File:Zicht_op_de_kerk_-_Maasbommel_-_20428100_-_RCE.jpg" rel="noopener">Rijksdienst voor het Cultureel Erfgoed</a> (objectnummers 20428100, 20428101, 20428107 en 20428062), onder licentie <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.nl" rel="noopener">CC BY-SA 4.0</a>. Verkleind voor deze website.',
		),
		'kbj-bronnen'
	),
	array( 'klasse' => 'kbj-bronnen-sectie', 'breed' => false )
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
