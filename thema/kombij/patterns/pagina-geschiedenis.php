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
$moment = function ( $jaar, $kop, $tekst, $bestand = '', $alt = '' ) {
	return kbj_groep(
		kbj_p( $jaar, 'kbj-tijd__jaar' )
		. kbj_groep(
			kbj_kop( 3, $kop )
			. kbj_p( $tekst )
			. ( '' !== $bestand ? kbj_foto( $bestand, $alt, array( 'vorm' => 'recht', 'verhouding' => '16/10' ) ) : '' ),
			'kbj-tijd__inhoud'
		),
		'kbj-tijd'
	);
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Tijdlijn', 'Ruim 150 jaar in een paar stappen', '', true )
	. kbj_groep(
		$moment( '13e eeuw', 'Een plek om samen te komen', 'De oudste vermelding van een kerk in Maasbommel komt uit de tweede helft van de dertiende eeuw.' )
		. $moment( '1812', 'Het oude gebouw verdwijnt', 'Het middeleeuwse gebouw is zo bouwvallig dat het wordt gesloopt. Er is niets meer van te zien.' )
		. $moment( '1868', 'De bouw begint', 'Aan de Raadhuisdijk verrijst een nieuw gebouw, naar een ontwerp van Cornelis van Dijk. Het is zijn eerste ontwerp als zelfstandig architect.', 'kerk-bloesem.webp', 'Het gebouw met de hoge toren, met een bloeiende boom ervoor' )
		. $moment( '1869', 'Een neogotische basiliek met toren', 'Hoge ramen van gebrandschilderd glas, gewelven en pilaren. Ruim 150 jaar is dit het hart van het dorp.', 'kerk-interieur.webp', 'De lichte gewelven en hoge ramen binnen' )
		. $moment( '2001', 'Een rijksmonument', 'Op 17 december 2001 wordt het gebouw een rijksmonument. Sindsdien is het beschermd.', 'kerk-luchtfoto.webp', 'Het gebouw van bovenaf, midden in Maasbommel' )
		. $moment( '2023', 'Op zoek naar een nieuwe bestemming', 'Het gebouw is niet langer in gebruik als kerk. Het komt leeg te staan, en de vraag is wat ermee gebeurt.' )
		. $moment( 'De verbouwing', 'Een familie ziet er een thuis in', 'Corrie Roelofsen, haar dochter Chantal en schoonzoon Edwin beginnen KomBij. Edwin neemt de verbouwing op zich. In de zijbeuken komt een tussenverdieping met lichte kamers, en er komen twee zoutkamers met wanden van zoutsteen.', 'schip.webp', 'Het lichte schip met de nieuwe tussenverdieping' )
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

$album = array(
	array( 'kerk-luchtfoto.webp', 'Het gebouw van bovenaf, met de Maas op de achtergrond' ),
	array( 'kerk-silhouet.webp', 'Het gebouw in de ochtendzon, over de velden gezien' ),
	array( 'kerk-groen.webp', 'De toren tussen de bomen' ),
	array( 'kerk-bloesem.webp', 'De toren met een bloeiende boom ervoor' ),
	array( 'kerk-interieur.webp', 'De gewelven en hoge ramen binnen' ),
	array( 'schip.webp', 'Het schip met de nieuwe tussenverdieping' ),
	array( 'slaapkamer-raam.webp', 'Een glas-in-loodraam in een van de kamers' ),
	array( 'slaapkamer.webp', 'Een kamer onder een bakstenen gewelf' ),
	array( 'zoutkamer.webp', 'Een zoutkamer met wanden van zoutsteen' ),
);

$fotos = '';

foreach ( $album as $foto ) {
	$fotos .= kbj_foto( $foto[0], $foto[1], array( 'vorm' => 'recht', 'verhouding' => '4/3' ) );
}

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Album', 'Het gebouw in beeld', 'Klik op een foto om hem groot te bekijken.', true )
	. kbj_groep( $fotos, 'kbj-album' ),
	array( 'klasse' => 'kbj-album-sectie' )
);
?>
<!-- wp:pattern {"slug":"kombij/slot"} /-->
