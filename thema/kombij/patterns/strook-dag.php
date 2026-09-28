<?php
/**
 * Title: Zo gaat een dag
 * Slug: kombij/strook-dag
 * Categories: kombij
 * Description: Een dag bij KomBij in vijf momenten, als een lijn van links naar rechts.
 */

$momenten = array(
	array( 'ochtend', 'Ochtend', 'Rustig ontbijten, in uw eigen tempo.' ),
	array( 'koffie', 'Koffietijd', 'Koffie, de krant en even bijpraten.' ),
	array( 'lunch', 'Lunch', 'Samen aan tafel, vaak met verse soep.' ),
	array( 'middag', 'Middag', 'Bewegen, schilderen of lekker niks.' ),
	array( 'avond', 'Avond', 'Een warme maaltijd en een rustige avond.' ),
);

$items = '';

foreach ( $momenten as $moment ) {
	$items .= '<li class="kbj-dagstrook__moment kbj-dagstrook__moment--' . esc_attr( $moment[0] ) . '"><strong>' . esc_html( $moment[1] ) . '</strong><span>' . esc_html( $moment[2] ) . '</span></li>';
}

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Zo gaat een dag bij KomBij', 'kbj-schuif' )
	. "<!-- wp:html -->\n<ol class=\"kbj-dagstrook\">" . $items . "</ol>\n<!-- /wp:html -->\n",
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-strook-dag',
		'boven'       => '50',
		'onder'       => '50',
	)
);
