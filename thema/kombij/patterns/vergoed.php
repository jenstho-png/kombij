<?php
/**
 * Title: Meestal vergoed
 * Slug: kombij/vergoed
 * Categories: kombij
 * Description: Het goede nieuws over de kosten, met de vier regelingen als tegels.
 */

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<!-- wp:kbj/illustratie {"naam":"gewelf","className":"kbj-tekening-hoek"} /-->' . "\n"
	. kbj_groep(
		kbj_groep(
			kbj_kop( 2, 'Goed nieuws: het wordt meestal vergoed.', 'kbj-schuif' )
			. kbj_p( 'Zorg bij KomBij betaalt u vaak niet zelf. Wij zoeken samen met u uit welke regeling voor u geldt, en helpen met de aanvraag.' )
			. kbj_knoppen(
				array(
					array(
						'tekst' => 'Zo werkt de vergoeding',
						'url'   => '/kosten-en-financiering/',
					),
				)
			)
		)
		. kbj_lijst(
			array(
				'<strong>WLZ</strong><span>Wet langdurige zorg</span>',
				'<strong>WMO</strong><span>Via uw gemeente</span>',
				'<strong>Zelf</strong><span>Zonder indicatie kan ook</span>',
			),
			'kbj-regels'
		),
		'kbj-vergoed__binnen'
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-vergoed kbj-reveal',
	)
);
