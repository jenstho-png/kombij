<?php
/**
 * Title: Slotsectie
 * Slug: kombij/slot
 * Categories: kombij
 * Description: De laatste stap onderaan elke pagina: een rondleiding plannen of bellen.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<!-- wp:kbj/illustratie {"naam":"koffie","className":"kbj-tekening-slot"} /-->' . "\n"
	. kbj_groep(
		kbj_kop( 2, 'KomBij ons langs. De koffie staat klaar.', 'kbj-schuif', true )
		. kbj_p( 'Zien is ervaren. Plan een rondleiding en voel zelf hoe het bij ons is.', 'kbj-intro', true )
		. kbj_knoppen(
			array(
				array(
					'tekst' => 'Plan een rondleiding',
					'url'   => '/contact/',
				),
				kbj_tel_knop(),
			),
			true
		)
		. kbj_p( 'Liever eerst even bellen? Dat kan altijd: <a href="' . esc_attr( kbj_tel_url() ) . '">' . esc_html( kbj_tel_tekst() ) . '</a>. U krijgt meteen iemand aan de lijn.', 'kbj-bellen', true ),
		'kbj-slot__binnen'
	),
	array(
		'achtergrond' => 'glasblauw',
		'klasse'      => 'kbj-slot kbj-lood',
		'breed'       => false,
	)
);
