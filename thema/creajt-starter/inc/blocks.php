<?php
/**
 * De blokken die het thema zelf tekent.
 *
 * Een blok is hier een stuk vormgeving dat de klant mag vullen maar niet mag
 * slopen: de opmaak zit in de code, de tekst en het beeld in de instellingen.
 * Daardoor kan er in de editor niets kapot en ziet elke sectie er over een jaar
 * nog net zo uit.
 *
 * @package creajt-starter
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
function cjt_register_blocks() {
	$blokken = array(
		'header'           => 'cjt_render_header',
		'paginakop'        => 'cjt_render_paginakop',
		'sfeerbeeld'       => 'cjt_render_sfeerbeeld',
		'video-facade'     => 'cjt_render_video_facade',
		'contactformulier' => 'cjt_render_contactformulier',
		'contactgegevens'  => 'cjt_render_contactgegevens',
		'footermenu'       => 'cjt_render_footermenu',
		'colofon'          => 'cjt_render_colofon',
		'logo'             => 'cjt_render_logo',
	);

	foreach ( $blokken as $naam => $functie ) {
		$map = CJT_DIR . '/blocks/' . $naam;

		if ( ! file_exists( $map . '/block.json' ) ) {
			continue;
		}

		register_block_type( $map, array( 'render_callback' => $functie ) );
	}
}
add_action( 'init', 'cjt_register_blocks' );

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
function cjt_logo_svg( $klasse = '' ) {
	static $cache = null;

	if ( null === $cache ) {
		$bestand = CJT_DIR . '/assets/beeld/logo.svg';
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
 * De kop van een binnenpagina: een regel erboven, de titel en een intro.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_paginakop( $attrs ) {
	$titel = isset( $attrs['titel'] ) ? (string) $attrs['titel'] : '';
	$intro = isset( $attrs['intro'] ) ? (string) $attrs['intro'] : '';
	$boven = isset( $attrs['boven'] ) ? (string) $attrs['boven'] : '';

	if ( '' === $titel ) {
		$titel = (string) get_the_title();
	}

	return sprintf(
		'<header %1$s>%2$s<h1 class="cjt-paginakop__titel">%3$s</h1>%4$s</header>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-paginakop cjt-reveal' ) ),
		'' !== $boven ? '<p class="cjt-paginakop__boven">' . esc_html( $boven ) . '</p>' : '',
		esc_html( $titel ),
		'' !== $intro ? '<p class="cjt-paginakop__intro">' . esc_html( $intro ) . '</p>' : ''
	);
}

/**
 * Een beeld over de volle breedte, met een bijschrift.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_sfeerbeeld( $attrs ) {
	$url      = isset( $attrs['url'] ) ? (string) $attrs['url'] : '';
	$alt      = isset( $attrs['alt'] ) ? (string) $attrs['alt'] : '';
	$onder    = isset( $attrs['bijschrift'] ) ? (string) $attrs['bijschrift'] : '';
	$hoogte   = isset( $attrs['hoogte'] ) ? (string) $attrs['hoogte'] : 'normaal';
	$klassen  = 'cjt-sfeerbeeld cjt-reveal cjt-sfeerbeeld--' . sanitize_html_class( $hoogte );

	if ( '' === $url ) {
		return sprintf(
			'<figure %1$s><div class="cjt-leeg"><span>%2$s</span></div></figure>',
			get_block_wrapper_attributes( array( 'class' => $klassen ) ),
			esc_html__( 'Kies hier een foto', 'creajt-starter' )
		);
	}

	return sprintf(
		'<figure %1$s>%2$s%3$s</figure>',
		get_block_wrapper_attributes( array( 'class' => $klassen ) ),
		cjt_beeld( $url, $alt, '100vw', 'cjt-sfeerbeeld__beeld' ),
		'' !== $onder ? '<figcaption class="cjt-sfeerbeeld__onder">' . esc_html( $onder ) . '</figcaption>' : ''
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
function cjt_render_video_facade( $attrs ) {
	$invoer = isset( $attrs['video'] ) ? (string) $attrs['video'] : '';
	$bron   = cjt_video_bron( $invoer );

	if ( '' === $bron['id'] ) {
		return sprintf(
			'<div %1$s><div class="cjt-leeg"><span>%2$s</span></div></div>',
			get_block_wrapper_attributes( array( 'class' => 'cjt-video cjt-reveal' ) ),
			esc_html__( 'Plak hier de link naar de video', 'creajt-starter' )
		);
	}

	$poster = isset( $attrs['poster'] ) ? (string) $attrs['poster'] : '';

	if ( '' === $poster ) {
		$poster = 'vimeo' === $bron['soort']
			? cjt_vimeo_poster( $bron['id'], $bron['sleutel'] )
			: sprintf( 'https://i.ytimg.com/vi/%s/maxresdefault.jpg', $bron['id'] );
	}

	$titel = isset( $attrs['titel'] ) ? (string) $attrs['titel'] : __( 'Bekijk de video', 'creajt-starter' );

	return sprintf(
		'<div %1$s><cjt-video-facade soort="%2$s" video="%3$s" sleutel="%4$s" poster="%5$s" titel="%6$s"></cjt-video-facade></div>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-video cjt-reveal' ) ),
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
function cjt_render_contactgegevens( $attrs ) {
	$regels = array();

	$email = cjt_optie( 'email' );

	if ( '' !== $email ) {
		$regels[] = sprintf(
			'<a href="mailto:%1$s">%1$s</a>',
			esc_attr( $email )
		);
	}

	$telefoon = cjt_optie( 'telefoon' );

	if ( '' !== $telefoon ) {
		$regels[] = sprintf(
			'<a href="tel:%s">%s</a>',
			esc_attr( preg_replace( '/[^\d+]/', '', $telefoon ) ),
			esc_html( $telefoon )
		);
	}

	$adres = trim( cjt_optie( 'straat' ) . ' ' . cjt_optie( 'postcode' ) . ' ' . cjt_optie( 'plaats' ) );

	if ( '' !== $adres ) {
		$regels[] = esc_html( $adres );
	} elseif ( '' !== cjt_optie( 'plaats' ) ) {
		$regels[] = esc_html( cjt_optie( 'plaats' ) );
	}

	$sociaal = array();

	foreach ( array( 'instagram', 'facebook', 'linkedin', 'youtube' ) as $kanaal ) {
		$link = cjt_optie( $kanaal );

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
		get_block_wrapper_attributes( array( 'class' => 'cjt-gegevens' ) ),
		$regels ? '<p class="cjt-gegevens__regels">' . implode( '<br>', $regels ) . '</p>' : '',
		$sociaal ? '<p class="cjt-gegevens__sociaal">' . implode( '<span aria-hidden="true"> / </span>', $sociaal ) . '</p>' : ''
	);
}

/**
 * Het menu in de voet: alle pagina's, inclusief de juridische.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_footermenu( $attrs ) {
	$items = array(
		array(
			'label' => __( 'Home', 'creajt-starter' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( cjt_paginas() as $pagina ) {
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
		'<nav %1$s aria-label="%2$s"><ul class="cjt-footermenu__lijst">%3$s</ul></nav>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-footermenu' ) ),
		esc_attr__( 'Menu in de voet', 'creajt-starter' ),
		$html
	);
}

/**
 * De regel helemaal onderaan: naam, jaartal, KvK.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_colofon( $attrs ) {
	$naam = cjt_optie( 'bedrijf', (string) get_bloginfo( 'name' ) );

	$delen = array(
		sprintf( '&copy; %s %s', esc_html( gmdate( 'Y' ) ), esc_html( $naam ) ),
	);

	$kvk = cjt_optie( 'kvk' );

	if ( '' !== $kvk ) {
		/* translators: %s: KvK-nummer. */
		$delen[] = esc_html( sprintf( __( 'KvK %s', 'creajt-starter' ), $kvk ) );
	}

	$btw = cjt_optie( 'btw' );

	if ( '' !== $btw ) {
		/* translators: %s: btw-nummer. */
		$delen[] = esc_html( sprintf( __( 'Btw %s', 'creajt-starter' ), $btw ) );
	}

	return sprintf(
		'<p %1$s>%2$s</p>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-colofon' ) ),
		implode( '<span aria-hidden="true"> &middot; </span>', $delen )
	);
}

