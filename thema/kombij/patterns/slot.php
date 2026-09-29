<?php
/**
 * Title: Slotsectie
 * Slug: kombij/slot
 * Categories: kombij
 * Description: De laatste stap onderaan elke pagina: een rondleiding plannen of bellen.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<!-- wp:kbj/illustratie {"naam":"koffie","className":"kbj-tekening-slot"} /-->' . "\n"
	. '<!-- wp:kbj/afsluiter /-->' . "\n",
	array(
		'achtergrond' => 'glasblauw',
		'klasse'      => 'kbj-slot kbj-lood',
		'breed'       => false,
	)
);
