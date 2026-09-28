<?php
/**
 * Title: Wat kost het?
 * Slug: kombij/geld
 * Categories: kombij
 * Description: De vier regelingen op een rij, in gewone woorden.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop(
		'Kosten',
		'Wat kost het, en wie betaalt?',
		'De meeste zorg bij KomBij wordt vergoed. Welke regeling voor u geldt, hangt af van uw situatie. Twijfelt u? Bel ons, dan zoeken we het samen uit.',
		false
	)
	. kbj_lijst( kbj_regelingen(), 'kbj-regelingen' )
	. kbj_p( '<a href="/kosten-en-financiering/">Alles over kosten en financiering</a>', 'kbj-verder' ),
	array( 'klasse' => 'kbj-geld kbj-reveal' )
);
