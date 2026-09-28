<?php
/**
 * Title: Wonen, logeren en dagbesteding
 * Slug: kombij/aanbod
 * Categories: kombij
 * Description: De drie manieren om bij KomBij te zijn, elk met een foto in kerkvorm. Op de telefoon een strook om opzij te vegen.
 */

$kaart = function ( $bestand, $alt, $label, $titel, $tekst, $link, $linktekst ) {
	return kbj_foto( $bestand, $alt )
		. kbj_p( $label, 'kbj-label' )
		. kbj_kop( 3, $titel )
		. kbj_p( $tekst )
		. kbj_p( '<a href="' . esc_url( $link ) . '">' . esc_html( $linktekst ) . '</a>', 'kbj-verder' );
};

$kaarten = kbj_kolommen(
	array(
		array(
			'klasse' => 'kbj-kaart',
			'inhoud' => $kaart(
				'slaapkamer.webp',
				'Een lichte zit-slaapkamer met een glas-in-loodraam en een gewelfd plafond',
				'Met een WLZ-indicatie',
				'KomBij ons wonen',
				'Een eigen kamer in een kleine groep van zes bewoners. Met 24 uur per dag zorg, ook bij dementie of intensieve verpleging.',
				'/wonen-met-zorg/',
				'Meer over wonen'
			),
		),
		array(
			'klasse' => 'kbj-kaart',
			'inhoud' => $kaart(
				'zitplek.webp',
				'Een fauteuil en een ronde tafel met bloemen in een logeerkamer',
				'Vanaf 2 nachten',
				'KomBij ons logeren',
				'Uw naaste logeert een paar nachten bij ons, met dezelfde zorg als thuis. U heeft even tijd voor uzelf.',
				'/logeren-met-zorg/',
				'Meer over logeren'
			),
		),
		array(
			'klasse' => 'kbj-kaart',
			'inhoud' => $kaart(
				'dagbesteding.webp',
				'Gasten en een begeleider aan de lange tafel in de kerk',
				'Maandag tot en met vrijdag',
				'KomBij ons de dag doorbrengen',
				'Samen koffie drinken, bewegen, schilderen of de krant lezen. Met een warme lunch. U kiest zelf de dagen.',
				'/dagbesteding/',
				'Meer over dagbesteding'
			),
		),
	),
	'kbj-kaarten'
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Wat wij bieden',
		'Drie manieren om bij ons te zijn',
		'Zoekt u een nieuw thuis, een paar nachten logeren of gezelschap overdag? U komt in hetzelfde huis, bij hetzelfde team.'
	)
	. $kaarten,
	array( 'klasse' => 'kbj-aanbod kbj-reveal' )
);