/**
 * Het logo als los blok, voor de voet en voor losse secties.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function cjt_render_logo( $attrs ) {
	$breedte = isset( $attrs['breedte'] ) ? (int) $attrs['breedte'] : 160;
	$breedte = max( 60, min( 480, $breedte ) );

	// Staat dit logo op een donker vlak? Dan mag er nooit een vaste kleur op.
	$licht = ! empty( $attrs['licht'] );

	$vorm = cjt_logo_svg( 'cjt-logo__vorm' );

	if ( '' === $vorm ) {
		$logo_id  = (int) get_theme_mod( 'custom_logo' );
		$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';

		/*
		 * Op een donker vlak nooit terugvallen op de geüploade afbeelding. Die
		 * heeft zijn eigen kleur, en een donker logo op een donkere voet is
		 * onzichtbaar. De naam van de site erven de kleur van de tekst eromheen
		 * en is dus altijd leesbaar. Wil je hier toch het logo, lever dan een
		 * SVG aan met currentColor erin.
		 */
		if ( '' === $logo_url || $licht ) {
			return sprintf(
				'<p %1$s>%2$s</p>',
				get_block_wrapper_attributes( array( 'class' => 'cjt-logo cjt-logo--tekst' ) ),
				esc_html( cjt_optie( 'bedrijf', (string) get_bloginfo( 'name' ) ) )
			);
		}

		$vorm = sprintf(
			'<img src="%s" alt="%s" decoding="async" loading="lazy">',
			esc_url( $logo_url ),
			esc_attr( get_bloginfo( 'name' ) )
		);
	}

	return sprintf(
		'<div %1$s style="--cjt-logo-breedte:%2$dpx">%3$s</div>',
		get_block_wrapper_attributes( array( 'class' => 'cjt-logo' ) ),
		$breedte,
		$vorm
	);
}
