<?php
/**
 * Title: Pagina werken bij
 * Slug: kombij/pagina-werken
 * Categories: kombij
 * Description: Vacatures en vrijwilligerswerk.
 */

$mail = '<a href="mailto:werkenbij@kombijmaasbommel.nl">werkenbij@kombijmaasbommel.nl</a>';

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Vacatures', 'Wij zoeken collega’s', 'In een klein team werk je dicht bij de mensen voor wie je zorgt. Je kent hun verhaal, en zij kennen jou.', false )
	. '<!-- wp:kbj/vacatures /-->' . "\n"
	. kbj_p( 'Staat jouw functie er niet bij? Een open sollicitatie is altijd welkom via ' . $mail . '.' ),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => kbj_kop( 2, 'Een paar uur per week, veel betekenen' )
					. kbj_p( 'Vrijwilligers maken het verschil bij KomBij. Een wandeling, een spelletje, samen koken of gewoon een praatje. Heb je een paar uur per week over? Dan horen we graag van je.' )
					. kbj_p( 'Aanmelden kan via ' . $mail . '.' ),
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_kader(
					kbj_kop( 3, 'Waar we hulp bij zoeken' )
					. kbj_lijst(
						array( 'Wandelen', 'Bewegen', 'Creatieve activiteiten', 'Koken', 'De leestafel', 'Gastvrouw of gastheer' ),
						'kbj-lijst'
					)
				),
				'breedte' => '45%',
			),
		),
		'',
		true
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);
