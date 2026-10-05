<?php
/**
 * Vacatures: een eigen onderdeel in WordPress, met een invulformulier.
 *
 * KomBij zet zelf een vacature online zonder iets van opmaak te hoeven weten.
 * Onder "Vacatures" in de zijbalk staat een formulier met vaste vakjes: de
 * functie, de uren, wat je gaat doen, wat we vragen en wat we bieden. De
 * vormgeving regelt het thema. Na de sluitingsdatum verdwijnt een vacature
 * vanzelf van de site.
 *
 * Elke vacature krijgt een eigen pagina, en Google krijgt de gegevens als
 * JobPosting mee. Daardoor kan hij verschijnen in Google Jobs, het blok met
 * vacatures bovenaan de zoekresultaten.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KBJ_VACATURE = 'vacature';

/**
 * De velden van het formulier, in de volgorde waarin ze op het scherm staan.
 *
 * Een 'lijst' is een tekstvak waarin elke regel een opsommingsteken wordt.
 *
 * @return array[]
 */
function kbj_vacature_velden() {
	return array(
		'uren'          => array(
			'label' => 'Uren per week',
			'hulp'  => 'Bijvoorbeeld: 24 tot 32 uur per week.',
			'type'  => 'text',
		),
		'dienstverband' => array(
			'label'   => 'Soort dienstverband',
			'hulp'    => '',
			'type'    => 'select',
			'keuzes'  => array(
				'PART_TIME' => 'Parttime',
				'FULL_TIME' => 'Fulltime',
				'TEMPORARY' => 'Tijdelijk',
				'PER_DIEM'  => 'Oproepbasis',
				'VOLUNTEER' => 'Vrijwilliger',
			),
		),
		'opleiding'     => array(
			'label' => 'Opleiding',
			'hulp'  => 'Bijvoorbeeld: afgeronde opleiding Verzorgende IG.',
			'type'  => 'text',
		),
		'salaris'       => array(
			'label' => 'Salaris',
			'hulp'  => 'Bijvoorbeeld: CAO VVT, FWG 35. Een bedrag of schaal trekt meer reacties dan "marktconform".',
			'type'  => 'text',
		),
		'diensten'      => array(
			'label' => 'Diensten',
			'hulp'  => 'Bijvoorbeeld: wisselende diensten, dag, avond en nacht.',
			'type'  => 'text',
		),
		'intro'         => array(
			'label' => 'Over de functie',
			'hulp'  => 'Twee of drie zinnen. Dit komt ook in het overzicht op de pagina Werken bij.',
			'type'  => 'textarea',
		),
		'taken'         => array(
			'label' => 'Wat ga je doen?',
			'hulp'  => 'Elke regel wordt een punt in de lijst.',
			'type'  => 'lijst',
		),
		'eisen'         => array(
			'label' => 'Wat vragen we?',
			'hulp'  => 'Elke regel wordt een punt in de lijst.',
			'type'  => 'lijst',
		),
		'jij'           => array(
			'label' => 'Wie ben jij?',
			'hulp'  => 'Mag leeg blijven.',
			'type'  => 'textarea',
		),
		'bieden'        => array(
			'label' => 'Wat bieden we?',
			'hulp'  => 'Elke regel wordt een punt in de lijst.',
			'type'  => 'lijst',
		),
		'email'         => array(
			'label' => 'Solliciteren via e-mail',
			'hulp'  => 'Laat leeg voor werkenbij@kombijmaasbommel.nl.',
			'type'  => 'email',
		),
		'sluitdatum'    => array(
			'label' => 'Sluitingsdatum',
			'hulp'  => 'Na deze datum verdwijnt de vacature vanzelf van de site. Leeg laten als hij open blijft.',
			'type'  => 'date',
		),
	);
}

/**
 * Het vacature-onderdeel aanmelden bij WordPress.
 */
