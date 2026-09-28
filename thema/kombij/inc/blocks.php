<?php
/**
 * De blokken die het thema zelf tekent.
 *
 * Een blok is hier een stuk vormgeving dat de klant mag vullen maar niet mag
 * slopen: de opmaak zit in de code, de tekst en het beeld in de instellingen.
 * Daardoor kan er in de editor niets kapot en ziet elke sectie er over een jaar
 * nog net zo uit.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alle blokken registreren.
 *
 * Elk blok heeft een map in /blocks met een block.json erin. Die beschrijft de
 * velden; de functie hieronder tekent de HTML.
 */
function kbj_register_blocks() {
	$blokken = array(
		'header'           => 'kbj_render_header',
		'paginakop'        => 'kbj_render_paginakop',
		'sfeerbeeld'       => 'kbj_render_sfeerbeeld',
		'video-facade'     => 'kbj_render_video_facade',
		'contactformulier' => 'kbj_render_contactformulier',
		'contactgegevens'  => 'kbj_render_contactgegevens',
		'footermenu'       => 'kbj_render_footermenu',
		'colofon'          => 'kbj_render_colofon',
		'logo'             => 'kbj_render_logo',
		'beeld'            => 'kbj_render_beeld',
		'vacatures'        => 'kbj_render_vacatures',
		'vacature'         => 'kbj_render_vacature',
		'opening'          => 'kbj_render_opening',
		'raamlijn'         => 'kbj_render_raamlijn',
	);

	foreach ( $blokken as $naam => $functie ) {
		$map = KBJ_DIR . '/blocks/' . $naam;

		if ( ! file_exists( $map . '/block.json' ) ) {
			continue;
		}

		register_block_type( $map, array( 'render_callback' => $functie ) );
	}
}
add_action( 'init', 'kbj_register_blocks' );

/**
 * Het logo als SVG, zodat het zijn kleur van de tekst eromheen overneemt.
 *
 * Zet assets/beeld/logo.svg neer en haal er de vaste fill- en stroke-kleuren
 * uit (of zet ze op currentColor). Staat er geen SVG, dan geeft dit een lege
 * string terug en valt de rest terug op het sitelogo.
 *
 * @param string $klasse Extra klasse op het svg-element.
 * @return string
 */
