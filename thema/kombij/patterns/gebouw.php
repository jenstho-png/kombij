<?php
/**
 * Title: Het gebouw
 * Slug: kombij/gebouw
 * Categories: kombij
 * Description: Het monumentale gebouw op een nachtblauw vlak: een grote, brede foto met de tekst eronder.
 */

$kop   = kbj_kop( 2, 'Gebouwd in 1869. Nu weer vol leven.', 'kbj-schuif' );
$tekst = kbj_p( 'Waar het dorp vroeger samenkwam, wonen, logeren en lachen mensen nu samen. De hoge ramen en de gewelven bleven. De warmte kwam erbij.' )
	. kbj_lijst(
		array(
			'<strong>1869</strong>gebouwd',
			'<strong>6</strong>woonplekken',
			'<strong>24/7</strong>zorg dichtbij',
		),
		'kbj-gebouw__feiten'
	)
	. kbj_p( '<a href="/over-ons/">Ons verhaal</a>', 'kbj-verder' );

// De foto groot en breed bovenaan, de tekst eronder in twee kolommen.
$beeld = kbj_foto( 'kerk-luchtfoto.webp', 'Het monumentale gebouw van KomBij in Maasbommel, van bovenaf gezien', array( 'vorm' => 'recht', 'verhouding' => '21/9', 'positie' => '50% 30%' ) );

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$beeld . kbj_groep( $kop . kbj_groep( $tekst, 'kbj-gebouw__tekst' ), 'kbj-gebouw__binnen kbj-gebouw__binnen--breed' ),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-gebouw kbj-lood',
	)
);
