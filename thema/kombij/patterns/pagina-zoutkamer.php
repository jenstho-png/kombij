<?php
/**
 * Title: Pagina zoutkamer
 * Slug: kombij/pagina-zoutkamer
 * Categories: kombij
 * Description: De zoutkamer als extra: hoe het gaat, de tarieven en reserveren. Zonder medische beloftes.
 */

echo kbj_kort( array( array( '2', 'zoutkamers, voor 8 en voor 4' ), array( '50 min.', 'per sessie' ), array( '€ 25', 'voor een losse sessie' ), array( 'Ma t/m vr', 'van 9:00 tot 18:00' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$reserveren = kbj_optie( 'zoutkamer_link', 'https://www.supersaas.nl/schedule/Zoutkamer_Maasbommel/Zoutkamers' );
$site       = kbj_optie( 'zoutkamer_site', 'https://www.zoutkamermaasbommel.nl/' );
$site_naam  = preg_replace( '#^https?://(www\.)?|/$#', '', $site );

// Twee routes naar de zoutkamer, meteen duidelijk: via KomBij, of los.
echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kop( 2, 'Twee manieren om de zoutkamer te gebruiken', 'kbj-schuif', true )
	. kbj_lijst(
		array(
			'<strong>Via KomBij</strong><em>Wonen, logeren of dagbesteding</em><span>Voor bewoners, logés en gasten van de dagbesteding hoort de zoutkamer erbij. U hoeft niets te regelen, wij plannen het met u in.</span>',
			'<strong>Los een sessie</strong><em>Voor iedereen</em><span>Wilt u los de zoutkamer gebruiken? Op ' . esc_html( $site_naam ) . ' vindt u alle informatie, de tarieven en de agenda, en reserveert u zelf een sessie.</span>',
		),
		'kbj-routes'
	)
	. kbj_knoppen(
		array(
			array(
				'tekst' => 'Naar ' . esc_html( $site_naam ),
				'url'   => $site,
			),
			array(
				'tekst' => 'Reserveer direct',
				'url'   => $reserveren,
				'rand'  => true,
			),
		),
		true
	),
	array(
		'achtergrond' => 'nachtblauw',
		'klasse'      => 'kbj-zoutroutes',
	)
);

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_kolommen(
		array(
			array(
				'inhoud'  => kbj_kop( 2, 'Even op adem komen' )
					. kbj_p( 'Een zoutkamer bootst de lucht van een zoutgrot na. U zit met uw kleding aan in een relaxstoel, met een plaid als u wilt, en luistert naar rustige muziek.' )
					. kbj_p( 'Een sessie duurt 50 minuten, voor kinderen 25 minuten. Er zijn twee zoutkamers: een grote voor 8 personen en een kleine voor 4.' )
					. kbj_knoppen(
						array(
							array(
								'tekst' => 'Meer op ' . esc_html( $site_naam ),
								'url'   => $site,
							),
							array(
								'tekst' => 'Bel 06 45 44 47 07',
								'url'   => 'tel:+31645444707',
								'rand'  => true,
							),
						)
					),
				'breedte' => '55%',
			),
			array(
				'inhoud'  => kbj_foto( 'zitplek.webp', 'Een fauteuil met een kussen, om na een sessie rustig bij te komen', array( 'verhouding' => '4/5', 'positie' => '40% 50%' ) ),
				'breedte' => '45%',
			),
		),
		'kbj-duo kbj-duo--omgekeerd',
		true
	),
	array( 'klasse' => 'kbj-reveal' )
);

echo kbj_beeldband( 'kerk-groen.webp', 'Even rustig ademhalen. <span class="kbj-hl-licht">Midden in Maasbommel.</span>', 'zee', '50% 50%' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_sectiekop( 'Tarieven', 'Wat kost een sessie?', 'Open van maandag tot en met vrijdag, van 9:00 tot 18:00. Een sessie voor kinderen reserveert u telefonisch. De actuele tarieven staan altijd op ' . esc_html( $site_naam ) . '.', false )
	. kbj_lijst(
		array(
			'<strong>€ 13</strong><em>Kinderen</em><span>Van 6 maanden tot 11 jaar. 25 minuten, samen met 1 volwassene.</span>',
			'<strong>€ 25</strong><em>Losse sessie</em><span>Vanaf 11 jaar. 50 minuten.</span>',
			'<strong>€ 105</strong><em>5 sessies</em><span>€ 21 per sessie van 50 minuten.</span>',
			'<strong>€ 185</strong><em>10 sessies</em><span>€ 18,50 per sessie van 50 minuten.</span>',
			'<strong>€ 155</strong><em>Per maand</em><span>Zo vaak als u wilt, een maand lang.</span>',
		),
		'kbj-regelingen kbj-regelingen--vrij'
	),
	array(
		'achtergrond' => 'ijs',
		'klasse'      => 'kbj-reveal',
	)
);
