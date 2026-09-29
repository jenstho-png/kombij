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
		'plek'             => 'kbj_render_plek',
		'vacaturestrook'   => 'kbj_render_vacaturestrook',
		'credit'           => 'kbj_render_credit',
		'illustratie'      => 'kbj_render_illustratie',
		'afsluiter'        => 'kbj_render_afsluiter',
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
	$plek    = '';

	if ( ! empty( $attrs['plek'] ) ) {
		$stand = kbj_plek( (string) $attrs['plek'] );

		if ( 'vrij' === $stand ) {
			$plek = '<p class="kbj-plek kbj-plek--vrij">' . esc_html__( 'Er is nu een plek vrij. Bel of mail ons, dan kijken we samen of het past.', 'kombij' ) . '</p>';
		} elseif ( 'wachtlijst' === $stand ) {
			$plek = '<p class="kbj-plek kbj-plek--wachtlijst">' . esc_html__( 'Er is op dit moment een wachtlijst. Bel ons gerust: vaak kan er in de tussentijd al iets.', 'kombij' ) . '</p>';
		}
	}

	if ( $knop ) {
		$acties = sprintf(
			'<div class="kbj-acties"><a class="kbj-knop" href="%1$s">%2$s</a><a class="kbj-knop kbj-knop--rand" href="%3$s">%4$s</a></div>',
			esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
			esc_html( kbj_tekst( 'knop_rondleiding' ) ),
			esc_attr( kbj_tel_url() ),
			esc_html( kbj_tekst( 'knop_bellen' ) )
		);
	}

	return sprintf(
		'<header %1$s>%2$s<div class="kbj-paginakop__binnen"><div class="kbj-paginakop__tekst"><h1 class="kbj-paginakop__titel kbj-schuif">%3$s</h1>%4$s%7$s%5$s</div>%6$s</div></header>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-paginakop' . ( '' !== $beeld ? ' kbj-paginakop--beeld' : '' ) ) ),
		kbj_render_illustratie( array( 'naam' => ! empty( $attrs['illustratie'] ) ? $attrs['illustratie'] : 'raam' ) ),
		kbj_kombij( esc_html( $titel ) ),
		'' !== $intro ? '<p class="kbj-paginakop__intro">' . wp_kses( $intro, array( 'strong' => array(), 'br' => array() ) ) . '</p>' : '',
		$acties,
		'' !== $beeld ? '<div class="kbj-paginakop__beeld">' . $beeld . '</div>' : '',
		$plek
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
			'<a class="kbj-sociaal kbj-sociaal--%1$s" href="%2$s" rel="me noopener" target="_blank" aria-label="%3$s">%4$s</a>',
			esc_attr( $kanaal ),
			esc_url( $link ),
			/* translators: %s: naam van het sociale medium. */
			esc_attr( sprintf( __( 'KomBij op %s', 'kombij' ), ucfirst( $kanaal ) ) ),
			kbj_icoon_sociaal( $kanaal )
		);
	}

	if ( empty( $regels ) && empty( $sociaal ) ) {
		return '';
	}

	return sprintf(
		'<div %1$s>%2$s%3$s</div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-gegevens' ) ),
		$regels ? '<p class="kbj-gegevens__regels">' . implode( '<br>', $regels ) . '</p>' : '',
		$sociaal ? '<p class="kbj-gegevens__sociaal">' . implode( '', $sociaal ) . '</p>' : ''
	);
}

