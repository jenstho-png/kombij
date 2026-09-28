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
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Naar welk adres de berichten gaan.
 *
 * @return string
 */
function kbj_contact_ontvanger() {
	$adres = kbj_optie( 'email' );

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
function kbj_contact_velden() {
	return array(
		array( 'naam', __( 'Je naam', 'kombij' ), 'text', true, 'name' ),
		array( 'email', __( 'E-mailadres', 'kombij' ), 'email', true, 'email' ),
		array( 'telefoon', __( 'Telefoonnummer', 'kombij' ), 'tel', false, 'tel' ),
		array( 'bedrijf', __( 'Bedrijf', 'kombij' ), 'text', false, 'organization' ),
	);
}

/**
 * Het formulier zelf.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_contactformulier( $attrs ) {
	$fout = isset( $_GET['kbj-fout'] ) ? sanitize_key( wp_unslash( $_GET['kbj-fout'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$meldingen = array(
		'velden' => __( 'Vul nog even je naam, een e-mailadres en een bericht in.', 'kombij' ),
		'email'  => __( 'Dat e-mailadres klopt niet helemaal, wil je het nog een keer proberen?', 'kombij' ),
		'mail'   => __( 'Het versturen lukte niet. Mail gerust rechtstreeks, dan komt het alsnog goed.', 'kombij' ),
	);

	$melding = isset( $meldingen[ $fout ] )
		? '<p class="kbj-formulier__fout" role="alert">' . esc_html( $meldingen[ $fout ] ) . '</p>'
		: '';

	/*
	 * Een sterretje bij de velden die we echt nodig hebben, en verder niets.
	 * Het sterretje is alleen om naar te kijken; een schermlezer krijgt het
	 * "required" op het veld zelf te horen en hoeft geen sterretjes op te
	 * sommen.
	 */
	$ster   = '<span class="kbj-formulier__ster" aria-hidden="true">*</span>';
	$velden = '';

	foreach ( kbj_contact_velden() as $veld ) {
		list( $naam, $label, $soort, $verplicht, $auto ) = $veld;

		$velden .= sprintf(
			'<p class="kbj-formulier__veld"><label for="kbj-%1$s">%2$s%6$s</label>'
				. '<input type="%3$s" id="kbj-%1$s" name="%1$s"%4$s%5$s></p>',
			esc_attr( $naam ),
			esc_html( $label ),
			esc_attr( $soort ),
			$verplicht ? ' required' : '',
			'' !== $auto ? ' autocomplete="' . esc_attr( $auto ) . '"' : '',
			$verplicht ? $ster : ''
		);
	}

	return sprintf(
		'<div %1$s>%2$s<form class="kbj-formulier" method="post" action="%3$s">'
			. '<input type="hidden" name="action" value="kbj_contact">'
			. '%4$s'
			. '%5$s'
			. '<p class="kbj-formulier__veld kbj-formulier__veld--breed">'
				. '<label for="kbj-bericht">%6$s<span class="kbj-formulier__ster" aria-hidden="true">*</span></label>'
				. '<textarea id="kbj-bericht" name="bericht" rows="6" required></textarea></p>'
			// Het onzichtbare veld: bots vullen alles in, mensen zien dit niet eens.
			. '<p class="kbj-formulier__val" aria-hidden="true"><label for="kbj-website">%7$s</label>'
				. '<input type="text" id="kbj-website" name="website" tabindex="-1" autocomplete="off"></p>'
			. '<p class="kbj-formulier__verzend"><button type="submit" class="kbj-knop">%8$s</button></p>'
			. '<p class="kbj-formulier__klein"><span aria-hidden="true">*</span> %9$s<br>%10$s</p>'
		. '</form></div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-formulier-wrap kbj-reveal' ) ),
		$melding,
		esc_url( admin_url( 'admin-post.php' ) ),
		wp_nonce_field( 'kbj_contact', 'kbj_nonce', true, false ),
		$velden,
		esc_html__( 'Waar gaat het over?', 'kombij' ),
		esc_html__( 'Laat dit veld leeg', 'kombij' ),
		esc_html__( 'Versturen', 'kombij' ),
		esc_html__( 'Deze heb ik nodig, de rest mag je overslaan.', 'kombij' ),
		esc_html__( 'Wat je hier invult gebruik ik alleen om te antwoorden. Ik deel het met niemand.', 'kombij' )
	);
}

/**
 * Het verwerken van een verstuurd formulier.
 */
function kbj_contact_verwerken() {
	$terug = wp_get_referer();

	if ( ! $terug ) {
		$terug = home_url( '/contact/' );
	}

	/** Stuurt de bezoeker terug met een melding erbij. */
	$stop = function ( $reden ) use ( $terug ) {
		wp_safe_redirect( add_query_arg( 'kbj-fout', $reden, $terug ) . '#contact' );
		exit;
	};

	if ( ! isset( $_POST['kbj_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['kbj_nonce'] ) ), 'kbj_contact' ) ) {
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
		$slot   = 'kbj_post_' . md5( $adres );
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

	foreach ( kbj_contact_velden() as $veld ) {
		$inhoud = 'email' === $veld[0] ? $email : $waarde( $veld[0] );

		if ( '' === $inhoud ) {
			continue;
		}

		$tekst .= $veld[1] . ': ' . $inhoud . "\n";
	}

	$tekst .= "\n" . $bericht . "\n";

	$gelukt = wp_mail(
		kbj_contact_ontvanger(),
		sprintf(
			/* translators: %s: de naam van de afzender. */
			__( 'Bericht via de site: %s', 'kombij' ),
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
add_action( 'admin_post_nopriv_kbj_contact', 'kbj_contact_verwerken' );
add_action( 'admin_post_kbj_contact', 'kbj_contact_verwerken' );
