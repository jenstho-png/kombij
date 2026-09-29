<?php
/**
 * KomBij Maasbommel, themabestand. Gebouwd op het startpakket van CREAjt.
 *
 * De vormgeving zit in theme.json en in de blokpatronen. Dit bestand doet
 * alleen wat vormgeving niet kan: de dynamische blokken registreren, de
 * pagina's klaarzetten en de SEO-gegevens meegeven.
 *
 * Nieuw project? Zoek en vervang in de hele map:
 *   kombij  ->  naam-van-de-klant
 *   KBJ_            ->  XXX_
 *   kbj_            ->  xxx_
 *   kbj/            ->  xxx/
 *   kbj-            ->  xxx-      (ook in de CSS en de JS)
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KBJ_VERSION', '1.6.2' );
define( 'KBJ_DIR', get_theme_file_path() );
define( 'KBJ_URI', get_theme_file_uri() );

require_once KBJ_DIR . '/inc/helpers.php';
require_once KBJ_DIR . '/inc/setup.php';
require_once KBJ_DIR . '/inc/fonts.php';
require_once KBJ_DIR . '/inc/opties.php';
require_once KBJ_DIR . '/inc/patronen.php';
require_once KBJ_DIR . '/inc/menu.php';
require_once KBJ_DIR . '/inc/blocks.php';
require_once KBJ_DIR . '/inc/paginas.php';
require_once KBJ_DIR . '/inc/vacatures.php';
require_once KBJ_DIR . '/inc/installatie.php';
require_once KBJ_DIR . '/inc/formulier.php';
require_once KBJ_DIR . '/inc/seo.php';
require_once KBJ_DIR . '/inc/beveiliging.php';
require_once KBJ_DIR . '/inc/melding.php';
require_once KBJ_DIR . '/inc/bordje.php';
