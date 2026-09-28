<?php
/**
 * Het contactformulier.
 *
 * Zonder plugin, want een formulier is hier één ding: een bericht dat in de mail
 * moet komen. Alles wat een plugin daaromheen bouwt (opslag, statistieken, een
 * eigen beheerscherm) hoeft niet en kost alleen maar snelheid.
 *
 * Het formulier is een blok en geen stukje HTML in een pagina, omdat WordPress
 * bij het opslaan formulieren uit de inhoud filtert. Als blok wordt het pas bij
 * het tonen gemaakt en kan er niets meer weg.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Naar welk adres de berichten gaan.
 *
 * @return string
 */
function cjt_contact_ontvanger() {
	$adres = cjt_optie( 'email' );

	if ( '' === $adres || ! is_email( $adres ) ) {
		$adres = (string) get_option( 'admin_email' );
	}

	return $adres;
}

/**
 * De velden van het formulier.
 *
 * Pas dit aan op wat de klant echt nodig heeft om te kunnen antwoorden. Vraag
 * niet meer dan dat: elk extra veld kost invullers.
 *
 * Volgorde: naam, [id, label, soort, verplicht, autocomplete].
 *
 * @return array[]
 */
function cjt_contact_velden() {
	return array(
		array( 'naam', __( 'Je naam', 'creajt-starter' ), 'text', true, 'name' ),
		array( 'email', __( 'E-mailadres', 'creajt-starter' ), 'email', true, 'email' ),
		array( 'telefoon', __( 'Telefoonnummer', 'creajt-starter' ), 'tel', false, 'tel' ),
		array( 'bedrijf', __( 'Bedrijf', 'creajt-starter' ), 'text', false, 'organization' ),
	);
}

/**
 * Het formulier zelf.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_contactformulier( $attrs ) {
	$fout = isset( $_GET['cjt-fout'] ) ? sanitize_key( wp_unslash( $_GET['cjt-fout'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$meldingen = array(
		'velden' => __( 'Vul nog even je naam, een e-mailadres en een bericht in.', 'creajt-starter' ),
		'email'  => __( 'Dat e-mailadres klopt niet helemaal, wil je het nog een keer proberen?', 'creajt-starter' ),
		'mail'   => __( 'Het versturen lukte niet. Mail gerust rechtstreeks, dan komt het alsnog goed.', 'creajt-starter' ),
	);

	$melding = isset( $meldingen[ $fout ] )
		? '<p class="cjt-formulier__fout" role="alert">' . esc_html( $meldingen[ $fout ] ) . '</p>'
		: '';

	/*
	 * Een sterretje bij de velden die we echt nodig hebben, en verder niets.
	 * Het sterretje is alleen om naar te kijken; een schermlezer krijgt het
	 * "required" op het veld zelf te horen en hoeft geen sterretjes op te
	 * sommen.
	 */
	$ster   = '<span class="cjt-formulier__ster" aria-hidden="true">*</span>';
	$velden = '';

	foreach ( cjt_contact_velden() as $veld ) {
		list( $naam, $label, $soort, $verplicht, $auto ) = $veld;

		$velden .= sprintf(
			'<p class="cjt-formulier__veld"><label for="cjt-%1$s">%2$s%6$s</label>'
				. '<input type="%3$s" id="cjt-%1$s" name="%1$s"%4$s%5$s></p>',
			esc_attr( $naam ),
			esc_html( $label ),
			esc_attr( $soort ),
			$verplicht ? ' required' : '',
			'' !== $auto ? ' autocomplete="' . esc_attr( $auto ) . '"' : '',
			$verplicht ? $ster : ''
		);
	}

	return sprintf(
		'<div %1$s>%2$s<form class="cjt-formulier" method="post" action="%3$s">'
			. '<input type="hidden" name="action" value="cjt_contact">'
			. '%4$s'
			. '%5$s'
			. '<p class="cjt-formulier__veld cjt-formulier__veld--breed">'
				. '<label for="cjt-bericht">%6$s<span class="cjt-formulier__ster" aria-hidden="true">*</span></label>'
				. '<textarea id="cjt-bericht" name="bericht" rows="6" required></textarea></p>'
			// Het onzichtbare veld: bots vullen alles in, mensen zien dit niet eens.
			. '<p class="cjt-formulier__val" aria-hidden="true"><label for="cjt-website">%7$s</label>'
				. '<input type="text" id="cjt-website" name="website" tabindex="-1" autocomplete="off"></p>'
			. '<p class="cjt-formulier__verzend"><button type="submit" class="cjt-knop">%8$s</button></p>'
			. '<p class="cjt-formulier__klein"><span aria-hidden="true">*</span> %9$s<br>%10$s</p>'
		. '</form></div>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-formulier-wrap cjt-reveal' ) ),
		$melding,
		esc_url( admin_url( 'admin-post.php' ) ),
		wp_nonce_field( 'cjt_contact', 'cjt_nonce', true, false ),
		$velden,
		esc_html__( 'Waar gaat het over?', 'creajt-starter' ),
		esc_html__( 'Laat dit veld leeg', 'creajt-starter' ),
		esc_html__( 'Versturen', 'creajt-starter' ),
		esc_html__( 'Deze heb ik nodig, de rest mag je overslaan.', 'creajt-starter' ),
		esc_html__( 'Wat je hier invult gebruik ik alleen om te antwoorden. Ik deel het met niemand.', 'creajt-starter' )
	);
}