function kbj_vacature_registreren() {
	register_post_type(
		KBJ_VACATURE,
		array(
			'labels'          => array(
				'name'               => 'Vacatures',
				'singular_name'      => 'Vacature',
				'add_new'            => 'Nieuwe vacature',
				'add_new_item'       => 'Nieuwe vacature toevoegen',
				'edit_item'          => 'Vacature bewerken',
				'new_item'           => 'Nieuwe vacature',
				'view_item'          => 'Vacature bekijken',
				'search_items'       => 'Vacatures zoeken',
				'not_found'          => 'Nog geen vacatures',
				'not_found_in_trash' => 'Geen vacatures in de prullenbak',
				'all_items'          => 'Vacatures',
				'menu_name'          => 'Vacatures',
			),
			'public'          => true,
			'has_archive'     => false,
			'show_in_menu'    => 'kbj-gegevens',
			'menu_position'   => 8,
			'menu_icon'       => 'dashicons-groups',
			'supports'        => array( 'title', 'revisions' ),
			'rewrite'         => array(
				'slug'       => 'vacatures',
				'with_front' => false,
			),
			/*
			 * Geen blok-editor, maar een gewoon formulier. De klant vult vakjes
			 * in en hoeft niets te weten van blokken of opmaak.
			 */
			'show_in_rest'    => false,
			'capability_type' => 'post',
		)
	);
}
add_action( 'init', 'kbj_vacature_registreren' );

/**
 * Een veld van een vacature ophalen.
 *
 * @param int    $id      De vacature.
 * @param string $sleutel Het veld.
 * @return string
 */
function kbj_vacature_veld( $id, $sleutel ) {
	return (string) get_post_meta( $id, '_kbj_' . $sleutel, true );
}

/**
 * Een lijstveld als losse regels.
 *
 * @param int    $id      De vacature.
 * @param string $sleutel Het veld.
 * @return string[]
 */
function kbj_vacature_regels( $id, $sleutel ) {
	$regels = preg_split( '/\r\n|\r|\n/', kbj_vacature_veld( $id, $sleutel ) );

	return array_values(
		array_filter(
			array_map(
				function ( $regel ) {
					return trim( $regel, " \t-•;" );
				},
				$regels
			)
		)
	);
}

/**
 * Het formulier op het bewerkscherm.
 */