/**
 * Het menu in de voet: alle pagina's, inclusief de juridische.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_footermenu( $attrs ) {
	$items = array();

	foreach ( kbj_paginas() as $pagina ) {
		// De voorwaarden staan onderaan naast het copyright, niet in dit lijstje.
		if ( in_array( $pagina['slug'], array( 'bedankt', 'voorwaarden' ), true ) ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object ) {
			continue;
		}

		$label = ! empty( $pagina['voet'] ) ? $pagina['voet'] : $pagina['titel'];

		// Staan er vacatures open, dan zie je dat al in de voet.
		if ( 'werken-bij' === $pagina['slug'] && function_exists( 'kbj_vacatures_open' ) ) {
			$aantal = count( kbj_vacatures_open() );

			if ( $aantal ) {
				/* translators: %d: aantal open vacatures. */
				$label .= ' ' . sprintf( '(%d)', $aantal );
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

	$voorwaarden = get_page_by_path( 'voorwaarden' );

	if ( $voorwaarden ) {
		$delen[] = sprintf(
			'<a class="kbj-colofon__link" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $voorwaarden ) ),
			esc_html__( 'Voorwaarden en reglementen', 'kombij' )
		);
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
				. '<li><strong>Vaak vergoed</strong>via de WLZ of WMO</li>'
			. '</ul>'
		. '</section>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-opening kbj-paneel' ) ),
		$foto,
		'', // De raamtekening staat niet meer in de opening; die zit bij de familie.
		esc_html( $kop ),
		'' !== $glans ? ' <span class="kbj-glastekst">' . esc_html( $glans ) . '</span>' : '',
		'' !== $tekst ? '<p class="kbj-opening__tekst">' . esc_html( $tekst ) . '</p>' : '',
		esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		esc_html( kbj_tekst( 'knop_rondleiding' ) ),
		esc_html__( 'Bekijk wat we doen', 'kombij' )
	);
}

