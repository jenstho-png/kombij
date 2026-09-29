<?php
/**
 * Bedrijfsgegevens op één plek.
 *
 * Eén scherm waar de gegevens staan die overal terugkomen: in de footer, in de
 * contactblokken en in de informatie die Google uitleest. Verander je ze hier,
 * dan kloppen ze meteen overal.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KBJ_OPTIE = 'kbj_bedrijfsgegevens';

/**
 * De velden op het instellingenscherm.
 *
 * Voeg hier gerust velden toe die deze klant nodig heeft (een prijs, een
 * openingstijd, een reviewlink). Het scherm en de opschoning lopen mee.
 *
 * @return array[]
 */
function kbj_optie_velden_los() {
	return array(
		'bedrijf'    => array(
			'label' => __( 'Bedrijfsnaam', 'kombij' ),
			'hulp'  => __( 'Zoals hij officieel heet. Laat leeg en we gebruiken de sitenaam.', 'kombij' ),
			'type'  => 'text',
		),
		'zin'        => array(
			'label' => __( 'Eén zin over het bedrijf', 'kombij' ),
			'hulp'  => __( 'Komt in de footer en in de omschrijving die Google toont. Hooguit een regel of twee.', 'kombij' ),
			'type'  => 'text',
		),
		'email'      => array(
			'label' => __( 'E-mailadres', 'kombij' ),
			'hulp'  => __( 'Hier komen de berichten uit het contactformulier binnen.', 'kombij' ),
			'type'  => 'email',
		),
		'telefoon'   => array(
			'label' => __( 'Telefoonnummer', 'kombij' ),
			'hulp'  => __( 'Internationaal genoteerd, bijvoorbeeld +31612345678.', 'kombij' ),
			'type'  => 'text',
		),
		'straat'     => array(
			'label' => __( 'Straat en huisnummer', 'kombij' ),
			'hulp'  => __( 'Alleen invullen als er een adres is waar klanten langs kunnen komen.', 'kombij' ),
			'type'  => 'text',
		),
		'postcode'   => array(
			'label' => __( 'Postcode', 'kombij' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'plaats'     => array(
			'label' => __( 'Vestigingsplaats', 'kombij' ),
			'hulp'  => __( 'Draagt de lokale vindbaarheid. Moet gelijk zijn aan het Google-bedrijfsprofiel.', 'kombij' ),
			'type'  => 'text',
		),
		'werkgebied' => array(
			'label' => __( 'Werkgebied', 'kombij' ),
			'hulp'  => __( 'Bijvoorbeeld: Limburg, Noord-Brabant. Scheiden met een komma.', 'kombij' ),
			'type'  => 'text',
		),
		'kvk'        => array(
			'label' => __( 'KvK-nummer', 'kombij' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'btw'        => array(
			'label' => __( 'Btw-nummer', 'kombij' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'instagram'  => array(
			'label' => __( 'Instagram', 'kombij' ),
			'hulp'  => __( 'Volledige link naar het profiel.', 'kombij' ),
			'type'  => 'url',
		),
		'facebook'   => array(
			'label' => __( 'Facebook', 'kombij' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'linkedin'   => array(
			'label' => __( 'LinkedIn', 'kombij' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'youtube'    => array(
			'label' => __( 'YouTube', 'kombij' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'review'     => array(
			'label' => __( 'Link om een Google-review achter te laten', 'kombij' ),
			'hulp'  => __( 'Te vinden in het Google-bedrijfsprofiel onder "Vraag om reviews".', 'kombij' ),
			'type'  => 'url',
		),
		'dagbesteding_open'  => array(
			'label' => __( 'Dagbesteding: dagen', 'kombij' ),
			'hulp'  => __( 'Bijvoorbeeld: maandag tot en met vrijdag.', 'kombij' ),
			'type'  => 'text',
		),
		'dagbesteding_tijd'  => array(
			'label' => __( 'Dagbesteding: tijden', 'kombij' ),
			'hulp'  => __( 'Zo genoteerd: 10:30-16:30. Google leest dit ook uit.', 'kombij' ),
			'type'  => 'text',
		),
		'zoutkamer_link'     => array(
			'label' => __( 'Link om de zoutkamer te reserveren', 'kombij' ),
			'hulp'  => __( 'Het reserveringssysteem van de zoutkamer, of later de eigen site van de zoutkamer.', 'kombij' ),
			'type'  => 'url',
		),
		'afsluiter_kop'      => array(
			'label' => __( 'Afsluiter onderaan: kop', 'kombij' ),
			'hulp'  => __( 'Het blok onderaan elke pagina. "KomBij ons" krijgt vanzelf de lichte letter.', 'kombij' ),
			'type'  => 'text',
		),
		'afsluiter_tekst'    => array(
			'label' => __( 'Afsluiter onderaan: tekst', 'kombij' ),
			'hulp'  => '',
			'type'  => 'textarea',
		),
		'afsluiter_bellen'   => array(
			'label' => __( 'Afsluiter onderaan: regel over bellen', 'kombij' ),
			'hulp'  => __( 'Het telefoonnummer komt er vanzelf achter.', 'kombij' ),
			'type'  => 'text',
		),
		'werken_kop'         => array(
			'label' => __( 'Vacatures: kop', 'kombij' ),
			'hulp'  => __( 'Staat op de home en boven de voet, zolang er vacatures open staan.', 'kombij' ),
			'type'  => 'text',
		),
		'werken_tekst'       => array(
			'label' => __( 'Vacatures: tekst', 'kombij' ),
			'hulp'  => '',
			'type'  => 'textarea',
		),
		'werken_mail'        => array(
			'label' => __( 'Vacatures: e-mailadres voor open sollicitaties', 'kombij' ),
			'hulp'  => '',
			'type'  => 'email',
		),
		'knop_rondleiding'   => array(
			'label' => __( 'Knop: rondleiding', 'kombij' ),
			'hulp'  => __( 'De tekst op de hoofdknop bovenaan elke pagina en in de afsluiter.', 'kombij' ),
			'type'  => 'text',
		),
		'knop_bellen'        => array(
			'label' => __( 'Knop: bellen', 'kombij' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'vacature_over'      => array(
			'label' => __( 'Vacatures: stukje "Werken bij KomBij"', 'kombij' ),
			'hulp'  => __( 'Staat onder elke vacature.', 'kombij' ),
			'type'  => 'textarea',
		),
		'vacature_foto_url'  => array(
			'label' => __( 'Vacatures: foto bovenaan', 'kombij' ),
			'hulp'  => __( 'Plak hier de link van een foto uit de mediabibliotheek (Media, klik op de foto, "URL kopiëren"). Leeg laten voor de standaardfoto.', 'kombij' ),
			'type'  => 'url',
		),
		'nietgevonden_kop'   => array(
			'label' => __( 'Kop', 'kombij' ),
			'hulp'  => __( 'Ziet een bezoeker als hij op een link klikt die niet (meer) bestaat.', 'kombij' ),
			'type'  => 'text',
		),
		'nietgevonden_tekst' => array(
			'label' => __( 'Tekst', 'kombij' ),
			'hulp'  => '',
			'type'  => 'textarea',
		),
		'zoutkamer_site'     => array(
			'label' => __( 'Website van de zoutkamer', 'kombij' ),
			'hulp'  => __( 'De eigen site van de zoutkamer, met alle informatie voor wie los een sessie wil.', 'kombij' ),
			'type'  => 'url',
		),
		'plek_wonen'         => array(
			'label'  => __( 'Plek vrij: wonen', 'kombij' ),
			'hulp'   => __( 'Staat dit op "Plek vrij", dan ziet iedereen dat op de site. Na 45 dagen zonder bijwerken verdwijnt het vanzelf, zodat er nooit oude informatie blijft staan.', 'kombij' ),
			'type'   => 'select',
			'keuzes' => array(
				''          => __( 'Niet tonen', 'kombij' ),
				'vrij'      => __( 'Plek vrij', 'kombij' ),
				'wachtlijst' => __( 'Wachtlijst', 'kombij' ),
			),
		),
		'plek_logeren'       => array(
			'label'  => __( 'Plek vrij: logeren', 'kombij' ),
			'hulp'   => '',
			'type'   => 'select',
			'keuzes' => array(
				''          => __( 'Niet tonen', 'kombij' ),
				'vrij'      => __( 'Plek vrij', 'kombij' ),
				'wachtlijst' => __( 'Wachtlijst', 'kombij' ),
			),
		),
		'plek_dagbesteding'  => array(
			'label'  => __( 'Plek vrij: dagbesteding', 'kombij' ),
			'hulp'   => '',
			'type'   => 'select',
			'keuzes' => array(
				''          => __( 'Niet tonen', 'kombij' ),
				'vrij'      => __( 'Plek vrij', 'kombij' ),
				'wachtlijst' => __( 'Wachtlijst', 'kombij' ),
			),
		),
		'melding_aan'        => array(
			'label' => __( 'Melding tonen', 'kombij' ),
			'hulp'  => __( 'Een klein kaartje rechtsonder, bijvoorbeeld: nog plekken vrij in de zoutkamer, of het café is open. Elke bezoeker ziet hem één keer.', 'kombij' ),
			'type'  => 'checkbox',
		),
		'melding_titel'      => array(
			'label' => __( 'Melding: kop', 'kombij' ),
			'hulp'  => __( 'Kort, bijvoorbeeld: Nog plekken vrij in de zoutkamer.', 'kombij' ),
			'type'  => 'text',
		),
		'melding_tekst'      => array(
			'label' => __( 'Melding: tekst', 'kombij' ),
			'hulp'  => __( 'Eén of twee zinnen.', 'kombij' ),
			'type'  => 'text',
		),
		'melding_knop'       => array(
			'label' => __( 'Melding: tekst op de knop', 'kombij' ),
			'hulp'  => __( 'Bijvoorbeeld: Reserveer een plek. Leeg laten voor geen knop.', 'kombij' ),
			'type'  => 'text',
		),
		'melding_link'       => array(
			'label' => __( 'Melding: waar de knop heen gaat', 'kombij' ),
			'hulp'  => __( 'Een volledige link, of een pagina van de site zoals /zoutkamer/.', 'kombij' ),
			'type'  => 'text',
		),
		'melding_seconden'   => array(
			'label' => __( 'Melding: na hoeveel seconden', 'kombij' ),
			'hulp'  => __( 'Bijvoorbeeld 12. Niet te snel: eerst moet iemand kunnen lezen waar hij is.', 'kombij' ),
			'type'  => 'text',
		),
		'breedtegraad'       => array(
			'label' => __( 'Breedtegraad', 'kombij' ),
			'hulp'  => __( 'Voor de kaart in Google. Rechtsklik in Google Maps op het gebouw, het eerste getal.', 'kombij' ),
			'type'  => 'text',
		),
		'lengtegraad'        => array(
			'label' => __( 'Lengtegraad', 'kombij' ),
			'hulp'  => __( 'Het tweede getal.', 'kombij' ),
			'type'  => 'text',
		),
	);
}

/**
 * De gegevens van KomBij, voor een verse installatie.
 *
 * Zo staat er na het activeren meteen een complete site met de juiste
 * gegevens, en niet een voet met gaten erin. Daarna beheert KomBij ze zelf
 * onder Gegevens.
 *
 * @return array
 */
function kbj_optie_standaard() {
	return array(
		'bedrijf'           => 'KomBij Maasbommel',
		'zin'               => 'Wonen, logeren en dagbesteding met zorg in de voormalige Lambertuskerk in Maasbommel. Kleinschalig, huiselijk en met 24 uur per dag zorg.',
		'email'             => 'info@kombijmaasbommel.nl',
		'telefoon'          => '06 25 52 05 63',
		'straat'            => 'Raadhuisdijk 44',
		'postcode'          => '6627 AD',
		'plaats'            => 'Maasbommel',
		'werkgebied'        => 'Maasbommel, West Maas en Waal, Maas en Waal, Rivierenland',
		'instagram'         => 'https://www.instagram.com/kombij_maasbommel',
		'facebook'          => 'https://www.facebook.com/61577271294162',
		'dagbesteding_open' => 'maandag tot en met vrijdag',
		'dagbesteding_tijd' => '10:30-16:30',
		'zoutkamer_link'    => 'https://www.supersaas.nl/schedule/Zoutkamer_Maasbommel/Zoutkamers',
		'zoutkamer_site'    => 'https://www.zoutkamermaasbommel.nl/',
		'afsluiter_kop'     => 'KomBij ons langs. De koffie staat klaar.',
		'afsluiter_tekst'   => 'Zien is ervaren. Plan een rondleiding en voel zelf hoe het bij ons is.',
		'afsluiter_bellen'  => 'Liever eerst even bellen? Dat kan altijd:',
		'werken_kop'        => 'Hart voor zorg? KomBij ons werken.',
		'werken_tekst'      => 'We zoeken regelmatig collega’s in de zorg, en vrijwilligers met een paar uur over. Een klein team, korte lijnen en een werkplek die je nergens anders vindt.',
		'werken_mail'       => 'werkenbij@kombijmaasbommel.nl',
		'knop_rondleiding'  => 'Plan een rondleiding',
		'knop_bellen'       => 'Bel ons',
		'vacature_over'     => 'Kleinschalige zorg in een huiselijke sfeer, in een monument in Maasbommel. Een klein team, samen met vrijwilligers. Jij helpt er een tweede thuis van te maken.',
		'vacature_foto'     => 'samen-aan-tafel.webp',
		'nietgevonden_kop'  => 'Deze pagina bestaat niet (meer)',
		'nietgevonden_tekst' => 'Misschien is hij verhuisd, of stond er een tikfout in de link. Kies hieronder waar u naartoe wilt, of neem contact met ons op.',
		'melding_aan'       => 'aan',
		'melding_titel'     => 'Nog plekken vrij in de zoutkamer',
		'melding_tekst'     => 'Deze week zijn er nog plekken vrij. Een sessie van 50 minuten kost € 25.',
		'melding_knop'      => 'Reserveer een plek',
		'melding_link'      => '/zoutkamer/',
		'melding_seconden'  => '12',
		'breedtegraad'      => '51.81967',
		'lengtegraad'       => '5.53460',
	);
}

/**
 * Eén bedrijfsgegeven ophalen.
 *
 * @param string $sleutel   Veldnaam.
 * @param string $standaard Waarde als het veld leeg is.
 * @return string
 */
function kbj_optie( $sleutel, $standaard = '' ) {
	$opties = get_option( KBJ_OPTIE, array() );

	if ( ! is_array( $opties ) || empty( $opties[ $sleutel ] ) ) {
		return $standaard;
	}

	return (string) $opties[ $sleutel ];
}

/**
 * Het scherm in de zijbalk.
 *
 * Bewust een eigen item en niet weggestopt onder Instellingen: dit is het enige
 * scherm dat de klant echt nodig heeft, dus mag het gevonden worden.
 */
function kbj_optie_menu() {
	$aandacht = count( kbj_aandachtspunten() );
	$bolletje = $aandacht ? sprintf( ' <span class="awaiting-mod count-%1$d"><span class="pending-count">%1$d</span></span>', $aandacht ) : '';

	add_menu_page(
		__( 'KomBij', 'kombij' ),
		__( 'KomBij', 'kombij' ) . $bolletje,
		'edit_pages',
		'kbj-gegevens',
		'kbj_overzicht_scherm',
		'dashicons-heart',
		2
	);

	add_submenu_page( 'kbj-gegevens', __( 'Overzicht', 'kombij' ), __( 'Overzicht', 'kombij' ), 'edit_pages', 'kbj-gegevens', 'kbj_overzicht_scherm' );

	foreach ( kbj_schermen() as $slug => $scherm ) {
		add_submenu_page(
			'kbj-gegevens',
			$scherm['titel'],
			$scherm['menu'],
			'manage_options',
			$slug,
			function () use ( $slug ) {
				kbj_optie_scherm( $slug );
			}
		);
	}

	add_submenu_page( 'kbj-gegevens', __( 'Pagina\'s', 'kombij' ), __( 'Pagina\'s', 'kombij' ), 'edit_pages', 'edit.php?post_type=page' );
	add_submenu_page( 'kbj-gegevens', __( 'Nieuwe vacature', 'kombij' ), __( 'Nieuwe vacature', 'kombij' ), 'edit_posts', 'post-new.php?post_type=vacature' );
	add_submenu_page( 'kbj-gegevens', __( 'Foto\'s en bestanden', 'kombij' ), __( 'Foto\'s en bestanden', 'kombij' ), 'upload_files', 'upload.php' );
	add_submenu_page( 'kbj-gegevens', __( 'Menu bovenaan', 'kombij' ), __( 'Menu bovenaan', 'kombij' ), 'edit_theme_options', 'site-editor.php?p=%2Fnavigation' );
}

/**
 * De schermen met gegevens, elk met de blokken velden die erbij horen.
 *
 * @return array
 */
function kbj_schermen() {
	return array(
		'kbj-contact' => array(
			'menu'   => __( 'Contact en bedrijf', 'kombij' ),
			'titel'  => __( 'Contact en bedrijf', 'kombij' ),
			'uitleg' => __( 'Naam, adres, telefoon, e-mail en sociale media. Deze gegevens staan in de footer, op de contactpagina en in de informatie die Google over KomBij uitleest. U past ze hier één keer aan en ze kloppen overal.', 'kombij' ),
			'blokken' => array( 'bedrijf', 'online', 'kaart' ),
		),
		'kbj-tijden'  => array(
			'menu'   => __( 'Openingstijden en plek vrij', 'kombij' ),
			'titel'  => __( 'Openingstijden en plek vrij', 'kombij' ),
			'uitleg' => __( 'De tijden van de dagbesteding staan op het bordje rechtsboven. Plek vrij of wachtlijst ziet de bezoeker bij wonen, logeren en dagbesteding. Werk dit af en toe bij: na 45 dagen zonder opslaan verdwijnt de melding vanzelf, zodat er nooit iets oud op de site staat.', 'kombij' ),
			'blokken' => array( 'tijden', 'plek' ),
		),
		'kbj-melding' => array(
			'menu'   => __( 'Melding rechtsonder', 'kombij' ),
			'titel'  => __( 'Melding rechtsonder', 'kombij' ),
			'uitleg' => __( 'Het kleine berichtje dat na een paar seconden rechtsonder op de site verschijnt, bijvoorbeeld over vrije plekken in de zoutkamer. Zet het vinkje uit om de melding te verbergen.', 'kombij' ),
			'blokken' => array( 'melding' ),
		),
		'kbj-teksten' => array(
			'menu'   => __( 'Teksten op elke pagina', 'kombij' ),
			'titel'  => __( 'Teksten op elke pagina', 'kombij' ),
			'uitleg' => __( 'Teksten die op meerdere pagina\'s terugkomen: de knoppen, het blok onderaan elke pagina, de strook over werken bij KomBij, de vacatures, de zoutkamer en de pagina die een bezoeker ziet bij een link die niet bestaat. Eén keer aanpassen en het klopt overal.', 'kombij' ),
			'blokken' => array( 'teksten', 'zoutkamer', '404' ),
		),
	);
}

/**
 * Wat aandacht nodig heeft. Het aantal staat als bolletje bij KomBij in het menu.
 *
 * @return array Lijst met array( titel, uitleg, link, knoptekst ).
 */
function kbj_aandachtspunten() {
	$punten = array();

	if ( ! get_option( 'blog_public' ) ) {
		$punten[] = array(
			__( 'Zoekmachines worden tegengehouden.', 'kombij' ),
			__( 'Zolang dit aanstaat komt de site niet in Google. Prima tijdens het bouwen, maar zet het uit zodra de site live gaat.', 'kombij' ),
			admin_url( 'options-reading.php' ),
			__( 'Naar die instelling', 'kombij' ),
		);
	}

	if ( 0 !== strpos( strtolower( get_locale() ), 'nl' ) ) {
		$punten[] = array(
			__( 'WordPress staat niet op Nederlands.', 'kombij' ),
			__( 'Datums, knoppen en het beheer blijven dan Engels.', 'kombij' ),
			admin_url( 'options-general.php' ),
			__( 'Naar die instelling', 'kombij' ),
		);
	}

	$gezet = '' !== kbj_optie( 'plek_wonen' ) || '' !== kbj_optie( 'plek_logeren' ) || '' !== kbj_optie( 'plek_dagbesteding' );
	$datum = kbj_optie( 'plek_datum' );

	if ( $gezet && ( '' === $datum || strtotime( $datum ) < strtotime( '-45 days' ) ) ) {
		$punten[] = array(
			__( 'Plek vrij is langer dan 45 dagen niet bijgewerkt.', 'kombij' ),
			__( 'Daarom staat het nu niet op de site. Kijk of het nog klopt en klik op Opslaan.', 'kombij' ),
			admin_url( 'admin.php?page=kbj-tijden' ),
			__( 'Bijwerken', 'kombij' ),
		);
	}

	return $punten;
}

/**
 * Het beheermenu opruimen: wat de site niet gebruikt, gaat weg.
 *
 * Berichten en reacties gebruikt KomBij niet. Pagina's, foto's en vacatures
 * staan onder KomBij, dus die hoeven niet nog een keer los in het menu.
 */
function kbj_menu_opruimen() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php?post_type=page' );
	remove_menu_page( 'upload.php' );

	// Onder KomBij: eerst het overzicht, dan de gegevens, dan pagina's en de rest.
	global $submenu;

	if ( empty( $submenu['kbj-gegevens'] ) ) {
		return;
	}

	$volgorde = array_merge( array( 'kbj-gegevens' ), array_keys( kbj_schermen() ), array( 'edit.php?post_type=page', 'edit.php?post_type=vacature', 'post-new.php?post_type=vacature', 'upload.php', 'site-editor.php?p=%2Fnavigation' ) );

	usort(
		$submenu['kbj-gegevens'],
		function ( $a, $b ) use ( $volgorde ) {
			$pa = array_search( $a[2], $volgorde, true );
			$pb = array_search( $b[2], $volgorde, true );

			return ( false === $pa ? 99 : $pa ) - ( false === $pb ? 99 : $pb );
		}
	);
}
add_action( 'admin_menu', 'kbj_menu_opruimen', 999 );

/**
 * Pagina's en foto's staan niet meer los in het menu. Zorg dat KomBij open
 * blijft staan en het juiste item oplicht als u daar bent.
 *
 * @param string $ouder Het menu-item dat openstaat.
 * @return string
 */
function kbj_menu_ouder( $ouder ) {
	global $submenu_file;
	$scherm = get_current_screen();

	if ( ! $scherm ) {
		return $ouder;
	}

	if ( 'page' === $scherm->post_type && in_array( $scherm->base, array( 'edit', 'post' ), true ) ) {
		$submenu_file = 'edit.php?post_type=page'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		return 'kbj-gegevens';
	}

	if ( in_array( $scherm->base, array( 'upload', 'media', 'attachment' ), true ) ) {
		$submenu_file = 'upload.php'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		return 'kbj-gegevens';
	}

	return $ouder;
}
add_filter( 'parent_file', 'kbj_menu_ouder' );

/**
 * Een korte uitleg bovenaan de lijsten van pagina's, vacatures en foto's, met
 * de weg terug naar het overzicht.
 */
function kbj_lijst_uitleg() {
	$scherm = get_current_screen();

	if ( ! $scherm ) {
		return;
	}

	$teksten = array(
		'edit-page'     => __( 'Klik op de naam van een pagina om hem te openen. Klik daarna op een tekst of foto om die aan te passen, en op Opslaan rechtsboven.', 'kombij' ),
		'edit-vacature' => __( 'Klik op een vacature om hem aan te passen, of maak een nieuwe. Een vacature offline halen: open hem en zet hem terug naar Concept.', 'kombij' ),
		'upload'        => __( 'Sleep foto\'s hierheen om ze te uploaden. Gebruik liefst liggende foto\'s van minimaal 1600 pixels breed.', 'kombij' ),
	);

	if ( ! isset( $teksten[ $scherm->id ] ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info"><p>%s <a href="%s">%s</a></p></div>',
		esc_html( $teksten[ $scherm->id ] ),
		esc_url( admin_url( 'admin.php?page=kbj-gegevens' ) ),
		esc_html__( 'Terug naar het KomBij-overzicht', 'kombij' )
	);
}
add_action( 'admin_notices', 'kbj_lijst_uitleg' );

/**
 * Reacties helemaal uit: geen formulier, geen spam.
 */
function kbj_reacties_uit() {
	foreach ( array( 'post', 'page' ) as $soort ) {
		remove_post_type_support( $soort, 'comments' );
		remove_post_type_support( $soort, 'trackbacks' );
	}
}
add_action( 'init', 'kbj_reacties_uit', 100 );
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Berichten en reacties ook uit de zwarte balk bovenaan.
 *
 * @param WP_Admin_Bar $balk De balk.
 */
function kbj_balk_opruimen( $balk ) {
	$balk->remove_node( 'comments' );
	$balk->remove_node( 'new-post' );
}
add_action( 'admin_bar_menu', 'kbj_balk_opruimen', 999 );
add_action( 'admin_menu', 'kbj_optie_menu' );

/**
 * Registreert de optie met een eigen opschoning per veld.
 */
function kbj_optie_register() {
	register_setting(
		'kbj_gegevens',
		KBJ_OPTIE,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'kbj_optie_schoonmaken',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'kbj_optie_register' );

/**
 * Maakt de ingestuurde waarden schoon.
 *
 * Let op: WordPress roept de opschoning soms twee keer aan met dezelfde
 * invoer. Alles hieronder moet daar tegen kunnen, dus geef nooit iets terug dat
 * bij een tweede ronde iets anders oplevert.
 *
 * @param mixed $ruw Wat het formulier stuurde.
 * @return array
 */
function kbj_optie_schoonmaken( $ruw ) {
	/*
	 * Elk scherm stuurt alleen zijn eigen velden mee. Daarom beginnen we bij wat
	 * er al stond, anders wist opslaan op het ene scherm de velden van het andere.
	 */
	$schoon = get_option( KBJ_OPTIE, array() );
	$schoon = is_array( $schoon ) ? $schoon : array();

	if ( ! is_array( $ruw ) ) {
		return $schoon;
	}

	/*
	 * Wanneer de vrije plekken voor het laatst zijn opgeslagen. Zo kan de site
	 * een oude melding vanzelf weghalen. Een datum van vandaag blijft vandaag,
	 * dus een tweede ronde opschonen verandert niets.
	 */
	if ( isset( $ruw['plek_wonen'] ) || isset( $ruw['plek_logeren'] ) || isset( $ruw['plek_dagbesteding'] ) ) {
		$schoon['plek_datum'] = isset( $ruw['plek_datum'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $ruw['plek_datum'] ) && ! isset( $_POST['option_page'] ) ? (string) $ruw['plek_datum'] : wp_date( 'Y-m-d' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	foreach ( kbj_optie_velden() as $sleutel => $veld ) {
		if ( ! isset( $ruw[ $sleutel ] ) ) {
			continue;
		}

		$waarde = trim( (string) $ruw[ $sleutel ] );

		switch ( $veld['type'] ) {
			case 'email':
				$schoon[ $sleutel ] = sanitize_email( $waarde );
				break;

			case 'url':
				$schoon[ $sleutel ] = esc_url_raw( $waarde );
				break;

			/*
			 * Een vinkje dat uit staat stuurt niets mee. Daarom staat er in het
			 * formulier een verborgen veld met "uit" vóór het vinkje: dan komt
			 * er altijd een waarde binnen, en betekent "leeg" niet per ongeluk
			 * "nog nooit ingevuld".
			 */
			case 'textarea':
				$schoon[ $sleutel ] = sanitize_textarea_field( $waarde );
				break;

			case 'kop':
				break;

			case 'checkbox':
				$schoon[ $sleutel ] = 'aan' === $waarde ? 'aan' : 'uit';
				break;

			case 'select':
				$schoon[ $sleutel ] = array_key_exists( $waarde, $veld['keuzes'] ) ? $waarde : '';
				break;

			default:
				$schoon[ $sleutel ] = sanitize_text_field( $waarde );
		}
	}

	return $schoon;
}

/**
 * Tekent een scherm met gegevens: alleen de blokken die bij dat scherm horen.
 *
 * @param string $slug Welk scherm.
 */
function kbj_optie_scherm( $slug = 'kbj-contact' ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$schermen = kbj_schermen();
	$scherm   = isset( $schermen[ $slug ] ) ? $schermen[ $slug ] : reset( $schermen );
	$velden   = kbj_optie_velden( $scherm['blokken'] );
	$koppen   = array_filter(
		$velden,
		function ( $veld ) {
			return 'kop' === $veld['type'];
		}
	);
	?>
	<div class="wrap kbj-beheer">
		<?php kbj_beheer_stijl(); ?>
		<p class="kbj-beheer__terug"><a href="<?php echo esc_url( admin_url( 'admin.php?page=kbj-gegevens' ) ); ?>">&larr; <?php esc_html_e( 'Terug naar het overzicht', 'kombij' ); ?></a></p>
		<h1><?php echo esc_html( $scherm['titel'] ); ?></h1>
		<?php if ( isset( $_GET['settings-updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e( 'Opgeslagen. De website is bijgewerkt.', 'kombij' ); ?></strong></p></div>
		<?php endif; ?>
		<div class="notice notice-info inline kbj-beheer__uitleg"><p><?php echo esc_html( $scherm['uitleg'] ); ?></p></div>

		<?php if ( count( $koppen ) > 1 ) : ?>
			<p class="kbj-beheer__naar">
				<strong><?php esc_html_e( 'Op deze pagina:', 'kombij' ); ?></strong>
				<?php
				$eerste = true;
				foreach ( $koppen as $sleutel => $veld ) {
					echo $eerste ? ' ' : ' &middot; ';
					printf( '<a href="#%s">%s</a>', esc_attr( $sleutel ), esc_html( $veld['label'] ) );
					$eerste = false;
				}
				?>
			</p>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'kbj_gegevens' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( $velden as $sleutel => $veld ) : ?>
					<?php if ( 'kop' === $veld['type'] ) : ?>
						<tr id="<?php echo esc_attr( $sleutel ); ?>"><th colspan="2" class="kbj-beheer__kop"><h2><?php echo esc_html( $veld['label'] ); ?></h2></th></tr>
						<?php continue; ?>
					<?php endif; ?>
					<tr>
						<th scope="row">
							<label for="kbj-<?php echo esc_attr( $sleutel ); ?>"><?php echo esc_html( $veld['label'] ); ?></label>
						</th>
						<td>
							<?php if ( 'textarea' === $veld['type'] ) : ?>
								<textarea id="kbj-<?php echo esc_attr( $sleutel ); ?>" name="<?php echo esc_attr( KBJ_OPTIE . '[' . $sleutel . ']' ); ?>" rows="3" class="large-text"><?php echo esc_textarea( kbj_optie( $sleutel ) ); ?></textarea>
							<?php elseif ( 'select' === $veld['type'] ) : ?>
								<select id="kbj-<?php echo esc_attr( $sleutel ); ?>" name="<?php echo esc_attr( KBJ_OPTIE . '[' . $sleutel . ']' ); ?>">
									<?php foreach ( $veld['keuzes'] as $waarde => $naam ) : ?>
										<option value="<?php echo esc_attr( $waarde ); ?>" <?php selected( $waarde, kbj_optie( $sleutel ) ); ?>><?php echo esc_html( $naam ); ?></option>
									<?php endforeach; ?>
								</select>
							<?php elseif ( 'checkbox' === $veld['type'] ) : ?>
								<input type="hidden" name="<?php echo esc_attr( KBJ_OPTIE . '[' . $sleutel . ']' ); ?>" value="uit">
								<input type="checkbox" id="kbj-<?php echo esc_attr( $sleutel ); ?>" name="<?php echo esc_attr( KBJ_OPTIE . '[' . $sleutel . ']' ); ?>" value="aan" <?php checked( 'aan', kbj_optie( $sleutel, 'aan' ) ); ?>>
							<?php else : ?>
								<input
									type="<?php echo esc_attr( 'email' === $veld['type'] ? 'email' : ( 'url' === $veld['type'] ? 'url' : 'text' ) ); ?>"
									id="kbj-<?php echo esc_attr( $sleutel ); ?>"
									name="<?php echo esc_attr( KBJ_OPTIE . '[' . $sleutel . ']' ); ?>"
									value="<?php echo esc_attr( kbj_optie( $sleutel ) ); ?>"
									class="regular-text"
								>
							<?php endif; ?>
							<?php if ( $veld['hulp'] ) : ?>
								<p class="description"><?php echo esc_html( $veld['hulp'] ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( __( 'Opslaan', 'kombij' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Link naar een deel van de site in de site-editor.
 *
 * @param string $deel header, footer of navigation.
 * @return string
 */
function kbj_editor_link( $deel ) {
	if ( 'navigation' === $deel ) {
		return admin_url( 'site-editor.php?p=%2Fnavigation' );
	}

	return admin_url( 'site-editor.php?p=' . rawurlencode( '/wp_template_part/' . get_stylesheet() . '//' . $deel ) . '&canvas=edit' );
}

/**
 * De stijl van de KomBij-schermen in het beheer.
 */
function kbj_beheer_stijl() {
	?>
	<style>
		.kbj-beheer{max-width:72rem}
		.kbj-beheer h1{font-size:1.75rem;margin:.25rem 0 1rem}
		.kbj-beheer__terug{margin:1rem 0 0}
		.kbj-beheer__terug a{text-decoration:none}
		.kbj-beheer__uitleg{max-width:48rem;padding:.25rem 1rem}
		.kbj-beheer__uitleg p{font-size:14px}
		.kbj-beheer__naar{margin:1.25rem 0 0}
		.kbj-beheer__kop{padding-top:2rem!important;border-bottom:1px solid #dcdcde}
		.kbj-beheer__kop h2{margin:0}
		.kbj-welkom{display:flex;flex-wrap:wrap;gap:1rem;align-items:center;justify-content:space-between;background:#0a2444;color:#fff;border-radius:14px;padding:1.5rem 1.75rem;margin:1rem 0 1.25rem}
		.kbj-welkom h1{color:#fff;margin:0 0 .35rem;padding:0}
		.kbj-welkom p{color:#dbe7f3;margin:0;font-size:14px;max-width:40rem}
		.kbj-welkom .button{background:#fff;color:#0a2444;border-color:#fff;border-radius:999px;padding:.35rem 1.1rem;min-height:40px;line-height:2}
		.kbj-let{background:#fff8e5;border:1px solid #f0c33c;border-radius:12px;padding:.85rem 1.25rem;margin:0 0 1.25rem}
		.kbj-let h2{margin:.2rem 0 .5rem;font-size:15px}
		.kbj-let ul{margin:0}
		.kbj-let li{margin:.35rem 0}
		.kbj-tegels{display:grid;grid-template-columns:repeat(auto-fill,minmax(19rem,1fr));gap:1rem}
		.kbj-tegel{background:#fff;border:1px solid #dcdcde;border-radius:14px;padding:1.1rem 1.25rem 1.25rem;display:flex;flex-direction:column}
		.kbj-tegel h2{display:flex;align-items:center;gap:.5rem;font-size:16px;margin:0 0 .3rem}
		.kbj-tegel h2 .dashicons{color:#1b5381}
		.kbj-tegel>p{color:#50575e;margin:0 0 .85rem}
		.kbj-knoppen{display:flex;flex-direction:column;gap:.4rem}
		.kbj-knoppen a{display:flex;justify-content:space-between;align-items:center;gap:.5rem;text-decoration:none;border:1px solid #e0e6ec;background:#f6f9fc;border-radius:10px;padding:.55rem .8rem;min-height:28px;color:#0a2444;font-weight:600}
		.kbj-knoppen a:hover,.kbj-knoppen a:focus{background:#e7f0f8;border-color:#1b5381;color:#0a2444}
		.kbj-knoppen a small{font-weight:400;color:#646970;text-align:right}
		.kbj-knoppen a::after{content:"\2192";color:#1b5381}
		.kbj-knoppen button{width:100%;text-align:left;min-height:40px;border-radius:10px!important}
		.kbj-tegel--breed{grid-column:1/-1}
		.kbj-herbouw{display:grid;grid-template-columns:repeat(auto-fill,minmax(15rem,1fr));gap:.4rem}
		.kbj-herbouw__rij{display:flex;align-items:center;gap:.6rem;border:1px solid #e0e6ec;background:#f6f9fc;border-radius:10px;padding:.55rem .8rem;min-height:28px;cursor:pointer}
		.kbj-herbouw__rij span{font-weight:600;color:#0a2444}
		.kbj-herbouw__stand{margin-left:auto;text-align:right;color:#646970}
		.kbj-herbouw__stand--aangepast{color:#996800}
		.kbj-herbouw__knop{margin:.9rem 0 0}
		.kbj-pagina-knoppen{display:grid;grid-template-columns:repeat(auto-fill,minmax(13rem,1fr));gap:.4rem}
	</style>
	<?php
}

/**
 * Eén tegel op het overzicht.
 *
 * @param string $icoon  Dashicon zonder "dashicons-".
 * @param string $titel  Kop.
 * @param string $uitleg Korte zin.
 * @param array  $knoppen Lijst met array( tekst, link, extra ).
 * @param string $extra  Klasse voor de knoppenlijst.
 */
function kbj_tegel( $icoon, $titel, $uitleg, $knoppen, $extra = '' ) {
	printf( '<section class="kbj-tegel%s">', $extra ? ' kbj-tegel--breed' : '' );
	printf( '<h2><span class="dashicons dashicons-%s" aria-hidden="true"></span>%s</h2>', esc_attr( $icoon ), esc_html( $titel ) );
	printf( '<p>%s</p>', esc_html( $uitleg ) );
	printf( '<div class="kbj-knoppen %s">', esc_attr( $extra ) );

	foreach ( $knoppen as $knop ) {
		printf(
			'<a href="%s">%s%s</a>',
			esc_url( $knop[1] ),
			'<span>' . esc_html( $knop[0] ) . '</span>',
			! empty( $knop[2] ) ? '<small>' . esc_html( $knop[2] ) . '</small>' : ''
		);
	}

	echo '</div></section>';
}

/**
 * Het overzicht: de eerste pagina als u op KomBij klikt. Alles wat u kunt
 * aanpassen, met een knop ernaartoe.
 */
function kbj_overzicht_scherm() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	// Pagina's in de volgorde van het menu, de startpagina eerst.
	$paginas = array();
	$voor    = (int) get_option( 'page_on_front' );

	if ( $voor ) {
		$paginas[] = array( __( 'Home', 'kombij' ), get_edit_post_link( $voor, 'raw' ) );
	}

	foreach ( kbj_paginas() as $pagina ) {
		$object = get_page_by_path( $pagina['slug'] );

		if ( $object ) {
			$paginas[] = array( $pagina['titel'], get_edit_post_link( $object->ID, 'raw' ) );
		}
	}

	$paginas[] = array( __( 'Alle pagina\'s', 'kombij' ), admin_url( 'edit.php?post_type=page' ) );

	$vacatures = wp_count_posts( 'vacature' );
	$open      = isset( $vacatures->publish ) ? (int) $vacatures->publish : 0;
	$melding   = 'aan' === kbj_optie( 'melding_aan', 'aan' ) ? __( 'staat aan', 'kombij' ) : __( 'staat uit', 'kombij' );
	$punten    = kbj_aandachtspunten();
	?>
	<div class="wrap kbj-beheer">
		<?php kbj_beheer_stijl(); ?>

		<div class="kbj-welkom">
			<div>
				<h1><?php esc_html_e( 'KomBij beheren', 'kombij' ); ?></h1>
				<p><?php esc_html_e( 'Alles om de website aan te passen staat hier bij elkaar. Kies wat u wilt veranderen en klik op de knop.', 'kombij' ); ?></p>
			</div>
			<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Bekijk de website', 'kombij' ); ?></a>
		</div>

		<?php if ( isset( $_GET['kbj-herbouwd'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success inline"><p><?php echo esc_html( sprintf( /* translators: %d: aantal pagina's */ _n( 'Klaar: %d pagina is opnieuw opgebouwd. De vorige versie staat in de revisies.', 'Klaar: %d pagina\'s zijn opnieuw opgebouwd. De vorige versies staan in de revisies.', absint( $_GET['kbj-herbouwd'] ), 'kombij' ), absint( $_GET['kbj-herbouwd'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?></p></div>
		<?php endif; ?>

		<?php if ( $punten ) : ?>
			<div class="kbj-let">
				<h2><?php esc_html_e( 'Let op', 'kombij' ); ?></h2>
				<ul>
					<?php foreach ( $punten as $punt ) : ?>
						<li><strong><?php echo esc_html( $punt[0] ); ?></strong> <?php echo esc_html( $punt[1] ); ?> <a href="<?php echo esc_url( $punt[2] ); ?>"><?php echo esc_html( $punt[3] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="kbj-tegels">
			<?php
			kbj_tegel(
				'admin-page',
				__( 'Pagina\'s en teksten', 'kombij' ),
				__( 'Teksten, koppen, foto\'s en knoppen op een pagina. Open de pagina en klik op wat u wilt veranderen.', 'kombij' ),
				$paginas,
				'kbj-pagina-knoppen'
			);

			if ( current_user_can( 'manage_options' ) ) {
				kbj_tegel(
					'id',
					__( 'Gegevens', 'kombij' ),
					__( 'Wat op meerdere plekken terugkomt. Eén keer aanpassen en het klopt overal.', 'kombij' ),
					array(
						array( __( 'Contact en bedrijf', 'kombij' ), admin_url( 'admin.php?page=kbj-contact' ), __( 'adres, telefoon, social', 'kombij' ) ),
						array( __( 'Openingstijden en plek vrij', 'kombij' ), admin_url( 'admin.php?page=kbj-tijden' ), __( 'bordje, wachtlijst', 'kombij' ) ),
						array( __( 'Melding rechtsonder', 'kombij' ), admin_url( 'admin.php?page=kbj-melding' ), $melding ),
						array( __( 'Teksten op elke pagina', 'kombij' ), admin_url( 'admin.php?page=kbj-teksten' ), __( 'knoppen, blok onderaan', 'kombij' ) ),
					)
				);
			}

			kbj_tegel(
				'groups',
				__( 'Vacatures', 'kombij' ),
				__( 'Een vacature zet u online met een paar velden. De opmaak doet de site zelf.', 'kombij' ),
				array(
					array( __( 'Alle vacatures', 'kombij' ), admin_url( 'edit.php?post_type=vacature' ), sprintf( /* translators: %d: aantal */ _n( '%d online', '%d online', $open, 'kombij' ), $open ) ),
					array( __( 'Nieuwe vacature', 'kombij' ), admin_url( 'post-new.php?post_type=vacature' ) ),
					array( __( 'Tekst en foto bij vacatures', 'kombij' ), admin_url( 'admin.php?page=kbj-teksten#kop_teksten' ) ),
				)
			);

			kbj_tegel(
				'format-image',
				__( 'Foto\'s en bestanden', 'kombij' ),
				__( 'Upload hier foto\'s. Daarna kiest u ze in een pagina bij de foto, rechts onder "Blok".', 'kombij' ),
				array(
					array( __( 'Alle foto\'s en bestanden', 'kombij' ), admin_url( 'upload.php' ) ),
					array( __( 'Foto\'s uploaden', 'kombij' ), admin_url( 'media-new.php' ) ),
				)
			);

			if ( current_user_can( 'edit_theme_options' ) ) {
				kbj_tegel(
					'menu',
					__( 'Menu, bovenkant en onderkant', 'kombij' ),
					__( 'Wat op elke pagina staat: het menu bovenaan en de footer onderaan.', 'kombij' ),
					array(
						array( __( 'Menu bovenaan', 'kombij' ), kbj_editor_link( 'navigation' ), __( 'pagina\'s in het menu', 'kombij' ) ),
						array( __( 'Bovenkant van de site', 'kombij' ), kbj_editor_link( 'header' ), __( 'logo, knoppen', 'kombij' ) ),
						array( __( 'Onderkant van de site', 'kombij' ), kbj_editor_link( 'footer' ), __( 'footer', 'kombij' ) ),
					)
				);
			}

			if ( current_user_can( 'manage_options' ) ) :
				?>
				<section class="kbj-tegel kbj-tegel--breed">
					<h2><span class="dashicons dashicons-update" aria-hidden="true"></span><?php esc_html_e( 'Na een nieuwe versie van het thema', 'kombij' ); ?></h2>
					<p><?php esc_html_e( 'Een nieuwe versie van het thema verandert pagina\'s die al bestaan niet. Hier kiest u welke pagina\'s de nieuwste opmaak krijgen. Pagina\'s die u zelf heeft aangepast staan standaard uit, zodat uw werk blijft staan. De vorige versie van elke pagina wordt altijd eerst bewaard: terugzetten kan via Revisies in de editor.', 'kombij' ); ?></p>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="kbj_herbouw">
						<?php wp_nonce_field( 'kbj_herbouw' ); ?>
						<div class="kbj-herbouw">
							<?php foreach ( kbj_herbouw_paginas() as $id => $naam ) : ?>
								<?php $stand = kbj_pagina_stand( $id ); ?>
								<label class="kbj-herbouw__rij">
									<input type="checkbox" name="kbj_paginas[]" value="<?php echo esc_attr( $id ); ?>" <?php checked( 'origineel', $stand ); ?>>
									<span><?php echo esc_html( $naam ); ?></span>
									<small class="kbj-herbouw__stand kbj-herbouw__stand--<?php echo esc_attr( $stand ); ?>">
										<?php
										if ( 'origineel' === $stand ) {
											esc_html_e( 'niet aangepast', 'kombij' );
										} elseif ( 'aangepast' === $stand ) {
											esc_html_e( 'door u aangepast', 'kombij' );
										} else {
											esc_html_e( 'onbekend, misschien aangepast', 'kombij' );
										}
										?>
									</small>
								</label>
							<?php endforeach; ?>
						</div>
						<p class="kbj-herbouw__knop"><button type="submit" class="button button-secondary"><?php esc_html_e( 'Gekozen pagina\'s opnieuw opbouwen', 'kombij' ); ?></button></p>
					</form>
				</section>
				<?php
			endif;
			?>
		</div>
	</div>
	<?php
}

/** Onder welke naam een pagina onthoudt hoe het thema hem heeft opgebouwd. */
const KBJ_AFDRUK_META = '_kbj_afdruk';

/**
 * Onthoudt hoe een pagina eruitziet zoals het thema hem net heeft opgebouwd.
 *
 * @param int $id Pagina.
 */
function kbj_afdruk_zetten( $id ) {
	update_post_meta( $id, KBJ_AFDRUK_META, md5( (string) get_post_field( 'post_content', $id, 'raw' ) ) );
}

/**
 * Of iemand een pagina zelf heeft aangepast sinds het thema hem opbouwde.
 *
 * Zonder afdruk (pagina's van voor deze versie) kijken we naar de revisies:
 * wie in de editor opslaat, maakt een revisie. Geen revisies is dus nooit
 * aangepast.
 *
 * @param int $id Pagina.
 * @return string origineel, aangepast of onbekend.
 */
function kbj_pagina_stand( $id ) {
	$afdruk = (string) get_post_meta( $id, KBJ_AFDRUK_META, true );

	if ( '' !== $afdruk ) {
		return md5( (string) get_post_field( 'post_content', $id, 'raw' ) ) === $afdruk ? 'origineel' : 'aangepast';
	}

	return wp_get_post_revisions( $id, array( 'fields' => 'ids', 'posts_per_page' => 1 ) ) ? 'onbekend' : 'origineel';
}

/**
 * De pagina's die het thema kan opbouwen: de startpagina en de vaste pagina's.
 *
 * @return array ID => naam.
 */
function kbj_herbouw_paginas() {
	$lijst = array();
	$voor  = (int) get_option( 'page_on_front' );

	if ( $voor ) {
		$lijst[ $voor ] = __( 'Home', 'kombij' );
	}

	foreach ( kbj_paginas() as $pagina ) {
		$object = get_page_by_path( $pagina['slug'] );

		if ( $object ) {
			$lijst[ $object->ID ] = $pagina['titel'];
		}
	}

	return $lijst;
}

/**
 * Bouwt de gekozen pagina's opnieuw op uit de patronen.
 *
 * Alleen wat is aangevinkt. Van elke pagina gaat eerst de huidige versie de
 * revisies in, zodat niets echt kwijt kan raken.
 */
function kbj_herbouw() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Dat mag u niet.', 'kombij' ) );
	}

	check_admin_referer( 'kbj_herbouw' );

	$gekozen = isset( $_POST['kbj_paginas'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['kbj_paginas'] ) ) : array();
	$bekend  = kbj_herbouw_paginas();
	$voor    = (int) get_option( 'page_on_front' );
	$slugs   = array();
	$aantal  = 0;

	foreach ( kbj_paginas() as $pagina ) {
		$slugs[ $pagina['slug'] ] = $pagina;
	}

	foreach ( $gekozen as $id ) {
		if ( ! isset( $bekend[ $id ] ) ) {
			continue;
		}

		if ( $id === $voor ) {
			$inhoud = kbj_startpagina_inhoud();
		} else {
			$slug = get_post_field( 'post_name', $id );

			if ( ! isset( $slugs[ $slug ] ) ) {
				continue;
			}

			$inhoud = kbj_pagina_inhoud( $slugs[ $slug ] );
		}

		// Eerst de huidige versie bewaren, dan pas vervangen.
		wp_save_post_revision( $id );
		wp_update_post(
			array(
				'ID'           => $id,
				'post_content' => $inhoud,
			)
		);
		kbj_afdruk_zetten( $id );
		++$aantal;
	}

	// Ontbrekende pagina's en het menu er meteen bij.
	kbj_installatie_paginas();
	kbj_installatie_menu();

	wp_safe_redirect( admin_url( 'admin.php?page=kbj-gegevens&kbj-herbouwd=' . $aantal ) );
	exit;
}
add_action( 'admin_post_kbj_herbouw', 'kbj_herbouw' );

/**
 * Of er een plek vrij is, voor wonen, logeren of dagbesteding.
 *
 * Geeft 'vrij', 'wachtlijst' of een lege tekst. Een melding die langer dan 45
 * dagen niet is bijgewerkt telt niet meer: liever niets dan iets wat niet klopt.
 *
 * @param string $soort wonen, logeren of dagbesteding.
 * @return string
 */
function kbj_plek( $soort ) {
	$stand = kbj_optie( 'plek_' . $soort );

	if ( '' === $stand ) {
		return '';
	}

	$datum = kbj_optie( 'plek_datum' );

	if ( '' === $datum || strtotime( $datum ) < strtotime( '-45 days' ) ) {
		return '';
	}

	return $stand;
}

/**
 * De velden in blokken met een kopje, in een logische volgorde.
 *
 * @param array $alleen Alleen deze blokken; leeg is alles.
 * @return array
 */
function kbj_optie_velden( $alleen = array() ) {
	$los    = kbj_optie_velden_los();
	$blokken = array(
		'bedrijf'   => array( __( 'Bedrijf en contact', 'kombij' ), array( 'bedrijf', 'zin', 'email', 'telefoon', 'straat', 'postcode', 'plaats', 'werkgebied', 'kvk', 'btw' ) ),
		'tijden'    => array( __( 'Openingstijden dagbesteding', 'kombij' ), array( 'dagbesteding_open', 'dagbesteding_tijd' ) ),
		'plek'      => array( __( 'Plek vrij of wachtlijst', 'kombij' ), array( 'plek_wonen', 'plek_logeren', 'plek_dagbesteding' ) ),
		'melding'   => array( __( 'Melding rechtsonder', 'kombij' ), array( 'melding_aan', 'melding_titel', 'melding_tekst', 'melding_knop', 'melding_link', 'melding_seconden' ) ),
		'teksten'   => array( __( 'Teksten op elke pagina', 'kombij' ), array( 'knop_rondleiding', 'knop_bellen', 'afsluiter_kop', 'afsluiter_tekst', 'afsluiter_bellen', 'werken_kop', 'werken_tekst', 'werken_mail', 'vacature_over', 'vacature_foto_url' ) ),
		'zoutkamer' => array( __( 'Zoutkamer', 'kombij' ), array( 'zoutkamer_site', 'zoutkamer_link' ) ),
		'404'       => array( __( 'Pagina niet gevonden', 'kombij' ), array( 'nietgevonden_kop', 'nietgevonden_tekst' ) ),
		'online'    => array( __( 'Sociale media en reviews', 'kombij' ), array( 'instagram', 'facebook', 'linkedin', 'youtube', 'review' ) ),
		'kaart'     => array( __( 'Plek op de kaart (voor Google)', 'kombij' ), array( 'breedtegraad', 'lengtegraad' ) ),
	);
	$velden = array();

	if ( $alleen ) {
		$blokken = array_intersect_key( $blokken, array_flip( $alleen ) );
	}

	foreach ( $blokken as $sleutel => $blok ) {
		$velden[ 'kop_' . $sleutel ] = array(
			'label' => $blok[0],
			'hulp'  => '',
			'type'  => 'kop',
		);

		foreach ( $blok[1] as $veld ) {
			if ( isset( $los[ $veld ] ) ) {
				$velden[ $veld ] = $los[ $veld ];
				unset( $los[ $veld ] );
			}
		}
	}

	// Wat nergens is ingedeeld, komt onderaan zodat het nooit verdwijnt.
	return $alleen ? $velden : array_merge( $velden, $los );
}

/**
 * Een tekst uit Gegevens, met de standaardtekst als hij nog leeg is.
 *
 * @param string $sleutel Naam van het veld.
 * @return string
 */
function kbj_tekst( $sleutel ) {
	$standaard = kbj_optie_standaard();

	return kbj_optie( $sleutel, isset( $standaard[ $sleutel ] ) ? (string) $standaard[ $sleutel ] : '' );
}

/**
 * Het KomBij-paneel in de editor van een pagina: uitleg en knoppen, per pagina.
 */
function kbj_paginahulp() {
	$post = get_post();

	if ( ! $post || 'page' !== $post->post_type ) {
		return;
	}

	$slug     = (int) get_option( 'page_on_front' ) === (int) $post->ID ? 'home' : $post->post_name;
	$teksten  = array( __( 'Teksten op elke pagina', 'kombij' ), admin_url( 'admin.php?page=kbj-teksten' ) );
	$contact  = array( __( 'Contact en bedrijf', 'kombij' ), admin_url( 'admin.php?page=kbj-contact' ) );
	$tijden   = array( __( 'Openingstijden en plek vrij', 'kombij' ), admin_url( 'admin.php?page=kbj-tijden' ) );
	$vacature = array( __( 'Vacatures', 'kombij' ), admin_url( 'edit.php?post_type=vacature' ) );
	$zorg     = __( 'Het label "plek vrij" of "wachtlijst" zet u niet hier, maar onder Openingstijden en plek vrij. Dan klopt het op elke pagina tegelijk.', 'kombij' );

	$per = array(
		'home'                   => array( __( 'Dit is de startpagina. Het blok onderaan (kom langs) en de teksten op de knoppen staan onder Teksten op elke pagina.', 'kombij' ), array( $teksten, $tijden ) ),
		'wonen-met-zorg'         => array( $zorg, array( $tijden, $teksten ) ),
		'logeren-met-zorg'       => array( $zorg, array( $tijden, $teksten ) ),
		'dagbesteding'           => array( __( 'De openingstijden op het bordje rechtsboven en het label "plek vrij" staan onder Openingstijden en plek vrij.', 'kombij' ), array( $tijden, $teksten ) ),
		'kosten-en-financiering' => array( __( 'Bedragen en regelingen past u hier aan: klik op de tekst en typ.', 'kombij' ), array( $teksten ) ),
		'over-ons'               => array( __( 'Het verhaal van KomBij. De knop naar de geschiedenis is een gewone knop: klik erop om de tekst te veranderen.', 'kombij' ), array( $teksten ) ),
		'geschiedenis'           => array( __( 'Bij de oude foto\'s staat een bronvermelding. Laat die staan zolang de foto blijft staan; dat is een voorwaarde van de maker.', 'kombij' ), array( $teksten ) ),
		'contact'                => array( __( 'Adres, telefoon en e-mail komen uit Contact en bedrijf. Berichten uit het formulier komen binnen op het e-mailadres dat daar staat.', 'kombij' ), array( $contact, $tijden ) ),
		'werken-bij'             => array( __( 'De vacatures verschijnen hier vanzelf. Een vacature toevoegen of offline halen doet u onder Vacatures.', 'kombij' ), array( $vacature, $teksten ) ),
		'zoutkamer'              => array( __( 'De link naar de website van de zoutkamer en de melding rechtsonder staan onder KomBij.', 'kombij' ), array( array( __( 'Zoutkamer-link', 'kombij' ), admin_url( 'admin.php?page=kbj-teksten#kop_zoutkamer' ) ), array( __( 'Melding rechtsonder', 'kombij' ), admin_url( 'admin.php?page=kbj-melding' ) ) ) ),
		'bedankt'                => array( __( 'Deze pagina ziet iemand na het versturen van het contactformulier. Hij staat niet in het menu.', 'kombij' ), array() ),
		'voorwaarden'            => array( __( 'Deze pagina staat onderaan elke pagina gelinkt, naast het copyright.', 'kombij' ), array() ),
	);

	$hier = isset( $per[ $slug ] ) ? $per[ $slug ] : array( '', array( $teksten ) );

	$data = array(
		'tip'       => $hier[0],
		'stappen'   => array(
			__( 'Tekst of kop: klik erop en typ.', 'kombij' ),
			__( 'Foto: klik op de foto. Rechts onder "Blok" kiest u een andere foto.', 'kombij' ),
			__( 'Knop: klik erop om de tekst te veranderen. De link past u aan met het kettingje.', 'kombij' ),
			__( 'Klaar? Klik rechtsboven op Opslaan.', 'kombij' ),
		),
		'elders'    => $hier[1],
		'bekijk'    => array( __( 'Bekijk de pagina', 'kombij' ), get_permalink( $post ), true ),
		'overzicht' => array( __( 'Terug naar het KomBij-overzicht', 'kombij' ), admin_url( 'admin.php?page=kbj-gegevens' ) ),
	);

	wp_enqueue_script( 'kbj-paginahulp', KBJ_URI . '/assets/js/paginahulp.js', array( 'wp-plugins', 'wp-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-dom-ready' ), KBJ_VERSION, true );
	wp_add_inline_script( 'kbj-paginahulp', 'window.kbjHulp = ' . wp_json_encode( $data ) . ';', 'before' );
}
add_action( 'enqueue_block_editor_assets', 'kbj_paginahulp' );
