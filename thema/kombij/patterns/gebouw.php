<?php
/**
 * Title: Het gebouw
 * Slug: kombij/gebouw
 * Categories: kombij
 * Description: Het monumentale gebouw op een nachtblauw vlak, met de lijntekening van het raam over de foto.
 */

$tekst = kbj_kop( 2, 'Gebouwd in 1869. Nu weer vol leven.', 'kbj-schuif' )
	. kbj_p( 'Waar het dorp vroeger samenkwam, wonen, logeren en lachen mensen nu samen. De hoge ramen en de gewelven bleven. De warmte kwam erbij.' )
	. kbj_lijst(
		array(
			'<strong>1869</strong>gebouwd',
			'<strong>6</strong>woonplekken',
			'<strong>24/7</strong>zorg dichtbij',
		),
		'kbj-gebouw__feiten'
	)
	. kbj_p( '<a href="/over-ons/">Ons verhaal</a>', 'kbj-verder' );

$beeld = kbj_groep(
	kbj_foto( 'kerk-interieur.webp', 'De lichte, witte gewelven van het gebouw met hoge ramen', array( 'vorm' => 'recht', 'positie' => '62% 50%' ) )
	. '<!-- wp:kbj/raamlijn /-->',
	'kbj-gebouw__beeld'
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep( kbj_groep( $tekst, 'kbj-gebouw__tekst' ) . $beeld, 'kbj-gebouw__binnen' ),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-gebouw kbj-lood',
	)
);