/**
 * Het label met de plekken: "Nu een plek vrij" of "Wachtlijst".
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_plek( $attrs ) {
	$soort = isset( $attrs['soort'] ) ? (string) $attrs['soort'] : 'wonen';
	$stand = kbj_plek( $soort );

	if ( '' === $stand ) {
		// In de editor een uitleg, zodat niemand naar een leeg vlak kijkt.
		if ( kbj_in_editor() ) {
			return '<p class="kbj-editor-uitleg">' . esc_html__( 'Label "plek vrij" staat nu uit. Aanzetten onder KomBij, Openingstijden en plek vrij.', 'kombij' ) . '</p>';
		}

		return '';
	}

	return sprintf(
		'<p %1$s>%2$s</p>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-plek kbj-plek--' . $stand ) ),
		'vrij' === $stand ? esc_html__( 'Nu een plek vrij', 'kombij' ) : esc_html__( 'Wachtlijst', 'kombij' )
	);
}

/**
 * Gerealiseerd door CREAjt: het regeltje met het logo onderaan de voet.
 *
 * Een blok en geen los stukje HTML, anders filtert WordPress de SVG eruit
 * zodra iemand de voet in de site-editor opslaat. De tekening staat op
 * currentColor en kleurt mee met de voet.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_credit( $attrs ) {
	$svg = '<svg viewBox="109 316 633 218" role="img" aria-label="CREAJT" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M720.67,450.27c-4.82,2.57-8.81,6-13.04,9.45-13.58,11.07-31.64,22.87-48.31,28.07-6.4,1.99-15.21,3.19-20.5-.52-7.08-4.96-6.46-17.94-3.65-25.18l9.61-24.76s.03-.06.04-.09l9-18.55s.02-.04.03-.06l12.87-23.15s.02-.04.03-.06l6.74-10.72c.23-.37.65-.6,1.09-.58,4.46.12,8.56.68,13.04,1.42,11.48,1.87,22.51,5.02,32.74,10.55,1.75.94,3.91,1.71,5.6.28,3.85-3.27,5.66-11.05.96-13.54-4.81-2.53-9.47-4.85-14.67-6.58-6.82-2.25-13.66-3.95-20.76-5.04-2.45-.39-4.43-.64-6.77-1.49-.72-.26-1.04-1.12-.65-1.79l1.76-3.08,6.98-10.97,9.39-14.44c1.62-2.47,1.81-4.98.34-7.58-.9-1.54-2.03-3.37-4.13-3.61-3.85-.44-7.02,1.67-9.23,4.94l-16.69,24.52-2.55,3.75-3.82,5.2c-.3.41-.83.6-1.33.46l-4.22-1.15c-5.92-1.62-10.97-5.76-14.16-10.95-1.91-3.09-5.36-4.94-8.93-4.15-3.89.9-5.9,4.98-4.73,8.77,2.13,6.96,8.75,13.76,15.23,17.15l7.8,4.07c.32.16.64.69.85,1.15.16.35.13.75-.05,1.09l-4.45,8.17-15.7,28.95c-.2.36-.56.6-.97.64h0c-1.78.17-3.54.51-5.25,1.01l-10.88,3.19-11.9,3.72c-.67.21-1.39-.18-1.57-.87l-.93-3.52s-.01-.05-.02-.07l-3.16-15.34c-1.24-6.08-3.04-11.8-5.33-17.52-.06-.16-.16-.31-.28-.43-2.06-2.12-4.63-2.99-7.68-2.62-2.81.3-6.12,1.52-7.72,4.13l-8.95,14.61-6.94,10.21-1.85,2.69s-.02.02-.04.04c-4.92,6.3-11.54,10.99-17.92,15.95-9.43,7.35-21.29,13.35-32.61,18.86-.66.32-1.45,0-1.7-.68l-24.44-65.31c-.18-.48-.65-.81-1.16-.81l-20.67.02c-.52,0-.98.32-1.16.81l-42.68,114.68c-.3.81.3,1.67,1.16,1.67l23,.02c.53,0,1.01-.34,1.18-.85l7.51-22.45c.17-.51.64-.85,1.18-.85l40.51.02c.53,0,1.01.34,1.17.84l7.55,22.42c.17.5.64.84,1.18.84h23.02c.87,0,1.47-.86,1.16-1.68l-10.75-28.72c-.21-.57.02-1.2.55-1.5,12.41-7.08,24.6-16.5,35.55-24.98,11.74-9.13,21.9-19.92,31.84-30.92.69-.77,1.97-.39,2.13.63v.03s2.57,15.92,2.57,15.92c.08.49-.14.98-.56,1.24l-6.17,3.95c-14.2,8.11-35.15,20.36-42.75,34.63-3.13,5.88-5.36,12.56-5.48,19.4-.16,8.57,3.93,17.17,10.21,22.87,5.86,5.32,14,6.78,21.59,5.62,8.25-1.28,15.31-5.72,20.72-12.16,2.67-3.21,5.38-6.06,7.06-9.77,4.41-9.73,7.8-19.76,9.87-30.31,1.56-7.9,1.48-15.61,1.85-23.68.02-.51.36-.95.84-1.12l17.38-6.04c.99-.34,1.93.62,1.57,1.6l-4.99,13.5s-.02.06-.03.09c-1.69,5.94-3.2,11.73-3.66,17.9-.76,5.98.48,11.44,2.85,16.85,1.71,3.89,3.75,7.32,7.38,9.83,2.53,1.75,5.64,2.67,8.53,3.91,4.35,1.87,8.89,2.61,13.56,1.89l10.33-1.64c.05,0,.1-.02.15-.03,7.43-2.07,14.32-4.78,21.16-8.49,11.54-6.3,22.37-13.36,32.84-21.27,4.47-3.37,8.47-6.88,12.1-11.03,2.25-2.59,2.89-5.76,1.06-8.67-1.46-2.29-4.98-4.43-7.86-2.89ZM460.8,460.01l12.82-38.54c.38-1.13,1.97-1.13,2.35,0l13,38.54c.27.8-.33,1.64-1.18,1.64h-25.82c-.85,0-1.44-.83-1.18-1.63ZM586.5,484.29c-3.65,10.11-11.64,23.67-23.29,23.23-6.52-.24-10.97-5.34-12.02-11.31-1.73-9.73,4.37-17.65,11.25-24.27,8.31-7.42,17.37-13.5,27.42-18.36.44-.08,1.59-.2,1.77.12l.61,1.01c.13.22.2.49.18.75-.79,9.91-2.43,19.48-5.91,28.83Z"/><path d="M406.47,454.56h-43.85c-.68,0-1.24.55-1.24,1.24l-.02,28.89c0,.69.55,1.24,1.24,1.24h51.9c.68,0,1.24.56,1.24,1.24l.03,16.93c0,.69-.55,1.24-1.24,1.24h-76.07c-.69,0-1.24-.56-1.24-1.24v-114.67c0-.68.55-1.24,1.24-1.24l75.9-.02c.69,0,1.24.55,1.24,1.24v17.13c0,.69-.55,1.24-1.24,1.24h-51.76c-.69,0-1.24.55-1.24,1.24v25.41c0,.69.55,1.24,1.24,1.24h43.87c.69,0,1.24.55,1.24,1.24v16.42c0,.69-.56,1.24-1.24,1.24Z"/><path d="M323.04,503.69l-24.46-46.19c-.33-.62-.07-1.38.55-1.69,3.43-1.72,6.35-3.52,9.04-5.9,4.57-4.03,7.66-8.97,9.29-14.89,4.98-21.95-3.15-39.52-25.58-45.14-5.08-1.02-9.99-1.71-15.25-1.71h-43.34c-.69,0-1.24.56-1.24,1.24v114.7c0,.69.56,1.24,1.24,1.24l21.66-.02c.68,0,1.24-.56,1.24-1.24v-40.41c0-.69.56-1.24,1.24-1.24l17.22.02c.47,0,.89.26,1.1.68l21.26,41.54c.21.41.64.68,1.11.68l25.08-.02c.06-.34.02-1.28-.16-1.63ZM284.28,441.56c-2.53.76-5.06,1.34-7.76,1.34l-19.1.02c-.69,0-1.24-.55-1.24-1.24v-32.63c0-.69.56-1.24,1.24-1.24h19.81c3,0,5.81.76,8.5,1.79.05.02.09.04.14.06,3.4,1.64,5.96,4.22,7.37,7.78,3.61,9.11.96,20.58-8.97,24.13Z"/><path d="M149.14,475.24c5.4,12.1,18.24,13.89,30.26,10.52.04-.01.08-.02.12-.04,8.41-2.9,11.5-9.91,12.42-18.29.07-.63.59-1.12,1.23-1.12h21.66c.72,0,1.3.62,1.24,1.34-1.44,17.18-11.61,31.14-28.25,36.49-10.24,3.29-21.23,3.7-31.73,1.35-17.01-3.8-28.17-17.13-32.75-33.59-2.24-8.04-2.73-16.07-2.7-24.43.02-5.86.05-11.41.94-17.16,1.05-6.8,2.94-13.09,6.04-19.18,6.2-12.18,17.14-20.79,30.65-23.46,9.21-1.82,18.6-1.46,27.62,1.06,12.56,3.51,22.42,12.39,27.09,24.48,1.63,4.33,2.63,8.49,3.21,13.36.09.73-.49,1.38-1.23,1.38l-21.63.02c-.64,0-1.16-.48-1.23-1.12-.96-8.74-3.74-15.94-12.37-19.01-5.19-1.55-10.55-1.96-15.9-.96-10.94,2.06-15.77,11.31-17.59,21.79-.98,5.66-1.31,11.21-1.11,16.98l.53,15.44c.17,4.95,1.49,9.67,3.49,14.14Z"/><path d="M589.29,366.9c-.44,2.75-1.92,5.28-4.24,6.82-.65.43-1.34.75-2.09.9-4.16.83-8.79-1.56-10.4-5.34-1.19-2.78-1.74-6.19.08-8.62,1.82-2.44,4.62-4.51,7.46-5.62,2.51-.98,5.77.11,7.57,1.87,2.25,2.21,2.24,6.15,1.62,9.99Z"/></svg>';

	return sprintf(
		'<p %1$s><a class="creajt-credit" href="%2$s" target="_blank" rel="noopener"><span>%3$s</span>%4$s</a></p>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-credit' ) ),
		esc_url( 'https://www.creajt.nl' ),
		esc_html__( 'Gerealiseerd door', 'kombij' ),
		$svg
	);
}

/**
 * De andere tekeningen: uit het gebouw en de omgeving.
 *
 * Dezelfde lijnstijl als het raam, en dezelfde beweging: de lijnen zetten zich
 * als ze in beeld komen. Bewust zonder kerkelijke tekens: een toren met een
 * spits en een windvaan, de gewelven van het schip, een kopje koffie, en de
 * Maas met de dijk.
 *
 * @param string $naam   toren, gewelf, koffie of maas.
 * @param string $klasse Extra klasse.
 * @return string
 */
