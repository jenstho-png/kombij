<?php
/**
 * Title: Opening van de startpagina
 * Slug: kombij/opening
 * Categories: kombij
 * Description: Grote foto met licht door gekleurd glas, de kop, twee knoppen en vier feiten.
 */

echo '<!-- wp:' . kbj_blok_attrs( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'kbj/opening',
	array(
		'kop'     => 'Een warm thuis, voor als thuis',
		'glans'   => 'niet meer gaat.',
		'tekst'   => 'Wonen, logeren en dagbesteding met zorg in een monumentaal gebouw in Maasbommel. Klein, huiselijk en dichtbij.',
		'bestand' => 'samen-aan-tafel.webp',
		'alt'     => 'Gasten en medewerkers samen aan de lange tafel bij KomBij',
		'positie' => '60% 50%',
		'align'   => 'full',
	)
) . " /-->\n";