function kbj_vacature_formulier_aanmelden() {
	add_meta_box( 'kbj-vacature', 'Gegevens van de vacature', 'kbj_vacature_formulier', KBJ_VACATURE, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'kbj_vacature_formulier_aanmelden' );

/**
 * Tekent het formulier.
 *
 * @param WP_Post $post De vacature.
 */
function kbj_vacature_formulier( $post ) {
	wp_nonce_field( 'kbj_vacature_opslaan', 'kbj_vacature_nonce' );
	?>
	<p style="max-width:44rem;">Vul de titel hierboven in als de naam van de functie, bijvoorbeeld <em>Verzorgende IG</em>. Vul daarna de vakjes hieronder in. De opmaak op de site regelt het thema. Klaar? Klik rechts op <strong>Publiceren</strong>.</p>
	<table class="form-table" role="presentation">
		<?php
		foreach ( kbj_vacature_velden() as $sleutel => $veld ) :
			$naam   = 'kbj_vacature[' . $sleutel . ']';
			$id     = 'kbj-vacature-' . $sleutel;
			$waarde = kbj_vacature_veld( $post->ID, $sleutel );
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $veld['label'] ); ?></label></th>
				<td>
					<?php if ( 'textarea' === $veld['type'] || 'lijst' === $veld['type'] ) : ?>
						<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $naam ); ?>" rows="<?php echo 'lijst' === $veld['type'] ? 6 : 4; ?>" class="large-text"><?php echo esc_textarea( $waarde ); ?></textarea>
					<?php elseif ( 'select' === $veld['type'] ) : ?>
						<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $naam ); ?>">
							<?php foreach ( $veld['keuzes'] as $code => $tekst ) : ?>
								<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $waarde, $code ); ?>><?php echo esc_html( $tekst ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<input type="<?php echo esc_attr( $veld['type'] ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $naam ); ?>" value="<?php echo esc_attr( $waarde ); ?>" class="regular-text">
					<?php endif; ?>
					<?php if ( '' !== $veld['hulp'] ) : ?>
						<p class="description"><?php echo esc_html( $veld['hulp'] ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Slaat het formulier op.
 *
 * @param int $post_id De vacature.
 */
function kbj_vacature_opslaan( $post_id ) {
	if ( ! isset( $_POST['kbj_vacature_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kbj_vacature_nonce'] ) ), 'kbj_vacature_opslaan' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$ruw = isset( $_POST['kbj_vacature'] ) && is_array( $_POST['kbj_vacature'] ) ? wp_unslash( $_POST['kbj_vacature'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	kbj_vacature_velden_opslaan( $post_id, $ruw );
}
add_action( 'save_post_' . KBJ_VACATURE, 'kbj_vacature_opslaan' );

/**
 * Schoont de velden op en slaat ze op.
 *
 * @param int   $post_id De vacature.
 * @param array $ruw     De ingestuurde waarden.
 */
function kbj_vacature_velden_opslaan( $post_id, $ruw ) {
	foreach ( kbj_vacature_velden() as $sleutel => $veld ) {
		$waarde = isset( $ruw[ $sleutel ] ) ? (string) $ruw[ $sleutel ] : '';

		switch ( $veld['type'] ) {
			case 'textarea':
			case 'lijst':
				$waarde = sanitize_textarea_field( $waarde );
				break;
			case 'email':
				$waarde = sanitize_email( $waarde );
				break;
			case 'date':
				$waarde = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $waarde ) ? $waarde : '';
				break;
			case 'select':
				$waarde = array_key_exists( $waarde, $veld['keuzes'] ) ? $waarde : 'PART_TIME';
				break;
			default:
				$waarde = sanitize_text_field( $waarde );
		}

		update_post_meta( $post_id, '_kbj_' . $sleutel, $waarde );
	}
}

/**
 * Is deze vacature nog open?
 *
 * @param int $id De vacature.
 * @return bool
 */
function kbj_vacature_open( $id ) {
	$sluit = kbj_vacature_veld( $id, 'sluitdatum' );

	if ( '' === $sluit ) {
		return true;
	}

	return $sluit >= wp_date( 'Y-m-d' );
}

/**
 * De open vacatures, nieuwste eerst.
 *
 * @return WP_Post[]
 */
function kbj_vacatures_open() {
	$alle = get_posts(
		array(
			'post_type'      => KBJ_VACATURE,
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	return array_values(
		array_filter(
			$alle,
			function ( $vacature ) {
				return kbj_vacature_open( $vacature->ID );
			}
		)
	);
}

/**
 * Een verlopen vacature is niet meer te bekijken: wie de oude link volgt,
 * komt op de pagina Werken bij met de vacatures die nog wel open staan.
 */
function kbj_vacature_verlopen() {
	if ( ! is_singular( KBJ_VACATURE ) || kbj_vacature_open( get_queried_object_id() ) ) {
		return;
	}

	wp_safe_redirect( home_url( '/werken-bij/' ), 302 );
	exit;
}
add_action( 'template_redirect', 'kbj_vacature_verlopen' );

/**
 * Kolommen in het overzicht, zodat de klant in één blik ziet wat er open staat.
 *
 * @param array $kolommen De kolommen.
 * @return array
 */
function kbj_vacature_kolommen( $kolommen ) {
	return array(
		'cb'         => $kolommen['cb'],
		'title'      => 'Functie',
		'uren'       => 'Uren',
		'sluitdatum' => 'Sluitingsdatum',
		'date'       => $kolommen['date'],
	);
}
add_filter( 'manage_' . KBJ_VACATURE . '_posts_columns', 'kbj_vacature_kolommen' );

/**
 * De inhoud van die kolommen.
 *
 * @param string $kolom De kolom.
 * @param int    $id    De vacature.
 */
function kbj_vacature_kolom( $kolom, $id ) {
	if ( 'uren' === $kolom ) {
		echo esc_html( kbj_vacature_veld( $id, 'uren' ) );
	}

	if ( 'sluitdatum' === $kolom ) {
		$sluit = kbj_vacature_veld( $id, 'sluitdatum' );

		if ( '' === $sluit ) {
			echo 'Blijft open';
		} else {
			echo esc_html( wp_date( 'j F Y', strtotime( $sluit ) ) );
			echo kbj_vacature_open( $id ) ? '' : ' <strong>(verlopen)</strong>';
		}
	}
}
add_action( 'manage_' . KBJ_VACATURE . '_posts_custom_column', 'kbj_vacature_kolom', 10, 2 );

/**
 * Het overzicht van open vacatures, voor op de pagina Werken bij.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_vacatures( $attrs ) {
	$vacatures = kbj_vacatures_open();

	if ( ! $vacatures ) {
		return sprintf(
			'<div %1$s><p>Op dit moment staan er geen vacatures open. Een open sollicitatie is altijd welkom via <a href="mailto:werkenbij@kombijmaasbommel.nl">werkenbij@kombijmaasbommel.nl</a>.</p></div>',
			get_block_wrapper_attributes( array( 'class' => 'kbj-vacatures' ) )
		);
	}

	$html = '';

	foreach ( $vacatures as $vacature ) {
		$kenmerken = array_filter(
			array(
				kbj_vacature_veld( $vacature->ID, 'uren' ),
				kbj_vacature_veld( $vacature->ID, 'opleiding' ),
			)
		);

		$html .= sprintf(
			'<li><a class="kbj-vacatures__link" href="%1$s"><strong>%2$s</strong>%3$s<span class="kbj-vacatures__tekst">%4$s</span><span class="kbj-vacatures__verder">Bekijk de vacature</span></a></li>',
			esc_url( get_permalink( $vacature ) ),
			esc_html( get_the_title( $vacature ) ),
			$kenmerken ? '<em>' . esc_html( implode( ' · ', $kenmerken ) ) . '</em>' : '',
			esc_html( kbj_inkorten( kbj_vacature_veld( $vacature->ID, 'intro' ), 150 ) )
		);
	}

	return sprintf(
		'<ul %1$s>%2$s</ul>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-vacatures' ) ),
		$html
	);
}

/**
 * De losse vacaturepagina.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_vacature( $attrs ) {
	$id = get_the_ID();

	if ( ! $id || KBJ_VACATURE !== get_post_type( $id ) ) {
		return '';
	}

	$email = kbj_vacature_veld( $id, 'email' );
	$email = '' !== $email ? $email : 'werkenbij@kombijmaasbommel.nl';

	$kenmerken = '';

	foreach ( array( 'uren', 'opleiding', 'salaris', 'diensten' ) as $sleutel ) {
		$waarde = kbj_vacature_veld( $id, $sleutel );

		if ( '' !== $waarde ) {
			$kenmerken .= '<li><span>' . esc_html( kbj_vacature_velden()[ $sleutel ]['label'] ) . '</span><span>' . esc_html( $waarde ) . '</span></li>';
		}
	}

	$sluit = kbj_vacature_veld( $id, 'sluitdatum' );

	if ( '' !== $sluit ) {
		$kenmerken .= '<li><span>Reageren kan tot</span><span>' . esc_html( wp_date( 'j F Y', strtotime( $sluit ) ) ) . '</span></li>';
	}

	$blokken = '';

	foreach ( array( 'taken', 'eisen', 'bieden' ) as $sleutel ) {
		$regels = kbj_vacature_regels( $id, $sleutel );

		if ( ! $regels ) {
			continue;
		}

		$blokken .= '<h2>' . esc_html( kbj_vacature_velden()[ $sleutel ]['label'] ) . '</h2><ul class="kbj-lijst">';

		foreach ( $regels as $regel ) {
			$blokken .= '<li>' . esc_html( $regel ) . '</li>';
		}

		$blokken .= '</ul>';

		if ( 'eisen' === $sleutel && '' !== kbj_vacature_veld( $id, 'jij' ) ) {
			$blokken .= '<h2>Wie ben jij?</h2>' . wpautop( esc_html( kbj_vacature_veld( $id, 'jij' ) ) );
		}
	}

	$onderwerp = rawurlencode( 'Sollicitatie ' . get_the_title( $id ) );

	return sprintf(
		'<article %1$s>'
			. '<header class="kbj-paginakop kbj-paginakop--beeld kbj-vacature__kop"><div class="kbj-paginakop__binnen"><div class="kbj-paginakop__tekst"><h1 class="kbj-paginakop__titel">%2$s</h1>%3$s<div class="kbj-acties"><a class="kbj-knop" href="mailto:%4$s?subject=%5$s">Solliciteer direct</a><a class="kbj-knop kbj-knop--rand" href="%6$s">Alle vacatures</a></div></div><div class="kbj-paginakop__beeld">%11$s</div></div></header>'
			. '<div class="kbj-vacature__binnen"><div class="kbj-vacature__tekst">%7$s'
			. '<h2>Werken bij KomBij</h2><p>%12$s</p>'
			. '<h2>Solliciteren</h2><p>Herken je jezelf hierin? Stuur je cv en een korte motivatie naar <a href="mailto:%4$s?subject=%5$s">%4$s</a>. Liever eerst even bellen? Dat kan op <a href="%8$s">%9$s</a>.</p>'
			. '</div><aside class="kbj-vacature__kenmerken kbj-kader kbj-kader--ijs"><h2>In het kort</h2><ul class="kbj-tijden">%10$s</ul><a class="kbj-knop" href="mailto:%4$s?subject=%5$s">Solliciteer direct</a><a class="kbj-knop kbj-knop--rand kbj-deel" href="%13$s" target="_blank" rel="noopener">%14$s<span>Deel via WhatsApp</span></a><p class="kbj-deel__uitleg">Kent u iemand voor wie dit iets is? Stuur de vacature door.</p></aside></div>'
		. '</article>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-vacature' ) ),
		esc_html( get_the_title( $id ) ),
		'' !== kbj_vacature_veld( $id, 'intro' ) ? '<p class="kbj-paginakop__intro">' . esc_html( kbj_vacature_veld( $id, 'intro' ) ) . '</p>' : '',
		esc_attr( $email ),
		esc_attr( $onderwerp ),
		esc_url( home_url( '/werken-bij/' ) ),
		$blokken,
		esc_attr( kbj_tel_url() ),
		esc_html( kbj_tel_tekst() ),
		$kenmerken,
		kbj_render_beeld(
			array(
				'bestand' => kbj_tekst( 'vacature_foto' ),
				'url'     => kbj_tekst( 'vacature_foto_url' ),
				'alt'     => '',
				'vorm'    => 'recht',
			)
		),
		esc_html( kbj_tekst( 'vacature_over' ) ),
		esc_url( 'https://wa.me/?text=' . rawurlencode( 'Vacature bij KomBij: ' . get_the_title( $id ) . ' ' . get_permalink( $id ) ) ),
		kbj_icoon_whatsapp()
	);
}

/**
 * De vacature als JobPosting voor Google.
 *
 * @return array|null
 */
function kbj_jsonld_vacature() {
	if ( ! is_singular( KBJ_VACATURE ) ) {
		return null;
	}

	$id           = get_queried_object_id();
	$omschrijving = '<p>' . esc_html( kbj_vacature_veld( $id, 'intro' ) ) . '</p>';

	foreach ( array( 'taken', 'eisen', 'bieden' ) as $sleutel ) {
		$regels = kbj_vacature_regels( $id, $sleutel );

		if ( $regels ) {
			$omschrijving .= '<p>' . esc_html( kbj_vacature_velden()[ $sleutel ]['label'] ) . '</p><ul><li>' . implode( '</li><li>', array_map( 'esc_html', $regels ) ) . '</li></ul>';
		}
	}

	$knoop = array(
		'@type'              => 'JobPosting',
		'title'              => get_the_title( $id ),
		'description'        => $omschrijving,
		'datePosted'         => get_the_date( 'Y-m-d', $id ),
		'employmentType'     => kbj_vacature_veld( $id, 'dienstverband' ) ? kbj_vacature_veld( $id, 'dienstverband' ) : 'PART_TIME',
		'hiringOrganization' => array(
			'@type'  => 'Organization',
			'name'   => kbj_bedrijfsnaam(),
			'sameAs' => home_url( '/' ),
			'logo'   => KBJ_URI . '/assets/beeld/logo.png',
		),
		'jobLocation'        => array(
			'@type'   => 'Place',
			'address' => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => kbj_optie( 'straat' ),
				'postalCode'      => kbj_optie( 'postcode' ),
				'addressLocality' => kbj_optie( 'plaats' ),
				'addressRegion'   => 'Gelderland',
				'addressCountry'  => 'NL',
			),
		),
		'directApply'        => false,
		'industry'           => 'Ouderenzorg',
		'occupationalCategory' => 'Zorg en welzijn',
		'identifier'         => array(
			'@type' => 'PropertyValue',
			'name'  => kbj_bedrijfsnaam(),
			'value' => (string) $id,
		),
	);

	$opleiding = kbj_vacature_veld( $id, 'opleiding' );

	if ( '' !== $opleiding ) {
		$knoop['qualifications'] = $opleiding;
	}

	$uren = kbj_vacature_veld( $id, 'uren' );

	if ( '' !== $uren ) {
		$knoop['workHours'] = $uren;
	}

	$sluit = kbj_vacature_veld( $id, 'sluitdatum' );

	if ( '' !== $sluit ) {
		$knoop['validThrough'] = $sluit . 'T23:59:59+02:00';
	}

	return $knoop;
}

/**
 * De vacatures die KomBij nu heeft, voor een verse installatie.
 *
 * Overgenomen uit de pdf's van de vorige site. Daarna beheert KomBij ze zelf.
 */
function kbj_installatie_vacatures() {
	if ( get_posts( array( 'post_type' => KBJ_VACATURE, 'post_status' => 'any', 'numberposts' => 1 ) ) ) {
		return;
	}

	$bieden = "Een goed salaris binnen de CAO VVT\nPensioen\nReiskostenvergoeding van € 0,19 per kilometer\nEindejaarsuitkering en 8% vakantiegeld\nToeslag voor onregelmatige diensten (ORT)";

	$vacatures = array(
		array(
			'titel'  => 'Verzorgende IG',
			'velden' => array(
				'uren'      => '24 tot 32 uur per week',
				'opleiding' => 'Afgeronde opleiding Verzorgende IG',
				'salaris'   => 'CAO VVT',
				'diensten'  => 'Wisselende diensten: dag, avond en nacht',
				'intro'     => 'Als Verzorgende IG ondersteun je onze bewoners en gasten bij hun dagelijkse zorg en welzijn. Je kent hun wensen, signaleert veranderingen en zorgt samen met je collega’s voor een fijne dag.',
				'taken'     => "Verpleegtechnische handelingen uitvoeren waarvoor je bevoegd bent\nZorgplannen opstellen, uitvoeren en evalueren\nBewoners begeleiden in hun dagelijks leven\nVeranderingen in de gezondheid signaleren\nContact houden met familie en andere zorgverleners\nWerken aan een veilige en gezellige leefomgeving",
				'eisen'     => "Een afgeronde opleiding Verzorgende IG\nErvaring met het toedienen van medicatie\nAffiniteit met ouderenzorg",
				'jij'       => 'Een zorgprofessional met een brede interesse en een stevig verantwoordelijkheidsgevoel. Je vindt het leuk om een gezellige dag te maken, past je makkelijk aan en werkt graag zelfstandig en in een team.',
				'bieden'    => $bieden,
			),
		),
		array(
			'titel'  => 'Verpleegkundige (hbo)',
			'velden' => array(
				'uren'      => '24 uur per week',
				'opleiding' => 'Hbo-verpleegkundige met BIG-registratie',
				'salaris'   => 'CAO VVT',
				'intro'     => 'Als verpleegkundige voer je uiteenlopende zorgtaken uit: medicatie, wondzorg, ADL en welzijn. Je stelt zorgplannen op, begeleidt bewoners en bent een vraagbaak voor je collega’s.',
				'taken'     => "Verpleegtechnische handelingen uitvoeren\nZorgplannen opstellen, uitvoeren en evalueren\nBewoners begeleiden in hun dagelijks leven\nVeranderingen in de gezondheid signaleren\nContact houden met familie en andere zorgverleners",
				'eisen'     => "Een afgeronde hbo-opleiding Verpleegkunde\nEen geldige BIG-registratie\nErvaring met het toedienen van medicatie",
				'jij'       => 'Een zorgprofessional met een brede interesse in welzijn en een stevig verantwoordelijkheidsgevoel. Je expertise ligt bij de ouderenzorg.',
				'bieden'    => "Een goed salaris binnen de CAO VVT\nPensioen\nReiskostenvergoeding van € 0,19 per kilometer\nEindejaarsuitkering en 8% vakantiegeld",
			),
		),
		array(
			'titel'  => 'Helpende Plus',
			'velden' => array(
				'uren'      => 'Vanaf 24 uur per week',
				'opleiding' => 'Afgeronde opleiding Helpende Zorg en Welzijn Plus',
				'salaris'   => 'CAO VVT, FWG 30',
				'diensten'  => 'Wisselende diensten: dag, avond en nacht',
				'intro'     => 'Als Helpende Plus ondersteun je bewoners bij hun dagelijkse zorg: verzorgen, aankleden en medicatie. Je helpt je collega’s en zorgt mee voor een fijne dag en een huiselijke sfeer.',
				'taken'     => "Bewoners helpen bij verzorging en aankleden\nMedicatie toedienen\nCollega’s ondersteunen bij verzorgende taken\nSignaleren wanneer iemand extra zorg nodig heeft\nMeedenken over een fijne invulling van de dag",
				'eisen'     => "Een afgeronde opleiding Helpende Zorg en Welzijn Plus\nErvaring met het toedienen van medicatie",
				'jij'       => 'Gastvrij, betrokken en flexibel. Je zet je graag in voor mensen met een zorgvraag, bijvoorbeeld met geheugenproblemen.',
				'bieden'    => $bieden,
			),
		),
		array(
			'titel'  => 'Nachtdienst',
			'velden' => array(
				'uren'      => 'Minimaal 24 uur per week',
				'opleiding' => 'Zorgdiploma, minimaal Helpende Plus',
				'salaris'   => 'CAO VVT',
				'diensten'  => 'Nachtdiensten',
				'intro'     => 'Als nachtdienst zorg je ervoor dat onze bewoners en gasten een rustige en veilige nacht hebben. Je houdt toezicht, helpt waar nodig en bent het vertrouwde gezicht in de nacht.',
				'taken'     => "Toezicht houden en rondes lopen\nOndersteunen bij zorgvragen en lichte ADL\nVeranderingen in welzijn signaleren\nRust en veiligheid bewaken\nBijzonderheden rapporteren\nLichte huishoudelijke taken",
				'eisen'     => "Een zorgdiploma, minimaal Helpende Plus\nErvaring in de ouderenzorg\nFlexibel inzetbaar in de nacht",
				'jij'       => 'Zorgzaam, betrouwbaar en zelfstandig. Je blijft rustig als er iets onverwachts gebeurt.',
				'bieden'    => "Werken in een warme, kleinschalige omgeving\nEen functie waarin jouw aanwezigheid echt verschil maakt\nEen salaris volgens de CAO VVT\nFijne collega’s en korte lijnen\nFlexibele inzet in overleg",
			),
		),
	);

	foreach ( $vacatures as $vacature ) {
		$id = wp_insert_post(
			array(
				'post_type'   => KBJ_VACATURE,
				'post_title'  => $vacature['titel'],
				'post_status' => 'publish',
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			kbj_vacature_velden_opslaan( $id, array_merge( array( 'dienstverband' => 'PART_TIME' ), $vacature['velden'] ) );
		}
	}
}

/**
 * De titel van een vacaturepagina, zoals mensen zoeken.
 *
 * "Verzorgende IG" zegt in Google weinig. "Vacature Verzorgende IG in
 * Maasbommel, 24 tot 32 uur" is precies waar iemand op zoekt en klikt.
 *
 * @param array $delen De delen van de titel.
 * @return array
 */
function kbj_vacature_titel( $delen ) {
	if ( ! is_singular( KBJ_VACATURE ) || kbj_seo_plugin_actief() ) {
		return $delen;
	}

	$id   = get_queried_object_id();
	$uren = kbj_vacature_veld( $id, 'uren' );

	// Midden in de titel: "Minimaal 24 uur" wordt "minimaal 24 uur".
	if ( '' !== $uren ) {
		$uren = mb_strtolower( mb_substr( $uren, 0, 1 ) ) . mb_substr( $uren, 1 );
	}

	$delen['title'] = sprintf(
		'Vacature %1$s in %2$s%3$s',
		get_the_title( $id ),
		kbj_optie( 'plaats', 'Maasbommel' ),
		'' !== $uren ? ', ' . $uren : ''
	);

	return $delen;
}
add_filter( 'document_title_parts', 'kbj_vacature_titel', 20 );

/**
 * De omschrijving van een vacaturepagina voor Google: het stukje "Over de
 * functie", met de plaats erbij.
 *
 * @param string $omschrijving De omschrijving die het thema maakte.
 * @return string
 */
function kbj_vacature_omschrijving( $omschrijving ) {
	if ( ! is_singular( KBJ_VACATURE ) ) {
		return $omschrijving;
	}

	$intro = kbj_vacature_veld( get_queried_object_id(), 'intro' );

	return '' !== $intro ? kbj_inkorten( 'Vacature bij KomBij in Maasbommel. ' . $intro, 155 ) : $omschrijving;
}
add_filter( 'kbj_omschrijving', 'kbj_vacature_omschrijving' );

/**
 * De strook boven de voet: "Kom jij ons team versterken?"
 *
 * Alleen als er vacatures open staan, en niet op de pagina Werken bij zelf of
 * op een vacature, want daar weet je het al.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_vacaturestrook( $attrs ) {
	$sectie = isset( $attrs['vorm'] ) && 'sectie' === $attrs['vorm'];

	// De strook boven de voet niet op de startpagina: daar staat de sectie al.
	if ( is_page( 'werken-bij' ) || is_singular( KBJ_VACATURE ) || ( ! $sectie && is_front_page() ) ) {
		return '';
	}

	$vacatures = kbj_vacatures_open();

	if ( ! $vacatures ) {
		if ( function_exists( 'kbj_in_editor' ) && kbj_in_editor() ) {
			return '<p class="kbj-editor-uitleg">' . esc_html__( 'Dit blok verschijnt vanzelf zodra er een vacature open staat. De teksten past u aan onder KomBij, Teksten op elke pagina.', 'kombij' ) . '</p>';
		}

		return '';
	}

	// De teksten staan onder Gegevens, zodat de eigenaren ze zelf aanpassen.
	$kop  = kbj_kombij( esc_html( kbj_tekst( 'werken_kop' ) ) );
	$zin  = kbj_tekst( 'werken_tekst' );
	$mail = kbj_tekst( 'werken_mail' );

	if ( $sectie ) {
		return sprintf(
			'<section %1$s><div class="kbj-werken__binnen">' . kbj_illustratie( 'maas', 'kbj-tekening-maas' ) . '<div><h2 class="kbj-schuif">%5$s</h2><p>%2$s</p><div class="kbj-acties"><a class="kbj-knop" href="%3$s">Bekijk de vacatures</a><a class="kbj-knop kbj-knop--rand" href="mailto:%6$s">Stuur een open sollicitatie</a></div></div>%4$s</div></section>',
			get_block_wrapper_attributes( array( 'class' => 'kbj-werken alignwide' ) ),
			esc_html( $zin ),
			esc_url( home_url( '/werken-bij/' ) ),
			kbj_render_beeld(
				array(
					'bestand'    => 'schip.webp',
					'alt'        => 'De lichte ruimte met de nieuwe tussenverdieping',
					'vorm'       => 'recht',
					'verhouding' => '4/3',
				)
			),
			$kop,
			esc_attr( $mail )
		);
	}

	return sprintf(
		'<aside %1$s><div class="kbj-vacaturestrook__binnen"><p class="kbj-vacaturestrook__kop">%4$s</p><p class="kbj-vacaturestrook__tekst">%2$s</p><a class="kbj-knop kbj-knop--wit" href="%3$s">Bekijk de vacatures</a></div></aside>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-vacaturestrook' ) ),
		esc_html( $zin ),
		esc_url( home_url( '/werken-bij/' ) ),
		$kop
	);
}

/**
 * Het WhatsApp-icoon, in de kleur van de tekst.
 *
 * @return string
 */
function kbj_icoon_whatsapp() {
	return '<svg class="kbj-deel__icoon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm0 18.15a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.23 8.23 0 1 1 6.98 3.86Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z"/></svg>';
}