function kbj_logo_svg( $klasse = '' ) {
	static $cache = null;

	if ( null === $cache ) {
		$bestand = KBJ_DIR . '/assets/beeld/logo.svg';
		$cache   = is_readable( $bestand ) ? (string) file_get_contents( $bestand ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	if ( '' === $cache ) {
		return '';
	}

	$svg = $cache;

	// Een XML-kop hoort niet in de pagina.
	$svg = preg_replace( '/<\?xml.*?\?>\s*/s', '', $svg );
	$svg = preg_replace( '/<!DOCTYPE.*?>\s*/s', '', $svg );

	// Een logo is versiering, geen informatie: de naam staat in de link eromheen.
	if ( false === strpos( $svg, 'aria-hidden' ) ) {
		$svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true" focusable="false"', $svg, 1 );
	}

	if ( '' !== $klasse ) {
		$svg = preg_replace( '/<svg\b/', '<svg class="' . esc_attr( $klasse ) . '"', $svg, 1 );
	}

	return $svg;
}

/**
 * Het adres van een foto: uit de mediabibliotheek, of uit de map van het thema.
 *
 * De tijdelijke foto's staan in assets/foto. Komen de echte foto's binnen, dan
 * vervang je daar het bestand met dezelfde naam en staat hij meteen overal
 * goed, zonder één pagina te openen. Kiest de klant zelf een foto in de editor,
 * dan gaat die voor.
 *
 * @param array $attrs Blokinstellingen met 'url' en/of 'bestand'.
 * @return string
 */
function kbj_foto_url( $attrs ) {
	$url = isset( $attrs['url'] ) ? trim( (string) $attrs['url'] ) : '';

	if ( '' !== $url ) {
		return $url;
	}

	$bestand = isset( $attrs['bestand'] ) ? sanitize_file_name( (string) $attrs['bestand'] ) : '';

	if ( '' === $bestand || ! file_exists( KBJ_DIR . '/assets/foto/' . $bestand ) ) {
		return '';
	}

	return KBJ_URI . '/assets/foto/' . $bestand . '?v=' . filemtime( KBJ_DIR . '/assets/foto/' . $bestand );
}

/**
 * De maten van een foto uit de themamap, zodat de browser de ruimte vrijhoudt.
 *
 * @param array $attrs Blokinstellingen.
 * @return int[] Breedte en hoogte, of nullen.
 */
function kbj_foto_maat( $attrs ) {
	$bestand = isset( $attrs['bestand'] ) ? sanitize_file_name( (string) $attrs['bestand'] ) : '';
	$pad     = KBJ_DIR . '/assets/foto/' . $bestand;

	if ( '' === $bestand || ! empty( $attrs['url'] ) || ! file_exists( $pad ) ) {
		return array( 0, 0 );
	}

	$maat = wp_getimagesize( $pad );

	return $maat ? array( (int) $maat[0], (int) $maat[1] ) : array( 0, 0 );
}

/**
 * Een foto, recht of in de vorm van een spitsboog.
 *
 * De spitsboog komt uit de ramen van de Lambertuskerk. Achter de foto ligt een
 * tweede boog in glasblauw, iets verschoven, zoals de lood- en glaslagen in het
 * logo. Dat is het oude kantje in een verder strakke site.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_beeld( $attrs ) {
	$url        = kbj_foto_url( $attrs );
	$alt        = isset( $attrs['alt'] ) ? (string) $attrs['alt'] : '';
	$vorm       = isset( $attrs['vorm'] ) && 'recht' === $attrs['vorm'] ? 'recht' : 'boog';
	$verhouding = isset( $attrs['verhouding'] ) ? preg_replace( '#[^0-9/ .]#', '', (string) $attrs['verhouding'] ) : '4/5';
	$positie    = isset( $attrs['positie'] ) ? preg_replace( '#[^0-9% a-z.]#', '', (string) $attrs['positie'] ) : '50% 50%';
	$eerst      = ! empty( $attrs['eerst'] );
	$onder      = isset( $attrs['bijschrift'] ) ? (string) $attrs['bijschrift'] : '';
	$klassen    = 'kbj-beeld kbj-beeld--' . $vorm . ( $eerst ? '' : ' kbj-reveal' );

	if ( '' === $url ) {
		return sprintf(
			'<figure %1$s><div class="kbj-beeld__kader" style="aspect-ratio:%2$s"><div class="kbj-leeg"><span>%3$s</span></div></div></figure>',
			get_block_wrapper_attributes( array( 'class' => $klassen ) ),
			esc_attr( $verhouding ),
			esc_html__( 'Kies hier een foto', 'kombij' )
		);
	}

	list( $breedte, $hoogte ) = kbj_foto_maat( $attrs );

	return sprintf(
		'<figure %1$s><div class="kbj-beeld__kader" style="aspect-ratio:%2$s"><img src="%3$s" alt="%4$s"%5$s style="object-position:%6$s"%7$s decoding="async"></div>%8$s</figure>',
		get_block_wrapper_attributes( array( 'class' => $klassen ) ),
		esc_attr( $verhouding ),
		esc_url( $url ),
		esc_attr( $alt ),
		$breedte ? sprintf( ' width="%d" height="%d"', $breedte, $hoogte ) : '',
		esc_attr( $positie ),
		$eerst ? ' fetchpriority="high"' : ' loading="lazy"',
		'' !== $onder ? '<figcaption class="kbj-beeld__onder">' . esc_html( $onder ) . '</figcaption>' : ''
	);
}

/**
 * De kop van een binnenpagina: een regel erboven, de titel, een intro, de
 * stap naar contact en een foto in kerkvorm.
 *
 * Elke pagina moet op zichzelf werken voor wie via Google binnenkomt. Daarom
 * staat het telefoonnummer al in de kop, en niet pas onderaan.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_paginakop( $attrs ) {
	$titel = isset( $attrs['titel'] ) ? (string) $attrs['titel'] : '';
	$intro = isset( $attrs['intro'] ) ? (string) $attrs['intro'] : '';
	$knop  = ! isset( $attrs['knop'] ) || ! empty( $attrs['knop'] );

	if ( '' === $titel ) {
		$titel = (string) get_the_title();
	}

	$beeld = '';

	if ( '' !== kbj_foto_url( $attrs ) ) {
		$beeld = kbj_render_beeld(
			array(
				'url'        => isset( $attrs['url'] ) ? $attrs['url'] : '',
				'bestand'    => isset( $attrs['bestand'] ) ? $attrs['bestand'] : '',
				'alt'        => isset( $attrs['alt'] ) ? $attrs['alt'] : '',
				'positie'    => isset( $attrs['positie'] ) ? $attrs['positie'] : '50% 50%',
				'vorm'       => 'recht',
				'verhouding' => '4/5',
				'eerst'      => true,
			)
		);
	}

	$contact = get_page_by_path( 'contact' );
	$acties  = '';

	if ( $knop ) {
		$acties = sprintf(
			'<div class="kbj-acties"><a class="kbj-knop" href="%1$s">%2$s</a><a class="kbj-knop kbj-knop--rand" href="%3$s">%4$s</a></div>',
			esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
			esc_html__( 'Plan een rondleiding', 'kombij' ),
			esc_attr( kbj_tel_url() ),
			esc_html__( 'Bel ons', 'kombij' )
		);
	}

	return sprintf(
		'<header %1$s>%2$s<div class="kbj-paginakop__binnen"><div class="kbj-paginakop__tekst"><h1 class="kbj-paginakop__titel kbj-schuif">%3$s</h1>%4$s%5$s</div>%6$s</div></header>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-paginakop' . ( '' !== $beeld ? ' kbj-paginakop--beeld' : '' ) ) ),
		kbj_raamlijn(),
		esc_html( $titel ),
		'' !== $intro ? '<p class="kbj-paginakop__intro">' . wp_kses( $intro, array( 'strong' => array(), 'br' => array() ) ) . '</p>' : '',
		$acties,
		'' !== $beeld ? '<div class="kbj-paginakop__beeld">' . $beeld . '</div>' : ''
	);
}

/**
 * Een beeld over de volle breedte, met een bijschrift.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_sfeerbeeld( $attrs ) {
	$url      = isset( $attrs['url'] ) ? (string) $attrs['url'] : '';
	$alt      = isset( $attrs['alt'] ) ? (string) $attrs['alt'] : '';
	$onder    = isset( $attrs['bijschrift'] ) ? (string) $attrs['bijschrift'] : '';
	$hoogte   = isset( $attrs['hoogte'] ) ? (string) $attrs['hoogte'] : 'normaal';
	$klassen  = 'kbj-sfeerbeeld kbj-reveal kbj-sfeerbeeld--' . sanitize_html_class( $hoogte );

	if ( '' === $url ) {
		return sprintf(
			'<figure %1$s><div class="kbj-leeg"><span>%2$s</span></div></figure>',
			get_block_wrapper_attributes( array( 'class' => $klassen ) ),
			esc_html__( 'Kies hier een foto', 'kombij' )
		);
	}

	return sprintf(
		'<figure %1$s>%2$s%3$s</figure>',
		get_block_wrapper_attributes( array( 'class' => $klassen ) ),
		kbj_beeld( $url, $alt, '100vw', 'kbj-sfeerbeeld__beeld' ),
		'' !== $onder ? '<figcaption class="kbj-sfeerbeeld__onder">' . esc_html( $onder ) . '</figcaption>' : ''
	);
}

/**
 * Een video die pas laadt als iemand erop klikt.
 *
 * Een ingesloten YouTube- of Vimeo-speler is bijna een megabyte aan script,
 * ook als niemand hem aanzet. Daarom staat er eerst alleen de posterfoto met een
 * afspeelknop; pas bij de klik komt de echte speler erin. Dat scheelt op de
 * homepage het verschil tussen een snelle en een trage site.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_video_facade( $attrs ) {
	$invoer = isset( $attrs['video'] ) ? (string) $attrs['video'] : '';
	$bron   = kbj_video_bron( $invoer );

	if ( '' === $bron['id'] ) {
		return sprintf(
			'<div %1$s><div class="kbj-leeg"><span>%2$s</span></div></div>',
			get_block_wrapper_attributes( array( 'class' => 'kbj-video kbj-reveal' ) ),
			esc_html__( 'Plak hier de link naar de video', 'kombij' )
		);
	}

	$poster = isset( $attrs['poster'] ) ? (string) $attrs['poster'] : '';

	if ( '' === $poster ) {
		$poster = 'vimeo' === $bron['soort']
			? kbj_vimeo_poster( $bron['id'], $bron['sleutel'] )
			: sprintf( 'https://i.ytimg.com/vi/%s/maxresdefault.jpg', $bron['id'] );
	}

	$titel = isset( $attrs['titel'] ) ? (string) $attrs['titel'] : __( 'Bekijk de video', 'kombij' );

	return sprintf(
		'<div %1$s><kbj-video-facade soort="%2$s" video="%3$s" sleutel="%4$s" poster="%5$s" titel="%6$s"></kbj-video-facade></div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-video kbj-reveal' ) ),
		esc_attr( $bron['soort'] ),
		esc_attr( $bron['id'] ),
		esc_attr( $bron['sleutel'] ),
		esc_url( $poster ),
		esc_attr( $titel )
	);
}

/**
 * De contactgegevens, zoals ze onder Gegevens staan.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_contactgegevens( $attrs ) {
	$regels = array();

	$email = kbj_optie( 'email' );

	if ( '' !== $email ) {
		$regels[] = sprintf(
			'<a href="mailto:%1$s">%1$s</a>',
			esc_attr( $email )
		);
	}

	$telefoon = kbj_optie( 'telefoon' );

	if ( '' !== $telefoon ) {
		$regels[] = sprintf(
			'<a href="tel:%s">%s</a>',
			esc_attr( preg_replace( '/[^\d+]/', '', $telefoon ) ),
			esc_html( $telefoon )
		);
	}

	$straat = kbj_optie( 'straat' );
	$plaats = trim( kbj_optie( 'postcode' ) . ' ' . kbj_optie( 'plaats' ) );

	if ( '' !== $straat ) {
		$regels[] = '<span class="kbj-gegevens__adres">' . esc_html( $straat ) . '<br>' . esc_html( $plaats ) . '</span>';
	} elseif ( '' !== kbj_optie( 'plaats' ) ) {
		$regels[] = esc_html( kbj_optie( 'plaats' ) );
	}

	$sociaal = array();

	foreach ( array( 'instagram', 'facebook', 'linkedin', 'youtube' ) as $kanaal ) {
		$link = kbj_optie( $kanaal );

		if ( '' === $link ) {
			continue;
		}

		$sociaal[] = sprintf(
			'<a href="%s" rel="me noopener" target="_blank">%s</a>',
			esc_url( $link ),
			esc_html( ucfirst( $kanaal ) )
		);
	}

	if ( empty( $regels ) && empty( $sociaal ) ) {
		return '';
	}

	return sprintf(
		'<div %1$s>%2$s%3$s</div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-gegevens' ) ),
		$regels ? '<p class="kbj-gegevens__regels">' . implode( '<br>', $regels ) . '</p>' : '',
		$sociaal ? '<p class="kbj-gegevens__sociaal">' . implode( '<span aria-hidden="true"> / </span>', $sociaal ) . '</p>' : ''
	);
}

/**
 * Het menu in de voet: alle pagina's, inclusief de juridische.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_footermenu( $attrs ) {
	$items = array(
		array(
			'label' => __( 'Home', 'kombij' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( kbj_paginas() as $pagina ) {
		if ( 'bedankt' === $pagina['slug'] ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object ) {
			continue;
		}

		$label = $pagina['titel'];

		// Staan er vacatures open, dan zie je dat al in de voet.
		if ( 'werken-bij' === $pagina['slug'] && function_exists( 'kbj_vacatures_open' ) ) {
			$aantal = count( kbj_vacatures_open() );

			if ( $aantal ) {
				/* translators: %d: aantal open vacatures. */
				$label .= ' ' . sprintf( _n( '(%d vacature)', '(%d vacatures)', $aantal, 'kombij' ), $aantal );
			}
		}

		$items[] = array(
			'label' => $label,
			'url'   => (string) get_permalink( $object ),
		);
	}

	$privacy = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( $privacy && 'publish' === get_post_status( $privacy ) ) {
		$items[] = array(
			'label' => get_the_title( $privacy ),
			'url'   => (string) get_permalink( $privacy ),
		);
	}

	$html = '';

	foreach ( $items as $item ) {
		$html .= sprintf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}

	return sprintf(
		'<nav %1$s aria-label="%2$s"><ul class="kbj-footermenu__lijst">%3$s</ul></nav>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-footermenu' ) ),
		esc_attr__( 'Menu in de voet', 'kombij' ),
		$html
	);
}

