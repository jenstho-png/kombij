<?php
/**
 * CREAjt Starter, themabestand.
 *
 * De vormgeving zit in theme.json en in de blokpatronen. Dit bestand doet
 * alleen wat vormgeving niet kan: de dynamische blokken registreren, de
 * pagina's klaarzetten en de SEO-gegevens meegeven.
 *
 * Nieuw project? Zoek en vervang in de hele map:
 *   creajt-starter  ->  naam-van-de-klant
 *   CJT_            ->  XXX_
 *   cjt_            ->  xxx_
 *   cjt/            ->  xxx/
 *   cjt-            ->  xxx-      (ook in de CSS en de JS)
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CJT_VERSION', '0.1.0' );
define( 'CJT_DIR', get_theme_file_path() );
define( 'CJT_URI', get_theme_file_uri() );

require_once CJT_DIR . '/inc/helpers.php';
require_once CJT_DIR . '/inc/setup.php';
require_once CJT_DIR . '/inc/fonts.php';
require_once CJT_DIR . '/inc/opties.php';
require_once CJT_DIR . '/inc/menu.php';
require_once CJT_DIR . '/inc/blocks.php';
require_once CJT_DIR . '/inc/paginas.php';
require_once CJT_DIR . '/inc/installatie.php';
require_once CJT_DIR . '/inc/formulier.php';
require_once CJT_DIR . '/inc/seo.php';