/**
 * Het verwerken van een verstuurd formulier.
 */
function cjt_contact_verwerken() {
	$terug = wp_get_referer();

	if ( ! $terug ) {
		$terug = home_url( '/contact/' );
	}

	/** Stuurt de bezoeker terug met een melding erbij. */
	$stop = function ( $reden ) use ( $terug ) {
		wp_safe_redirect( add_query_arg( 'cjt-fout', $reden, $terug ) . '#contact' );
		exit;
	};

	if ( ! isset( $_POST['cjt_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['cjt_nonce'] ) ), 'cjt_contact' ) ) {
		$stop( 'velden' );
	}

	// Het onzichtbare veld is ingevuld: dat is geen mens.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( home_url( '/bedankt/' ) );
		exit;
	}

	/*
	 * Een slot op de deur. De nonce en het onzichtbare veld houden de meeste
	 * bots tegen, maar niet iemand die het formulier één keer met de hand
	 * bekijkt en daarna in een lus zet. Drie berichten per kwartier vanaf
	 * hetzelfde adres is ruim voor iemand die zich bedenkt, en te weinig om een
	 * postvak mee vol te gooien.
	 *
	 * Wie erboven komt krijgt hetzelfde bedankje te zien als iemand die wel
	 * doorkomt: een bot die een foutmelding krijgt gaat het opnieuw proberen.
	 */
	$adres = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	if ( '' !== $adres ) {
		$slot   = 'cjt_post_' . md5( $adres );
		$aantal = (int) get_transient( $slot );

		if ( $aantal >= 3 ) {
			wp_safe_redirect( home_url( '/bedankt/' ) );
			exit;
		}

		set_transient( $slot, $aantal + 1, 15 * MINUTE_IN_SECONDS );
	}

	$waarde = function ( $naam ) {
		return isset( $_POST[ $naam ] ) ? sanitize_text_field( wp_unslash( $_POST[ $naam ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	};

	$naam    = $waarde( 'naam' );
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$bericht = isset( $_POST['bericht'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bericht'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( '' === $naam || '' === $email || '' === $bericht ) {
		$stop( 'velden' );
	}

	if ( ! is_email( $email ) ) {
		$stop( 'email' );
	}

	$tekst = '';

	foreach ( cjt_contact_velden() as $veld ) {
		$inhoud = 'email' === $veld[0] ? $email : $waarde( $veld[0] );

		if ( '' === $inhoud ) {
			continue;
		}

		$tekst .= $veld[1] . ': ' . $inhoud . "\n";
	}

	$tekst .= "\n" . $bericht . "\n";

	$gelukt = wp_mail(
		cjt_contact_ontvanger(),
		sprintf(
			/* translators: %s: de naam van de afzender. */
			__( 'Bericht via de site: %s', 'creajt-starter' ),
			$naam
		),
		$tekst,
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $naam . ' <' . $email . '>',
		)
	);

	if ( ! $gelukt ) {
		$stop( 'mail' );
	}

	wp_safe_redirect( home_url( '/bedankt/' ) );
	exit;
}
add_action( 'admin_post_nopriv_cjt_contact', 'cjt_contact_verwerken' );
add_action( 'admin_post_cjt_contact', 'cjt_contact_verwerken' );