function kbj_illustratie( $naam, $klasse = '' ) {
	$tekeningen = array(
		'toren'  => array(
			'vak'    => '0 0 200 360',
			'lijnen' => array(
				'M0 352H200',
				'M100 10L78 120H122Z',
				'M100 10V120',
				'M70 120L74 100L78 120M122 120L126 100L130 120',
				'M72 120V200H128V120',
				'M81 188V156C81 148 85 143 88 141C91 143 95 148 95 156V188',
				'M105 188V156C105 148 109 143 112 141C115 143 119 148 119 156V188',
				'M81 166H95M81 174H95M81 182H95M105 166H119M105 174H119M105 182H119',
				'M68 200V352M132 200V352',
				'M64 200H136M64 238H136M64 292H136',
				'M58 352V246L68 238M142 352V246L132 238',
				'M90 352V322C90 312 95 306 100 304C105 306 110 312 110 322V352',
				'M16 352V272L62 250M184 352V272L138 250',
				'M100 10V2M96 5H104',
			),
			'rondjes' => array( array( 100, 218, 11 ), array( 100, 264, 13 ), array( 100, 264, 6 ) ),
		),
		'gewelf' => array(
			'vak'    => '0 0 420 260',
			'lijnen' => array(
				'M0 256H420',
				'M14 256V128M146 256V128M274 256V128M406 256V128',
				'M8 128H20M140 128H152M268 128H280M400 128H412',
				'M14 128C14 76 44 38 80 22C116 38 146 76 146 128',
				'M146 128C146 76 174 38 210 22C246 38 274 76 274 128',
				'M274 128C274 76 304 38 340 22C376 38 406 76 406 128',
				'M26 128C26 82 52 50 80 36C108 50 134 82 134 128',
				'M158 128C158 82 184 50 210 36C236 50 262 82 262 128',
				'M286 128C286 82 312 50 340 36C368 50 394 82 394 128',
				'M14 128C60 70 120 40 210 22M406 128C360 70 300 40 210 22',
				'M46 256V178C46 164 52 156 58 152C64 156 70 164 70 178V256M90 256V178C90 164 96 156 102 152C108 156 114 164 114 178V256',
				'M178 256V178C178 164 184 156 190 152C196 156 202 164 202 178V256M218 256V178C218 164 224 156 230 152C236 156 242 164 242 178V256',
				'M306 256V178C306 164 312 156 318 152C324 156 330 164 330 178V256M350 256V178C350 164 356 156 362 152C368 156 374 164 374 178V256',
			),
			'rondjes' => array( array( 80, 92, 9 ), array( 210, 92, 9 ), array( 340, 92, 9 ) ),
		),
		'koffie' => array(
			'vak'    => '0 0 220 200',
			'lijnen' => array(
				'M60 102H160C160 138 142 160 110 160C78 160 60 138 60 102Z',
				'M160 114C182 112 186 138 164 144',
				'M36 164C36 176 184 176 184 164',
				'M92 86C82 72 102 62 92 46',
				'M110 88C100 72 120 60 110 38',
				'M128 86C118 72 138 62 128 46',
			),
			'rondjes' => array( array( 110, 126, 4 ), array( 104, 132, 4 ), array( 116, 132, 4 ), array( 110, 138, 4 ) ),
			'ovalen'  => array( array( 110, 102, 50, 7 ), array( 110, 164, 74, 10 ) ),
			'stoom'   => array( 3, 4, 5 ),
		),
		'maas'   => array(
			'vak'    => '0 0 420 170',
			'lijnen' => array(
				'M0 112C80 102 140 98 210 100C270 102 340 106 420 102',
				'M0 132Q15 127 30 132T60 132T90 132T120 132T150 132T180 132T210 132T240 132T270 132T300 132T330 132T360 132T390 132T420 132',
				'M40 148Q55 143 70 148T100 148T130 148T160 148T190 148T220 148T250 148T280 148T310 148T340 148T370 148T400 148',
				'M318 22L308 62H328Z',
				'M308 62V100M328 62V100M328 82L364 74V102',
				'M318 22V14M314 17H322',
				'M60 99V84M84 101V90M242 99V86',
				'M150 44q6-6 12 0q6-6 12 0M178 32q5-5 10 0q5-5 10 0',
			),
			'rondjes' => array( array( 60, 76, 12 ), array( 84, 84, 9 ), array( 242, 78, 10 ), array( 120, 56, 15 ) ),
		),
	);

	if ( ! isset( $tekeningen[ $naam ] ) ) {
		return '';
	}

	$t    = $tekeningen[ $naam ];
	$html = '';
	$i    = 0;

	foreach ( $t['lijnen'] as $nummer => $d ) {
		$stoom = ! empty( $t['stoom'] ) && in_array( $nummer, $t['stoom'], true );

		$html .= sprintf(
			'<path class="kbj-raam-lijn%1$s" d="%2$s" pathLength="1" style="--i:%3$d"/>',
			$stoom ? ' kbj-stoom' : '',
			$d,
			$i++
		);
	}

	foreach ( isset( $t['rondjes'] ) ? $t['rondjes'] : array() as $c ) {
		$html .= sprintf( '<circle class="kbj-raam-lijn" cx="%1$s" cy="%2$s" r="%3$s" pathLength="1" style="--i:%4$d"/>', $c[0], $c[1], $c[2], $i++ );
	}

	foreach ( isset( $t['ovalen'] ) ? $t['ovalen'] : array() as $e ) {
		$html .= sprintf( '<ellipse class="kbj-raam-lijn" cx="%1$s" cy="%2$s" rx="%3$s" ry="%4$s" pathLength="1" style="--i:%5$d"/>', $e[0], $e[1], $e[2], $e[3], $i++ );
	}

	return sprintf(
		'<svg class="kbj-raamlijn kbj-illustratie kbj-illustratie--%1$s %2$s" viewBox="%3$s" aria-hidden="true" focusable="false"><g class="kbj-raam-lijnen">%4$s</g></svg>',
		esc_attr( $naam ),
		esc_attr( $klasse ),
		esc_attr( $t['vak'] ),
		$html
	);
}

