<?php
/**
 * Bedrijfsgegevens op één plek.
 *
 * Eén scherm waar de gegevens staan die overal terugkomen: in de footer, in de
 * contactblokken en in de informatie die Google uitleest. Verander je ze hier,
 * dan kloppen ze meteen overal.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CJT_OPTIE = 'cjt_bedrijfsgegevens';

/**
 * De velden op het instellingenscherm.
 *
 * Voeg hier gerust velden toe die deze klant nodig heeft (een prijs, een
 * openingstijd, een reviewlink). Het scherm en de opschoning lopen mee.
 *
 * @return array[]
 */
function cjt_optie_velden() {
	return array(
		'bedrijf'    => array(
			'label' => __( 'Bedrijfsnaam', 'creajt-starter' ),
			'hulp'  => __( 'Zoals hij officieel heet. Laat leeg en we gebruiken de sitenaam.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'zin'        => array(
			'label' => __( 'Eén zin over het bedrijf', 'creajt-starter' ),
			'hulp'  => __( 'Komt in de footer en in de omschrijving die Google toont. Hooguit een regel of twee.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'email'      => array(
			'label' => __( 'E-mailadres', 'creajt-starter' ),
			'hulp'  => __( 'Hier komen de berichten uit het contactformulier binnen.', 'creajt-starter' ),
			'type'  => 'email',
		),
		'telefoon'   => array(
			'label' => __( 'Telefoonnummer', 'creajt-starter' ),
			'hulp'  => __( 'Internationaal genoteerd, bijvoorbeeld +31612345678.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'straat'     => array(
			'label' => __( 'Straat en huisnummer', 'creajt-starter' ),
			'hulp'  => __( 'Alleen invullen als er een adres is waar klanten langs kunnen komen.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'postcode'   => array(
			'label' => __( 'Postcode', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'plaats'     => array(
			'label' => __( 'Vestigingsplaats', 'creajt-starter' ),
			'hulp'  => __( 'Draagt de lokale vindbaarheid. Moet gelijk zijn aan het Google-bedrijfsprofiel.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'werkgebied' => array(
			'label' => __( 'Werkgebied', 'creajt-starter' ),
			'hulp'  => __( 'Bijvoorbeeld: Limburg, Noord-Brabant. Scheiden met een komma.', 'creajt-starter' ),
			'type'  => 'text',
		),
		'kvk'        => array(
			'label' => __( 'KvK-nummer', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'btw'        => array(
			'label' => __( 'Btw-nummer', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'text',
		),
		'instagram'  => array(
			'label' => __( 'Instagram', 'creajt-starter' ),
			'hulp'  => __( 'Volledige link naar het profiel.', 'creajt-starter' ),
			'type'  => 'url',
		),
		'facebook'   => array(
			'label' => __( 'Facebook', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'linkedin'   => array(
			'label' => __( 'LinkedIn', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'youtube'    => array(
			'label' => __( 'YouTube', 'creajt-starter' ),
			'hulp'  => '',
			'type'  => 'url',
		),
		'review'     => array(
			'label' => __( 'Link om een Google-review achter te laten', 'creajt-starter' ),
			'hulp'  => __( 'Te vinden in het Google-bedrijfsprofiel onder "Vraag om reviews".', 'creajt-starter' ),
			'type'  => 'url',
		),
	);
}

/**
 * Eén bedrijfsgegeven ophalen.
 *
 * @param string $sleutel   Veldnaam.
 * @param string $standaard Waarde als het veld leeg is.
 * @return string
 */
function cjt_optie( $sleutel, $standaard = '' ) {
	$opties = get_option( CJT_OPTIE, array() );

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
function cjt_optie_menu() {
	add_menu_page(
		__( 'Gegevens van de site', 'creajt-starter' ),
		__( 'Gegevens', 'creajt-starter' ),
		'manage_options',
		'cjt-gegevens',
		'cjt_optie_scherm',
		'dashicons-info-outline',
		7
	);
}
add_action( 'admin_menu', 'cjt_optie_menu' );

/**
 * Registreert de optie met een eigen opschoning per veld.
 */
function cjt_optie_register() {
	register_setting(
		'cjt_gegevens',
		CJT_OPTIE,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'cjt_optie_schoonmaken',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'cjt_optie_register' );

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
function cjt_optie_schoonmaken( $ruw ) {
	$schoon = array();

	if ( ! is_array( $ruw ) ) {
		return $schoon;
	}

	foreach ( cjt_optie_velden() as $sleutel => $veld ) {
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
			case 'checkbox':
				$schoon[ $sleutel ] = 'aan' === $waarde ? 'aan' : 'uit';
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
function cjt_optie_scherm() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Gegevens van de site', 'creajt-starter' ); ?></h1>
		<p class="description" style="max-width:40rem;">
			<?php esc_html_e( 'Deze gegevens komen terug in de footer, op de contactpagina en in de informatie die Google over het bedrijf uitleest. Eén plek, dus je hoeft ze niet op vier pagina\'s bij te werken.', 'creajt-starter' ); ?>
		</p>
		<?php cjt_optie_waarschuwingen(); ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'cjt_gegevens' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( cjt_optie_velden() as $sleutel => $veld ) : ?>
					<tr>
						<th scope="row">
							<label for="cjt-<?php echo esc_attr( $sleutel ); ?>"><?php echo esc_html( $veld['label'] ); ?></label>
						</th>
						<td>
							<?php if ( 'checkbox' === $veld['type'] ) : ?>
								<input type="hidden" name="<?php echo esc_attr( CJT_OPTIE . '[' . $sleutel . ']' ); ?>" value="uit">
								<input type="checkbox" id="cjt-<?php echo esc_attr( $sleutel ); ?>" name="<?php echo esc_attr( CJT_OPTIE . '[' . $sleutel . ']' ); ?>" value="aan" <?php checked( 'aan', cjt_optie( $sleutel, 'aan' ) ); ?>>
							<?php else : ?>
								<input
									type="<?php echo esc_attr( 'email' === $veld['type'] ? 'email' : ( 'url' === $veld['type'] ? 'url' : 'text' ) ); ?>"
									id="cjt-<?php echo esc_attr( $sleutel ); ?>"
									name="<?php echo esc_attr( CJT_OPTIE . '[' . $sleutel . ']' ); ?>"
									value="<?php echo esc_attr( cjt_optie( $sleutel ) ); ?>"
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
	</div>
	<?php
}

/**
 * Twee instellingen van WordPress zelf die de site onzichtbaar kunnen maken.
 *
 * Ze staan ergens anders in WordPress, en juist daarom worden ze vergeten. De
 * eerste zet Google buiten de deur; dat hoort tijdens het bouwen ook zo, maar
 * niet als de site live gaat. De tweede zorgt dat de site zichzelf als Engels
 * aankondigt terwijl alles wat erop staat Nederlands is.
 */
function cjt_optie_waarschuwingen() {
	if ( ! get_option( 'blog_public' ) ) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
			esc_html__( 'Zoekmachines worden op dit moment tegengehouden.', 'creajt-starter' ),
			esc_html__( 'Zolang dat aanstaat komt de site niet in Google. Prima tijdens het bouwen, maar zet het uit voor je live gaat.', 'creajt-starter' ),
			esc_url( admin_url( 'options-reading.php' ) ),
			esc_html__( 'Naar die instelling', 'creajt-starter' )
		);
	}

	if ( 0 !== strpos( strtolower( get_locale() ), 'nl' ) ) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
			esc_html__( 'De taal van WordPress staat niet op Nederlands.', 'creajt-starter' ),
			esc_html__( 'De site zegt zelf wel dat hij Nederlands is, dus Google leest hem goed. Maar de datums, de knoppen en het beheerscherm blijven Engels.', 'creajt-starter' ),
			esc_url( admin_url( 'options-general.php' ) ),
			esc_html__( 'Naar die instelling', 'creajt-starter' )
		);
	}
}
