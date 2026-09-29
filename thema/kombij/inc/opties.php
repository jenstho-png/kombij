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
	add_menu_page(
		__( 'Gegevens van de site', 'kombij' ),
		__( 'KomBij', 'kombij' ),
		'manage_options',
		'kbj-gegevens',
		'kbj_optie_scherm',
		'dashicons-heart',
		2
	);

	add_submenu_page( 'kbj-gegevens', __( 'Gegevens en teksten', 'kombij' ), __( 'Gegevens en teksten', 'kombij' ), 'manage_options', 'kbj-gegevens', 'kbj_optie_scherm' );
	add_submenu_page( 'kbj-gegevens', __( 'Pagina\'s', 'kombij' ), __( 'Pagina\'s', 'kombij' ), 'edit_pages', 'edit.php?post_type=page' );
	add_submenu_page( 'kbj-gegevens', __( 'Nieuwe vacature', 'kombij' ), __( 'Nieuwe vacature', 'kombij' ), 'edit_posts', 'post-new.php?post_type=vacature' );
	add_submenu_page( 'kbj-gegevens', __( 'Foto\'s en bestanden', 'kombij' ), __( 'Foto\'s en bestanden', 'kombij' ), 'upload_files', 'upload.php' );
	add_submenu_page( 'kbj-gegevens', __( 'Menu bovenaan', 'kombij' ), __( 'Menu bovenaan', 'kombij' ), 'edit_theme_options', 'site-editor.php?p=%2Fnavigation' );
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

	// Onder KomBij: eerst de gegevens, dan de vacatures, dan de rest.
	global $submenu;

	if ( empty( $submenu['kbj-gegevens'] ) ) {
		return;
	}

	$volgorde = array( 'kbj-gegevens', 'edit.php?post_type=vacature', 'post-new.php?post_type=vacature', 'edit.php?post_type=page', 'upload.php', 'site-editor.php?p=%2Fnavigation' );

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
	$schoon = array();

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
 * Tekent het scherm.
 */