/**
 * De regel helemaal onderaan: naam, jaartal, KvK.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_colofon( $attrs ) {
	$naam = kbj_optie( 'bedrijf', (string) get_bloginfo( 'name' ) );

	$delen = array(
		sprintf( '&copy; %s %s', esc_html( gmdate( 'Y' ) ), esc_html( $naam ) ),
	);

	$kvk = kbj_optie( 'kvk' );

	if ( '' !== $kvk ) {
		/* translators: %s: KvK-nummer. */
		$delen[] = esc_html( sprintf( __( 'KvK %s', 'kombij' ), $kvk ) );
	}

	$btw = kbj_optie( 'btw' );

	if ( '' !== $btw ) {
		/* translators: %s: btw-nummer. */
		$delen[] = esc_html( sprintf( __( 'Btw %s', 'kombij' ), $btw ) );
	}

	return sprintf(
		'<p %1$s>%2$s</p>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-colofon' ) ),
		implode( '<span aria-hidden="true"> &middot; </span>', $delen )
	);
}

/**
 * Het staande logo als los blok, voor de voet en voor losse secties.
 *
 * Op een donker vlak de witte versie uit het merkboek, anders de versie in
 * kleur. Beide zijn afbeeldingen: het logo mag niet hertint worden.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_logo( $attrs ) {
	$breedte = isset( $attrs['breedte'] ) ? (int) $attrs['breedte'] : 160;
	$breedte = max( 60, min( 480, $breedte ) );
	$licht   = ! empty( $attrs['licht'] );

	return sprintf(
		'<div %1$s style="--kbj-logo-breedte:%2$dpx"><img src="%3$s" alt="%4$s" width="233" height="389" loading="lazy" decoding="async"></div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-logo' ) ),
		$breedte,
		esc_url( KBJ_URI . '/assets/beeld/' . ( $licht ? 'logo-staand-wit.svg' : 'logo-staand.svg' ) ),
		esc_attr( kbj_optie( 'bedrijf', (string) get_bloginfo( 'name' ) ) )
	);
}

/**
 * Het raam van het gebouw als tekening, nagetekend van een van de ramen.
 *
 * Een spitsboog met een dubbele lijst, twee smalle vensters met een kop van
 * drie bogen, een ijzeren stang om de zoveel rijen, lood in achthoeken, een
 * blauwe strook langs de randen, en bovenin de vierpas met een ruit in het
 * midden en twee kleine lichten ernaast. Precies zoals het echte raam.
 *
 * Eerst zetten de lijnen zich, dan licht het glas op in de kleuren van het
 * raam, en daarna trekt er af en toe een streep zonlicht overheen.
 *
 * @param string $klasse Extra klasse.
 * @return string
 */
