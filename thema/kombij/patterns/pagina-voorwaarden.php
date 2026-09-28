<?php
/**
 * Title: Pagina voorwaarden
 * Slug: kombij/pagina-voorwaarden
 * Categories: kombij
 * Description: De voorwaarden, de huisregels en het privacyreglement, als pdf.
 */

$pdf = function ( $bestand ) {
	return esc_url( KBJ_URI . '/assets/documenten/' . $bestand );
};

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_lijst(
		array(
			'<strong>Voorwaarden logeren</strong><span>Wat u van ons mag verwachten, en wij van u. <a href="' . $pdf( 'algemene-voorwaarden-logeren.pdf' ) . '">Download (pdf)</a></span>',
			'<strong>Huisregels</strong><span>Zo houden we het samen prettig in huis. <a href="' . $pdf( 'huisregels.pdf' ) . '">Download (pdf)</a></span>',
			'<strong>Privacyreglement</strong><span>Hoe we met uw gegevens omgaan. <a href="' . $pdf( 'privacyreglement.pdf' ) . '">Download (pdf)</a></span>',
		),
		'kbj-regelingen kbj-regelingen--vrij kbj-mensen'
	)
	. kbj_p( 'Heeft u een vraag over een van deze documenten? Mail naar ' . kbj_mail_link() . ' of bel ' . esc_html( kbj_tel_tekst() ) . '.' ),
	array( 'boven' => '50' )
);
