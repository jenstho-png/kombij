<?php
/**
 * Title: Slotsectie
 * Slug: kombij/slot
 * Categories: kombij
 * Description: De laatste stap onderaan elke pagina: bellen of een bericht sturen.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_groep(
		kbj_kop( 2, 'KomBij ons langs', '', true )
		. kbj_p( 'De koffie staat klaar. Bel of mail ons voor een rondleiding, of stel gewoon uw vraag. U spreekt meteen iemand die u verder helpt.', 'kbj-intro', true )
		. kbj_knoppen(
			array(
				array(
					'tekst' => 'Bel ' . kbj_tel_tekst(),
					'url'   => kbj_tel_url(),
				),
				array(
					'tekst' => 'Stuur een bericht',
					'url'   => '/contact/',
					'rand'  => true,
				),
			),
			true
		)
		. kbj_p( 'Of mail naar ' . kbj_mail_link(), 'kbj-klein', true ),
		'kbj-slot__binnen'
	),
	array(
		'achtergrond' => 'glasblauw',
		'klasse'      => 'kbj-slot kbj-lood',
		'breed'       => false,
		'ornament'    => true,
	)
);