function kbj_raamlijn( $klasse = '' ) {
	static $nummer = 0;
	++$nummer;

	$id = 'kbj-raam-' . $nummer;

	// Het glas van één venster: recht omhoog, dan de kop van drie bogen.
	$venster = function ( $links ) {
		$rechts = $links + 56;
		$midden = $links + 28;

		return sprintf(
			'M%1$d 350V166A11 11 0 0 1 %2$s 150A34 34 0 0 1 %3$d 127A34 34 0 0 1 %4$s 150A11 11 0 0 1 %5$d 166V350Z',
			$links,
			$links + 9,
			$midden,
			$rechts - 9,
			$rechts
		);
	};

	$glas_links  = $venster( 34 );
	$glas_rechts = $venster( 110 );
	$roos        = 'M83.1 61.1A17 17 0 1 1 116.9 61.1A17 17 0 1 1 116.9 94.9A17 17 0 1 1 83.1 94.9A17 17 0 1 1 83.1 61.1Z';
	$licht_links  = 'M25 150C27 126 38 106 57 92C57 110 50 124 38 140Z';
	$licht_rechts = 'M175 150C173 126 162 106 143 92C143 110 150 124 162 140Z';
	$licht_midden = 'M91 127Q100 123 109 127Q102 136 100 150Q98 136 91 127Z';

	$lijnen = array(
		// De lijst om het raam, dubbel.
		'M10 352V150A150 150 0 0 1 100 12.5A150 150 0 0 1 190 150V352',
		'M18 352V150A142 142 0 0 1 100 21.3A142 142 0 0 1 182 150V352',
		// De twee vensters.
		$glas_links,
		$glas_rechts,
		// De stenen stijl ertussen en de lijst om de vensters.
		'M30 352V170A64 64 0 0 1 62 114.6A64 64 0 0 1 94 170V352',
		'M106 352V170A64 64 0 0 1 138 114.6A64 64 0 0 1 170 170V352',
		// De vierpas met zijn lijst.
		'M79.1 57.1A21 21 0 1 1 120.9 57.1A21 21 0 1 1 120.9 98.9A21 21 0 1 1 79.1 98.9A21 21 0 1 1 79.1 57.1Z',
		$roos,
		// Het lood in de vierpas: een ruit in het midden, lijnen naar de lobben,
		// en een boog in elke lob.
		'M100 66L112 78L100 90L88 78Z',
		'M100 42V66M100 90V114M64 78H88M112 78H136',
		'M89.2 60A11 11 0 0 1 110.8 60M120 67.2A11 11 0 0 1 120 88.8M110.8 96A11 11 0 0 1 89.2 96M80 88.8A11 11 0 0 1 80 67.2',
		// De kleine lichten naast en onder de vierpas.
		$licht_links,
		$licht_rechts,
		$licht_midden,
		// De ijzeren stangen dwars door het glas.
		'M34 196H90M110 196H166',
		'M34 240H90M110 240H166',
		'M34 284H90M110 284H166',
		'M34 328H90M110 328H166',
		// De vensterbank.
		'M2 352H198M6 358H194',
	);

	$html = '';

	foreach ( $lijnen as $i => $d ) {
		$html .= sprintf( '<path class="kbj-raam-lijn" d="%1$s" pathLength="1" style="--i:%2$d"/>', $d, $i );
	}

	// Het glas, dat na de lijnen oplicht.
	$glas = '';

	foreach ( array( $glas_links, $glas_rechts, $roos, $licht_links, $licht_rechts, $licht_midden ) as $i => $d ) {
		$glas .= sprintf( '<path class="kbj-raam-glas" d="%1$s" style="--i:%2$d"/>', $d, $i );
		$glas .= sprintf( '<path class="kbj-raam-lood" d="%1$s" fill="url(#%2$s-lood)" style="--i:%3$d"/>', $d, $id, $i );
	}

	// De blauwe stroken langs de randen van de vensters, en blauw in de lobben.
	$blauw = '';

	foreach ( array( 36, 88, 112, 164 ) as $i => $x ) {
		$blauw .= sprintf( '<path class="kbj-raam-strook" d="M%1$d 172V350" style="--i:%2$d"/>', $x, $i );
	}

	$blauw .= '<path class="kbj-raam-blauw" d="M89.2 60A11 11 0 0 1 110.8 60L106 60A6 6 0 0 0 94 60ZM120 67.2A11 11 0 0 1 120 88.8L120 84A6 6 0 0 0 120 72ZM110.8 96A11 11 0 0 1 89.2 96L94 96A6 6 0 0 0 106 96ZM80 88.8A11 11 0 0 1 80 67.2L80 72A6 6 0 0 0 80 84Z" style="--i:4"/>';

	// Een streep zonlicht die af en toe over het glas trekt.
	$glans = sprintf(
		'<g clip-path="url(#%1$s-glas)"><rect class="kbj-raam-zon" x="-80" y="0" width="70" height="360" fill="url(#%1$s-zon)"/></g>',
		$id
	);

	$defs = sprintf(
		'<defs>'
			. '<pattern id="%1$s-lood" width="9" height="9" patternUnits="userSpaceOnUse"><path d="M2.64 0h3.72L9 2.64v3.72L6.36 9H2.64L0 6.36V2.64z" fill="none" stroke="currentColor" stroke-width=".25"/></pattern>'
			. '<linearGradient id="%1$s-zon" x1="0" x2="1" y1="0" y2="0"><stop offset="0" stop-color="#fff" stop-opacity="0"/><stop offset=".5" stop-color="#fff" stop-opacity=".55"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></linearGradient>'
			. '<clipPath id="%1$s-glas"><path d="%2$s"/><path d="%3$s"/><path d="%4$s"/></clipPath>'
		. '</defs>',
		$id,
		$glas_links,
		$glas_rechts,
		$roos
	);

	return sprintf(
		'<svg class="kbj-raamlijn %1$s" viewBox="0 0 200 360" aria-hidden="true" focusable="false">%2$s<g class="kbj-raam-vulling">%3$s%4$s</g>%5$s<g class="kbj-raam-lijnen">%6$s</g></svg>',
		esc_attr( $klasse ),
		$defs,
		$glas,
		$blauw,
		$glans,
		$html
	);
}