/**
 * Een tekening als blok.
 *
 * @param array $attrs Blokinstellingen.
 * @return string
 */
function kbj_render_illustratie( $attrs ) {
	$naam = isset( $attrs['naam'] ) ? (string) $attrs['naam'] : 'toren';

	if ( 'raam' === $naam ) {
		return kbj_raamlijn( isset( $attrs['className'] ) ? (string) $attrs['className'] : '' );
	}

	return kbj_illustratie( $naam, isset( $attrs['className'] ) ? (string) $attrs['className'] : '' );
}

/**
 * De eigen blokken zichtbaar en aanpasbaar maken in de blok-editor.
 *
 * Zonder dit script zegt de editor bij elk van onze blokken dat hij ze niet
 * kent. Nu tonen ze een voorbeeld zoals op de site, met de instellingen in het
 * zijpaneel.
 */
function kbj_editor_blokken() {
	$namen = array();

	foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $naam => $type ) {
		if ( 0 === strpos( $naam, 'kbj/' ) ) {
			$namen[] = $naam;
		}
	}

	wp_enqueue_script(
		'kbj-editor',
		KBJ_URI . '/assets/js/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-data' ),
		KBJ_VERSION,
		true
	);

	wp_add_inline_script( 'kbj-editor', 'window.kbjBlokken = ' . wp_json_encode( $namen ) . ';', 'before' );
}
add_action( 'enqueue_block_editor_assets', 'kbj_editor_blokken' );

