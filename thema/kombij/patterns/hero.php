<?php
/**
 * Title: Opening van de startpagina
 * Slug: kombij/hero
 * Categories: kombij
 * Description: De belofte, de twee knoppen en een foto van het koor in de vorm van een kerkraam. Daaronder de kerncijfers.
 */

$tekst = kbj_p( 'Zorg vanuit ons hart', 'kbj-boven' )
	. kbj_kop( 1, 'Wonen, logeren en dagbesteding met zorg <span class="kbj-nadruk">in de oude kerk van Maasbommel</span>' )
	. kbj_p( 'Wordt het thuis steeds lastiger, of wordt zorgen voor uw naaste te zwaar? Bij KomBij bent u welkom. Kleinschalig en huiselijk, met 24 uur per dag zorg van mensen die u bij naam kennen.', 'kbj-intro' )
	. kbj_knoppen(
		array(
			array(
				'tekst' => 'Plan een rondleiding',
				'url'   => '/contact/',
			),
			array(
				'tekst' => 'Bel ' . kbj_tel_tekst(),
				'url'   => kbj_tel_url(),
				'rand'  => true,
			),
		)
	)
	. kbj_lijst(
		array(
			'WLZ, WMO, PGB of particulier',
			'De koffie staat altijd klaar',
			'Aan de Maas in West Maas en Waal',
		),
		'kbj-vinkjes'
	);

$beeld = kbj_foto(
	'altaar.webp',
	'Het koor van de Lambertuskerk, met hoge glas-in-loodramen en witte gewelven',
	array(
		'verhouding' => '3/4',
		'positie'    => '50% 35%',
		'eerst'      => true,
	)
);

$cijfers = kbj_lijst(
	array(
		'<strong>24 uur</strong><span>zorg en toezicht, elke dag</span>',
		'<strong>6 plekken</strong><span>om te wonen, met een WLZ-indicatie</span>',
		'<strong>5 dagen</strong><span>dagbesteding, van 10:30 tot 16:30</span>',
		'<strong>1869</strong><span>gebouwd als kerk, nu een warm thuis</span>',
	),
	'kbj-cijfers'
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => $tekst,
				'breedte' => '56%',
			),
			array(
				'inhoud'  => $beeld,
				'breedte' => '44%',
				'klasse'  => 'kbj-hero__beeld',
			),
		),
		'kbj-hero__binnen',
		true
	) . kbj_groep( $cijfers, 'kbj-hero__cijfers' ),
	array(
		'klasse'   => 'kbj-hero',
		'boven'    => '50',
		'onder'    => '40',
		'ornament' => true,
	)
);