/**
 * Het raam als los blok, zodat het in een pagina kan staan zonder dat
 * WordPress de tekening bij het opslaan wegfiltert.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_raamlijn( $attrs ) {
	return kbj_raamlijn( isset( $attrs['className'] ) ? (string) $attrs['className'] : '' );
}

/**
 * De opening van de startpagina.
 *
 * Een grote foto, daarover licht in de kleuren van het glas dat langzaam
 * verschuift, en de lijntekening van het raam die zich zet. De kop komt woord
 * voor woord omhoog; het laatste stuk glanst in de kleuren van het glas.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_opening( $attrs ) {
	$kop     = isset( $attrs['kop'] ) ? (string) $attrs['kop'] : '';
	$glans   = isset( $attrs['glans'] ) ? (string) $attrs['glans'] : '';
	$tekst   = isset( $attrs['tekst'] ) ? (string) $attrs['tekst'] : '';
	$url     = kbj_foto_url( $attrs );
	$contact = get_page_by_path( 'contact' );

	list( $breedte, $hoogte ) = kbj_foto_maat( $attrs );

	$foto = '' !== $url ? sprintf(
		'<img class="kbj-opening__foto" src="%1$s" alt="%2$s"%3$s style="object-position:%4$s" fetchpriority="high" decoding="async">',
		esc_url( $url ),
		esc_attr( isset( $attrs['alt'] ) ? $attrs['alt'] : '' ),
		$breedte ? sprintf( ' width="%d" height="%d"', $breedte, $hoogte ) : '',
		esc_attr( isset( $attrs['positie'] ) ? preg_replace( '#[^0-9% a-z.]#', '', $attrs['positie'] ) : '50% 50%' )
	) : '';

	return sprintf(
		'<section %1$s>%2$s<div class="kbj-glaslicht" aria-hidden="true"></div>%3$s'
			. '<div class="kbj-opening__binnen">'
				. '<h1 class="kbj-schuif">%4$s%5$s</h1>'
				. '%6$s'
				. '<div class="kbj-acties"><a class="kbj-knop kbj-knop--wit" href="%7$s">%8$s</a><a class="kbj-knop kbj-knop--glas" href="#aanbod">%9$s</a></div>'
			. '</div>'
			. '<ul class="kbj-opening__feiten">'
				. '<li><strong>24 uur</strong>zorg, dag en nacht</li>'
				. '<li><strong>6 plekken</strong>om te wonen</li>'
				. '<li><strong>5 dagen</strong>dagbesteding per week</li>'
				. '<li><strong>Vaak vergoed</strong>via WLZ, WMO of PGB</li>'
			. '</ul>'
		. '</section>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-opening kbj-paneel' ) ),
		$foto,
		kbj_raamlijn( 'is-getekend-bij-laden' ),
		esc_html( $kop ),
		'' !== $glans ? ' <span class="kbj-glastekst">' . esc_html( $glans ) . '</span>' : '',
		'' !== $tekst ? '<p class="kbj-opening__tekst">' . esc_html( $tekst ) . '</p>' : '',
		esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		esc_html__( 'Plan een rondleiding', 'kombij' ),
		esc_html__( 'Bekijk wat we doen', 'kombij' )
	);
}
