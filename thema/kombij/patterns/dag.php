<?php
/**
 * Title: Een dag bij KomBij
 * Slug: kombij/dag
 * Categories: kombij
 * Description: Vijf momenten van de dag op een lijn, zoals het lood tussen het glas.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Een dag bij KomBij',
		'Rust, regelmaat en gezelligheid',
		'Iedere dag heeft een vaste vorm. Daarbinnen is er ruimte voor wat u zelf graag doet.'
	)
	. kbj_lijst(
		array(
			'<strong>Ontbijt</strong><span>De dag begint rustig, met een uitgebreid ontbijt en tijd voor elkaar.</span>',
			'<strong>Bewegen</strong><span>Elke ochtend bewegen we samen. Niet een keer per week, maar iedere dag.</span>',
			'<strong>Lunch</strong><span>Vaak met verse soep. Soms helpen de bewoners zelf mee in de keuken.</span>',
			'<strong>Middag</strong><span>Muziek, schilderen, een verhalenmiddag, wandelen of juist een rustig moment.</span>',
			'<strong>Avond</strong><span>We eten samen een warme maaltijd. Wie wil, ontspant daarna in de zoutkamer.</span>',
		),
		'kbj-dag',
		true
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);
