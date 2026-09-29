<?php
/**
 * Het inlogscherm in de stijl van KomBij.
 *
 * Een foto van de kerk met een blauwe laag, het witte logo erboven en een wit
 * formulier. Zo ziet inloggen er vertrouwd uit in plaats van als WordPress.
 *
 * @package KomBij
 */

defined( 'ABSPATH' ) || exit;

/**
 * De stijl van het inlogscherm.
 */
function kbj_inloggen_stijl() {
	$fonts = function_exists( 'kbj_font_face_css' ) ? kbj_font_face_css() : '';
	$foto  = KBJ_URI . '/assets/foto/kerk-interieur.webp';
	$logo  = KBJ_URI . '/assets/beeld/logo-staand-wit.svg';
	?>
	<style>
		<?php echo $fonts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		body.login {
			background: linear-gradient(160deg, rgba(10, 36, 68, 0.9) 0%, rgba(27, 83, 129, 0.78) 55%, rgba(30, 124, 178, 0.55) 100%), url('<?php echo esc_url( $foto ); ?>') center / cover no-repeat fixed, #0a2444;
			font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
			color: #0a2444;
		}
		#login {
			width: min(360px, calc(100% - 32px));
			padding: 6vh 0 2rem;
		}
		body.login #login h1.wp-login-logo a {
			background-image: url('<?php echo esc_url( $logo ); ?>') !important;
			background-size: contain !important;
			background-position: center !important;
			background-repeat: no-repeat !important;
			width: 120px;
			height: 200px;
			margin: 0 auto 1.5rem;
		}
		.login form {
			border: 0;
			border-radius: 20px;
			padding: 28px 26px 30px;
			box-shadow: 0 24px 60px rgba(10, 36, 68, 0.35);
		}
		.login label {
			font-size: 14px;
			font-weight: 600;
			color: #0a2444;
		}
		.login input[type="text"],
		.login input[type="password"],
		.login input[type="email"] {
			min-height: 46px;
			border: 1px solid #c9d6e2;
			border-radius: 12px;
			font-size: 16px;
			padding: 6px 12px;
		}
		.login input[type="text"]:focus,
		.login input[type="password"]:focus,
		.login input[type="email"]:focus {
			border-color: #1b5381;
			box-shadow: 0 0 0 2px rgba(27, 83, 129, 0.25);
		}
		.login .button.wp-hide-pw {
			min-height: 46px;
			color: #1b5381;
		}
		body.login.wp-core-ui .button-primary {
			background: #1b5381;
			border-color: #1b5381;
			border-radius: 999px;
			min-height: 44px;
			padding: 0 22px;
			font-size: 15px;
			font-weight: 600;
			box-shadow: none;
			text-shadow: none;
		}
		body.login.wp-core-ui .button-primary:hover,
		body.login.wp-core-ui .button-primary:focus {
			background: #0a2444;
			border-color: #0a2444;
		}
		.login .forgetmenot label {
			font-weight: 400;
		}
		.login #nav,
		.login #backtoblog {
			text-align: center;
			padding: 0;
		}
		body.login #nav a,
		body.login #backtoblog a,
		body.login .privacy-policy-link,
		body.login .language-switcher label {
			color: #fff;
			font-size: 14px;
		}
		body.login #nav a:hover,
		body.login #backtoblog a:hover {
			color: #b0e1f1;
		}
		.login .message,
		.login .notice,
		.login .success,
		.login #login_error {
			border-radius: 12px;
			border-left-color: #1e7cb2;
		}
		.login #login_error {
			border-left-color: #d63638;
		}
		.language-switcher label .dashicons {
			color: #fff;
		}
		body.login .language-switcher .button {
			color: #fff;
			border-color: rgba(255, 255, 255, 0.6);
			background: transparent;
			min-height: 40px;
			border-radius: 999px;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'kbj_inloggen_stijl' );

/**
 * Het logo boven het formulier gaat naar de eigen site, niet naar wordpress.org.
 *
 * @return string
 */
function kbj_inloggen_link() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'kbj_inloggen_link' );

/**
 * De naam bij het logo, voor schermlezers.
 *
 * @return string
 */
function kbj_inloggen_naam() {
	return kbj_bedrijfsnaam();
}
add_filter( 'login_headertext', 'kbj_inloggen_naam' );
