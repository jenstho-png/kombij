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
	$boven = isset( $attrs['boven'] ) ? (string) $attrs['boven'] : '';
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
				'vorm'       => 'boog',
				'verhouding' => '4/5',
				'eerst'      => true,
			)
		);
	}

	$contact = get_page_by_path( 'contact' );
	$acties  = '';

	if ( $knop ) {
		$acties = sprintf(
			'<div class="kbj-acties"><a class="kbj-knop" href="%1$s">%2$s</a>%3$s</div>',
			esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
			esc_html__( 'Plan een kennismaking', 'kombij' ),
			kbj_bel_link( 'kbj-knop kbj-knop--rand' )
		);
	}

	return sprintf(
		'<header %1$s><div class="kbj-paginakop__binnen"><div class="kbj-paginakop__tekst">%2$s<h1 class="kbj-paginakop__titel">%3$s</h1>%4$s%5$s</div>%6$s</div></header>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-paginakop kbj-ornament' . ( '' !== $beeld ? ' kbj-paginakop--beeld' : '' ) ) ),
		'' !== $boven ? '<p class="kbj-boven">' . esc_html( $boven ) . '</p>' : '',
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

		$items[] = array(
			'label' => $pagina['titel'],
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