function kbj_optie_scherm() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Gegevens van de site', 'kombij' ); ?></h1>
		<p class="description" style="max-width:40rem;">
			<?php esc_html_e( 'Deze gegevens komen terug in de footer, op de contactpagina en in de informatie die Google over het bedrijf uitleest. Eén plek, dus je hoeft ze niet op vier pagina\'s bij te werken.', 'kombij' ); ?>
		</p>
		<?php kbj_optie_waarschuwingen(); ?>

		<div class="notice notice-info inline" style="max-width:48rem;padding:0.5rem 1rem">
			<p><strong><?php esc_html_e( 'Waar past u wat aan?', 'kombij' ); ?></strong></p>
			<ul style="list-style:disc;padding-left:1.25rem">
				<li><?php esc_html_e( 'Teksten, koppen, foto\'s en knoppen op een pagina: via Pagina\'s, open de pagina en klik op wat u wilt veranderen. Bij een foto of eigen onderdeel staan de instellingen rechts onder "Blok".', 'kombij' ); ?></li>
				<li><?php esc_html_e( 'Contactgegevens, openingstijden, plek vrij, de melding, de afsluiter onderaan en de vacaturetekst: hieronder, op deze pagina.', 'kombij' ); ?></li>
				<li><?php esc_html_e( 'Vacatures: via Vacatures in het menu links. Elk veld heeft een eigen kopje.', 'kombij' ); ?></li>
				<li><?php esc_html_e( 'Het menu: via Weergave, Editor, Navigatie.', 'kombij' ); ?></li>
			</ul>
		</div>

		<p style="margin:1.25rem 0 0">
			<strong><?php esc_html_e( 'Ga naar:', 'kombij' ); ?></strong>
			<?php
			$eerste = true;
			foreach ( kbj_optie_velden() as $sleutel => $veld ) {
				if ( 'kop' !== $veld['type'] ) {
					continue;
				}
				echo $eerste ? ' ' : ' &middot; ';
				printf( '<a href="#%s">%s</a>', esc_attr( $sleutel ), esc_html( $veld['label'] ) );
				$eerste = false;
			}
			?>
		</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'kbj_gegevens' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( kbj_optie_velden() as $sleutel => $veld ) : ?>
					<?php if ( 'kop' === $veld['type'] ) : ?>
						<tr id="<?php echo esc_attr( $sleutel ); ?>"><th colspan="2" style="padding-top:2.25rem;border-bottom:1px solid #dcdcde"><h2 style="margin:0"><?php echo esc_html( $veld['label'] ); ?></h2></th></tr>
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
			<?php submit_button(); ?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Pagina\'s opnieuw opbouwen', 'kombij' ); ?></h2>
		<?php if ( isset( $_GET['kbj-herbouwd'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success inline"><p><?php esc_html_e( 'Klaar: de pagina\'s zijn opnieuw opgebouwd met de nieuwste versie van het thema.', 'kombij' ); ?></p></div>
		<?php endif; ?>
		<p class="description" style="max-width:48rem">
			<?php esc_html_e( 'Een nieuwe versie van het thema verandert pagina\'s die al bestaan niet. Klik hier om de startpagina en alle vaste pagina\'s opnieuw op te bouwen met de nieuwste opmaak. Let op: wat u zelf in die pagina\'s heeft veranderd, gaat dan verloren. Vacatures en gegevens blijven staan.', 'kombij' ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Weet u het zeker? Eigen aanpassingen in de pagina\'s gaan verloren.', 'kombij' ) ); ?>');">
			<input type="hidden" name="action" value="kbj_herbouw">
			<?php wp_nonce_field( 'kbj_herbouw' ); ?>
			<?php submit_button( __( 'Pagina\'s opnieuw opbouwen', 'kombij' ), 'secondary' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Bouwt de startpagina en de vaste pagina's opnieuw op uit de patronen.
 */
function kbj_herbouw() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Dat mag u niet.', 'kombij' ) );
	}

	check_admin_referer( 'kbj_herbouw' );

	$voor = (int) get_option( 'page_on_front' );

	if ( $voor ) {
		wp_update_post(
			array(
				'ID'           => $voor,
				'post_content' => kbj_startpagina_inhoud(),
			)
		);
	}

	foreach ( kbj_paginas() as $pagina ) {
		$object = get_page_by_path( $pagina['slug'] );

		if ( $object ) {
			wp_update_post(
				array(
					'ID'           => $object->ID,
					'post_content' => kbj_pagina_inhoud( $pagina ),
				)
			);
		}
	}

	// Ontbrekende pagina's en het menu er meteen bij.
	kbj_installatie_paginas();
	kbj_installatie_menu();

	wp_safe_redirect( admin_url( 'admin.php?page=kbj-gegevens&kbj-herbouwd=1' ) );
	exit;
}
add_action( 'admin_post_kbj_herbouw', 'kbj_herbouw' );

/**
 * Twee instellingen van WordPress zelf die de site onzichtbaar kunnen maken.
 *
 * Ze staan ergens anders in WordPress, en juist daarom worden ze vergeten. De
 * eerste zet Google buiten de deur; dat hoort tijdens het bouwen ook zo, maar
 * niet als de site live gaat. De tweede zorgt dat de site zichzelf als Engels
 * aankondigt terwijl alles wat erop staat Nederlands is.
 */
function kbj_optie_waarschuwingen() {
	if ( ! get_option( 'blog_public' ) ) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
			esc_html__( 'Zoekmachines worden op dit moment tegengehouden.', 'kombij' ),
			esc_html__( 'Zolang dat aanstaat komt de site niet in Google. Prima tijdens het bouwen, maar zet het uit voor je live gaat.', 'kombij' ),
			esc_url( admin_url( 'options-reading.php' ) ),
			esc_html__( 'Naar die instelling', 'kombij' )
		);
	}

	if ( 0 !== strpos( strtolower( get_locale() ), 'nl' ) ) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
			esc_html__( 'De taal van WordPress staat niet op Nederlands.', 'kombij' ),
			esc_html__( 'De site zegt zelf wel dat hij Nederlands is, dus Google leest hem goed. Maar de datums, de knoppen en het beheerscherm blijven Engels.', 'kombij' ),
			esc_url( admin_url( 'options-general.php' ) ),
			esc_html__( 'Naar die instelling', 'kombij' )
		);
	}
}

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
 * @return array
 */
function kbj_optie_velden() {
	$los    = kbj_optie_velden_los();
	$blokken = array(
		'bedrijf'   => array( __( 'Bedrijf en contact', 'kombij' ), array( 'bedrijf', 'zin', 'email', 'telefoon', 'straat', 'postcode', 'plaats', 'werkgebied', 'kvk', 'btw' ) ),
		'tijden'    => array( __( 'Openingstijden dagbesteding', 'kombij' ), array( 'dagbesteding_open', 'dagbesteding_tijd' ) ),
		'plek'      => array( __( 'Plek vrij of wachtlijst', 'kombij' ), array( 'plek_wonen', 'plek_logeren', 'plek_dagbesteding' ) ),
		'melding'   => array( __( 'Melding rechtsonder', 'kombij' ), array( 'melding_aan', 'melding_titel', 'melding_tekst', 'melding_knop', 'melding_link', 'melding_seconden' ) ),
		'teksten'   => array( __( 'Teksten op elke pagina', 'kombij' ), array( 'knop_rondleiding', 'knop_bellen', 'afsluiter_kop', 'afsluiter_tekst', 'afsluiter_bellen', 'werken_kop', 'werken_tekst', 'werken_mail', 'vacature_over', 'vacature_foto_url' ) ),
		'zoutkamer' => array( __( 'Zoutkamer', 'kombij' ), array( 'zoutkamer_site', 'zoutkamer_link' ) ),
		'online'    => array( __( 'Sociale media en reviews', 'kombij' ), array( 'instagram', 'facebook', 'linkedin', 'youtube', 'review' ) ),
		'kaart'     => array( __( 'Plek op de kaart (voor Google)', 'kombij' ), array( 'breedtegraad', 'lengtegraad' ) ),
	);
	$velden = array();

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
	return array_merge( $velden, $los );
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