/**
 * Een klein icoon voor een sociaal medium, in de kleur van de tekst.
 *
 * @param string $kanaal instagram, facebook, linkedin of youtube.
 * @return string
 */
function kbj_icoon_sociaal( $kanaal ) {
	$paden = array(
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17.4" cy="6.6" r="1.2" fill="currentColor"/>',
		'facebook'  => '<path fill="currentColor" d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H7.9v3h2.6V21z"/>',
		'linkedin'  => '<path fill="currentColor" d="M5 8.5h3V19H5zM6.5 4a1.75 1.75 0 1 1 0 3.5 1.75 1.75 0 0 1 0-3.5zM10.5 8.5h2.9v1.4c.4-.8 1.4-1.6 2.9-1.6 3.1 0 3.7 2 3.7 4.7V19h-3v-5.2c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7V19h-2.8z"/>',
		'youtube'   => '<path fill="currentColor" d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3z"/>',
	);

	if ( ! isset( $paden[ $kanaal ] ) ) {
		return esc_html( ucfirst( $kanaal ) );
	}

	return '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">' . $paden[ $kanaal ] . '</svg>';
}

/**
 * De afsluiter onderaan elke pagina, met de teksten uit Gegevens.
 *
 * @return string
 */
function kbj_render_afsluiter() {
	$contact = get_page_by_path( 'contact' );
	$kop     = kbj_tekst( 'afsluiter_kop' );

	// Na een punt begint de kop op een nieuwe regel, zoals in het ontwerp.
	$kop = preg_replace( '/\.\s+/u', '.<br>', esc_html( $kop ), 1 );

	return sprintf(
		'<div %1$s><h2 class="wp-block-heading has-text-align-center kbj-schuif">%2$s</h2>'
			. '<p class="has-text-align-center kbj-intro">%3$s</p>'
			. '<div class="wp-block-buttons kbj-acties is-content-justification-center is-layout-flex">'
				. '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="%4$s">%5$s</a></div>'
				. '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="%6$s">%7$s</a></div>'
			. '</div>'
			. '<p class="has-text-align-center kbj-bellen">%8$s <a href="%6$s">%9$s</a>.</p>'
		. '</div>',
		get_block_wrapper_attributes( array( 'class' => 'kbj-slot__binnen' ) ),
		kbj_kombij( $kop ),
		esc_html( kbj_tekst( 'afsluiter_tekst' ) ),
		esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		esc_html( kbj_tekst( 'knop_rondleiding' ) ),
		esc_attr( kbj_tel_url() ),
		esc_html( kbj_tekst( 'knop_bellen' ) ),
		esc_html( kbj_tekst( 'afsluiter_bellen' ) ),
		esc_html( kbj_tel_tekst() )
	);
}

/**
 * Wordt dit blok nu voor de editor opgebouwd?
 *
 * @return bool
 */
function kbj_in_editor() {
	return defined( 'REST_REQUEST' ) && REST_REQUEST && is_user_logged_in();
}
