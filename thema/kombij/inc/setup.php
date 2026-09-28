<?php
/**
 * Themaondersteuning, stylesheet en scripts.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wat het thema ondersteunt.
 */
function kbj_setup() {
	load_theme_textdomain( 'kombij', KBJ_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );

	/*
	 * De redactie ziet in de editor dezelfde opmaak als op de site. De tweede
	 * stylesheet zet de beginstanden van alle beweging terug op zichtbaar:
	 * zonder dat kijk je in de editor naar een lege pagina, want op de site
	 * komt bijna alles pas in beeld als je gaat scrollen.
	 */
	add_editor_style( array( 'assets/css/thema.css', 'assets/css/editor.css' ) );

	add_image_size( 'kbj-kaart', 800, 450, true );
	add_image_size( 'kbj-breed', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'kbj_setup' );

/**
 * Versienummer van één bestand, voor de cache van de browser.
 *
 * Het themaversienummer verandert niet bij elke aanpassing, en dan blijft de
 * browser de oude stylesheet gebruiken. De wijzigingsdatum van het bestand zelf
 * verandert wel altijd, dus die gebruiken we.
 *
 * @param string $pad Pad binnen het thema, bijvoorbeeld /assets/css/thema.css.
 * @return string
 */
function kbj_versie( $pad ) {
	$bestand = KBJ_DIR . $pad;

	if ( file_exists( $bestand ) ) {
		return (string) filemtime( $bestand );
	}

	return KBJ_VERSION;
}

/**
 * Het pad naar een bestand, met de kleine kopie als die klaarstaat.
 *
 * Naast elk bronbestand kan een .min-versie staan zonder commentaar en zonder
 * overbodige witruimte (zie gereedschap/verklein.py). Die sturen we naar de
 * bezoeker. De kopie telt alleen mee als hij nieuwer is dan de bron, dus
 * vergeet je het verkleinen, dan valt de site vanzelf terug op het bronbestand
 * en zie je je wijziging gewoon.
 *
 * @param string $pad Pad binnen het thema.
 * @return string
 */
function kbj_klein( $pad ) {
	$klein = preg_replace( '/\.(css|js)$/', '.min.$1', $pad );

	if ( $klein === $pad ) {
		return $pad;
	}

	$bron  = KBJ_DIR . $pad;
	$kopie = KBJ_DIR . $klein;

	if ( ! file_exists( $kopie ) || ! file_exists( $bron ) ) {
		return $pad;
	}

	return filemtime( $kopie ) >= filemtime( $bron ) ? $klein : $pad;
}

/**
 * Een regeltje in de broncode met de themaversie.
 *
 * Zo is in één blik te zien welke versie er draait, zonder in te loggen.
 */
function kbj_versiemerk() {
	echo "\n<!-- " . esc_html( wp_get_theme()->get( 'Name' ) . ' ' . KBJ_VERSION ) . " -->\n";
}
add_action( 'wp_head', 'kbj_versiemerk', 1 );

/**
 * De scripts die het thema meebrengt.
 *
 * Alles met defer: ze hebben de HTML nodig die er al staat, en niets op de
 * pagina wacht op ze.
 *
 * @return string[]
 */
function kbj_scripts() {
	return array( 'reveal', 'koptekst', 'teller', 'melding', 'video-facade', 'lichtbak' );
}

/**
 * Stylesheet en scripts voor de voorkant.
 */
function kbj_assets() {
	/*
	 * De stylesheet gaat mee in de pagina zelf en niet als een apart bestand.
	 *
	 * Een los bestand betekent dat de browser eerst de pagina ophaalt, daarin de
	 * verwijzing vindt, en dan nog een keer op pad moet voordat hij iets mag
	 * tekenen. Op een telefoon met een matige verbinding is dat een halve
	 * seconde leeg scherm. Zo staat de opmaak er bij de eerste byte.
	 *
	 * Het kost wat: de opmaak zit in elke pagina in plaats van één keer in de
	 * cache. Ingepakt is dat zo'n twintig kilobyte, minder dan één foto.
	 *
	 * Lukt het lezen niet, dan gaat het gewoon weer als bestand. Beter een
	 * trage pagina dan een pagina zonder opmaak.
	 */
	$pad   = kbj_klein( '/assets/css/thema.css' );
	$stijl = is_readable( KBJ_DIR . $pad ) ? file_get_contents( KBJ_DIR . $pad ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( is_string( $stijl ) && '' !== $stijl ) {
		// Zonder bron drukt WordPress alleen de inline regels af, geen <link>.
		wp_register_style( 'kbj-thema', false, array(), KBJ_VERSION );
		wp_enqueue_style( 'kbj-thema' );
		wp_add_inline_style( 'kbj-thema', $stijl );
	} else {
		wp_enqueue_style( 'kbj-thema', KBJ_URI . $pad, array(), kbj_versie( $pad ) );
	}

	foreach ( kbj_scripts() as $naam ) {
		$script = kbj_klein( '/assets/js/' . $naam . '.js' );

		if ( ! file_exists( KBJ_DIR . $script ) ) {
			continue;
		}

		wp_enqueue_script(
			'kbj-' . $naam,
			KBJ_URI . $script,
			array(),
			kbj_versie( $script ),
			array( 'strategy' => 'defer' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'kbj_assets' );

/**
 * De klasse voor de beweging, meteen bovenaan de pagina.
 *
 * Zet het script die klasse pas als het geladen is, dan zie je de opening van
 * de pagina eerst staan en daarna nog een keer binnenkomen. Daarom zet dit
 * regeltje hem al voordat er iets getekend wordt.
 *
 * Het vangnet: komt het script om wat voor reden ook niet binnen, bijvoorbeeld
 * omdat een streng webfilter het tegenhoudt, dan gaat de klasse er na twee en
 * een halve seconde weer af. Dan staat alles gewoon in beeld, zonder beweging.
 */
function kbj_beweging_klasse() {
	if ( is_admin() ) {
		return;
	}

	echo "<script>(function(d,w){if(!w.matchMedia||w.matchMedia('(prefers-reduced-motion: reduce)').matches||!('IntersectionObserver' in w))return;d.documentElement.classList.add('kbj-js');setTimeout(function(){if(!w.kbjReveal)d.documentElement.classList.remove('kbj-js');},2500);})(document,window);</script>\n";
}
add_action( 'wp_head', 'kbj_beweging_klasse', 0 );

/**
 * De regels die een bestand uit het thema nodig hebben.
 *
 * De stylesheet staat in de pagina zelf, en dan wijst een relatief pad naar de
 * verkeerde map. Het ornament en de spitsboog komen daarom hier binnen, met het
 * volledige adres erbij.
 *
 * @return string
 */
function kbj_css_met_paden() {
	$map = KBJ_URI . '/assets/beeld/';

	return sprintf(
		':root{--kbj-ornament:url("%1$sornament-glasblauw.svg");--kbj-ornament-wit:url("%1$sornament-wit.svg");--kbj-ornament-kleur:url("%1$sornament.svg")}',
		esc_url_raw( $map )
	);
}

/**
 * Die regels aan de voorkant en in de editor meegeven.
 */
function kbj_css_met_paden_laden() {
	wp_add_inline_style( 'kbj-thema', kbj_css_met_paden() );
}
add_action( 'wp_enqueue_scripts', 'kbj_css_met_paden_laden', 21 );

/**
 * Eigen categorie in de patroon-kiezer, zodat de klant onze secties bij elkaar
 * ziet staan en niet tussen de honderd standaardpatronen hoeft te zoeken.
 */
function kbj_pattern_category() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'kombij',
		array(
			'label'       => wp_get_theme()->get( 'Name' ),
			'description' => __( 'De secties van de site, in de vormgeving van het thema.', 'kombij' ),
		)
	);
}
add_action( 'init', 'kbj_pattern_category' );

/**
 * Eigen categorie in de blok-kiezer, boven de standaardblokken.
 *
 * @param array $categorieen Bestaande categorieën.
 * @return array
 */
function kbj_block_category( $categorieen ) {
	array_unshift(
		$categorieen,
		array(
			'slug'  => 'kombij',
			'title' => wp_get_theme()->get( 'Name' ),
			'icon'  => null,
		)
	);

	return $categorieen;
}
add_filter( 'block_categories_all', 'kbj_block_category' );

/**
 * De standaardpatronen van WordPress.org uitzetten.
 *
 * Anders staan er honderden vreemde secties in de kiezer die niet bij de
 * huisstijl passen, en dat is precies waar de vormgeving mee uit elkaar valt.
 */
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/**
 * De lijst met blokken beperken tot wat we echt gebruiken.
 *
 * Minder keuze is hier een functie: de vormgeving blijft staan en de klant
 * hoeft niet te kiezen tussen tachtig blokken waarvan er zestig niet passen.
 *
 * @param bool|string[]           $toegestaan Toegestane blokken.
 * @param WP_Block_Editor_Context $context    Waar de editor draait.
 * @return bool|string[]
 */
function kbj_allowed_blocks( $toegestaan, $context ) {
	// In de site-editor alles toestaan: daar bouwen wij, niet de klant.
	if ( empty( $context->post ) ) {
		return $toegestaan;
	}

	return array(
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/gallery',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/buttons',
		'core/button',
		'core/columns',
		'core/column',
		'core/group',
		'core/spacer',
		'core/separator',
		'core/cover',
		'core/details',
		'core/embed',
		'core/pattern',
		'kbj/paginakop',
		'kbj/sfeerbeeld',
		'kbj/video-facade',
		'kbj/contactformulier',
		'kbj/logo',
		'kbj/beeld',
		'kbj/vacatures',
	);
}
add_filter( 'allowed_block_types_all', 'kbj_allowed_blocks', 10, 2 );

/**
 * Wat WordPress standaard meestuurt en deze site niet nodig heeft.
 *
 * Emoji-ondersteuning is een script plus een tekening voor elk plaatje-emoji,
 * en elk toestel tekent emoji zelf al. Hetzelfde geldt voor het insluitscript
 * en de oude stylesheet voor klassieke thema's. Samen scheelt dat een paar
 * verzoeken en zo'n 25 kB per pagina.
 */
function kbj_opruimen() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );

	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'kbj_opruimen' );

/**
 * Het insluitscript en de stylesheet voor klassieke thema's eruit.
 */
function kbj_scripts_opruimen() {
	wp_dequeue_script( 'wp-embed' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'kbj_scripts_opruimen', 100 );

/**
 * Het icoontje in de browsertab.
 *
 * WordPress zet zelf een site-icoon als je er een kiest onder Instellingen.
 * Doe je dat niet, dan staat er het standaardlogo van WordPress naast je
 * paginatitel, en dat is het eerste wat iemand van het merk ziet als hij een
 * tabblad terugzoekt. Daarom levert het thema er zelf een mee.
 *
 * Drie bestanden, want geen enkele browser neemt genoegen met één: de SVG voor
 * alles wat modern is, een PNG van 32 pixels voor de rest, en een van 180 voor
 * het icoontje op een telefoonscherm. Zet ze in assets/beeld.
 */
function kbj_icoon() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$map = KBJ_URI . '/assets/beeld/';

	foreach ( array( 'icoon-32.png' => 'icon', 'icoon.svg' => 'icon', 'icoon-180.png' => 'apple-touch-icon' ) as $bestand => $rel ) {
		if ( ! file_exists( KBJ_DIR . '/assets/beeld/' . $bestand ) ) {
			continue;
		}

		printf(
			"\n<link rel=\"%s\" href=\"%s\"%s>",
			esc_attr( $rel ),
			esc_url( $map . $bestand ),
			'icoon.svg' === $bestand ? ' type="image/svg+xml"' : ''
		);
	}

	printf( "\n<meta name=\"theme-color\" content=\"%s\">\n", '#0a2444' );
}
add_action( 'wp_head', 'kbj_icoon', 2 );
