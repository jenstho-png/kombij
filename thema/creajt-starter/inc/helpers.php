<?php
/**
 * Kleine hulpjes die overal terugkomen.
 *
 * @package creajt-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Haalt het YouTube-id uit een link, een insluitcode of een kaal id.
 *
 * @param string $invoer Wat er ingevuld is.
 * @return string Het id, of een lege string.
 */
function cjt_youtube_id( $invoer ) {
	$invoer = trim( (string) $invoer );

	if ( '' === $invoer ) {
		return '';
	}

	// Al een kaal id.
	if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $invoer ) ) {
		return $invoer;
	}

	$patronen = array(
		'#youtu\.be/([A-Za-z0-9_-]{11})#',
		'#youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})#',
		'#youtube\.com/embed/([A-Za-z0-9_-]{11})#',
		'#youtube\.com/shorts/([A-Za-z0-9_-]{11})#',
	);

	foreach ( $patronen as $patroon ) {
		if ( preg_match( $patroon, $invoer, $treffer ) ) {
			return $treffer[1];
		}
	}

	return '';
}

/**
 * Haalt het Vimeo-id uit een link of een kaal nummer.
 *
 * Vimeo kent verborgen video's met een sleutel achter het nummer
 * (vimeo.com/123456789/abcdef123). Die sleutel hoort bij de video en moet mee,
 * anders krijg je "private video".
 *
 * @param string $invoer Wat er ingevuld is.
 * @return array Met 'id' en 'sleutel'; beide leeg als er niets in zit.
 */
function cjt_vimeo_id( $invoer ) {
	$invoer = trim( (string) $invoer );
	$leeg   = array(
		'id'      => '',
		'sleutel' => '',
	);

	if ( '' === $invoer ) {
		return $leeg;
	}

	if ( preg_match( '/^\d{6,12}$/', $invoer ) ) {
		return array(
			'id'      => $invoer,
			'sleutel' => '',
		);
	}

	if ( preg_match( '#vimeo\.com/(?:video/)?(\d{6,12})(?:/([A-Za-z0-9]+))?#', $invoer, $treffer ) ) {
		return array(
			'id'      => $treffer[1],
			'sleutel' => isset( $treffer[2] ) ? $treffer[2] : '',
		);
	}

	// player.vimeo.com/video/123?h=abc
	if ( preg_match( '#player\.vimeo\.com/video/(\d{6,12})#', $invoer, $treffer ) ) {
		$sleutel = '';
		if ( preg_match( '/[?&]h=([A-Za-z0-9]+)/', $invoer, $h ) ) {
			$sleutel = $h[1];
		}
		return array(
			'id'      => $treffer[1],
			'sleutel' => $sleutel,
		);
	}

	return $leeg;
}

/**
 * Bepaalt of een link naar YouTube of naar Vimeo wijst.
 *
 * @param string $invoer Wat er ingevuld is.
 * @return array Met 'soort', 'id' en 'sleutel'.
 */
function cjt_video_bron( $invoer ) {
	$vimeo = cjt_vimeo_id( $invoer );

	if ( '' !== $vimeo['id'] ) {
		return array(
			'soort'   => 'vimeo',
			'id'      => $vimeo['id'],
			'sleutel' => $vimeo['sleutel'],
		);
	}

	$youtube = cjt_youtube_id( $invoer );

	if ( '' !== $youtube ) {
		return array(
			'soort'   => 'youtube',
			'id'      => $youtube,
			'sleutel' => '',
		);
	}

	return array(
		'soort'   => '',
		'id'      => '',
		'sleutel' => '',
	);
}

/**
 * De posterfoto van een Vimeo-video, via de oEmbed van Vimeo.
 *
 * Het antwoord blijft een week in de cache staan. Lukt het niet, dan proberen
 * we het een uur later nog eens en niet bij elke paginaweergave opnieuw.
 *
 * @param string $id      Het Vimeo-nummer.
 * @param string $sleutel De sleutel van een verborgen video.
 * @return string De url van de foto, of een lege string.
 */
function cjt_vimeo_poster( $id, $sleutel = '' ) {
	$id = preg_replace( '/\D/', '', (string) $id );

	if ( '' === $id ) {
		return '';
	}

	$naam    = 'cjt_vimeo_' . md5( $id . '|' . $sleutel );
	$bewaard = get_transient( $naam );

	if ( is_string( $bewaard ) ) {
		return '-' === $bewaard ? '' : $bewaard;
	}

	$adres = 'https://vimeo.com/' . $id . ( '' !== $sleutel ? '/' . $sleutel : '' );
	$vraag = add_query_arg(
		array(
			'url'   => rawurlencode( $adres ),
			'width' => 1280,
		),
		'https://vimeo.com/api/oembed.json'
	);

	$antwoord = wp_remote_get( $vraag, array( 'timeout' => 5 ) );

	if ( is_wp_error( $antwoord ) || 200 !== (int) wp_remote_retrieve_response_code( $antwoord ) ) {
		set_transient( $naam, '-', HOUR_IN_SECONDS );
		return '';
	}

	$gegevens = json_decode( wp_remote_retrieve_body( $antwoord ), true );

	if ( ! is_array( $gegevens ) || empty( $gegevens['thumbnail_url'] ) ) {
		set_transient( $naam, '-', HOUR_IN_SECONDS );
		return '';
	}

	$foto = (string) $gegevens['thumbnail_url'];

	set_transient( $naam, $foto, WEEK_IN_SECONDS );

	return $foto;
}

/**
 * Een afbeelding met de juiste maten, ook als we alleen de url hebben.
 *
 * WordPress kan srcset en de maten alleen maken als het de afbeelding kent.
 * Daarom zoeken we eerst het id erbij; lukt dat niet (een foto van buiten de
 * mediabibliotheek), dan zetten we er alsnog een gewone img neer.
 *
 * @param string $url    Adres van de afbeelding.
 * @param string $alt    Beschrijving voor wie de foto niet ziet.
 * @param string $sizes  De sizes-regel.
 * @param string $klasse Extra klassen.
 * @return string
 */
function cjt_beeld( $url, $alt = '', $sizes = '', $klasse = '' ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '';
	}

	$id = cjt_beeld_id( $url );

	if ( $id ) {
		return (string) wp_get_attachment_image(
			$id,
			'large',
			false,
			array(
				'alt'      => $alt,
				'class'    => $klasse,
				'sizes'    => '' !== $sizes ? $sizes : null,
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
	}

	return sprintf(
		'<img src="%s" alt="%s"%s loading="lazy" decoding="async">',
		esc_url( $url ),
		esc_attr( $alt ),
		'' !== $klasse ? ' class="' . esc_attr( $klasse ) . '"' : ''
	);
}

/**
 * Het id van een afbeelding bij een url uit de mediabibliotheek.
 *
 * @param string $url Adres van de afbeelding.
 * @return int Nul als de afbeelding niet in de bibliotheek staat.
 */
function cjt_beeld_id( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return 0;
	}

	$naam    = 'cjt_beeld_' . md5( $url );
	$bewaard = get_transient( $naam );

	if ( false !== $bewaard ) {
		return (int) $bewaard;
	}

	// Een bijgesneden versie (foto-800x600.jpg) hoort bij dezelfde bijlage.
	$kaal = preg_replace( '/-\d+x\d+(?=\.(jpe?g|png|gif|webp|avif)$)/i', '', $url );

	$id = attachment_url_to_postid( $kaal );

	if ( ! $id && $kaal !== $url ) {
		$id = attachment_url_to_postid( $url );
	}

	set_transient( $naam, (int) $id, DAY_IN_SECONDS );

	return (int) $id;
}

/**
 * De url van de pagina waar de bezoeker nu staat.
 *
 * @return string
 */
function cjt_huidige_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_singular() ) {
		return (string) get_permalink();
	}

	if ( is_post_type_archive() ) {
		$soort = get_query_var( 'post_type' );
		$adres = get_post_type_archive_link( is_array( $soort ) ? reset( $soort ) : $soort );
		if ( $adres ) {
			return (string) $adres;
		}
	}

	return home_url( add_query_arg( array() ) );
}

/**
 * Een tekst inkorten op een heel woord, met een beletselteken erachter.
 *
 * @param string $tekst De tekst.
 * @param int    $lengte Maximaal aantal tekens.
 * @return string
 */
function cjt_inkorten( $tekst, $lengte = 160 ) {
	$tekst = trim( wp_strip_all_tags( (string) $tekst ) );

	if ( '' === $tekst || mb_strlen( $tekst ) <= $lengte ) {
		return $tekst;
	}

	$kort = mb_substr( $tekst, 0, $lengte );
	$stop = mb_strrpos( $kort, ' ' );

	if ( false !== $stop && $stop > 40 ) {
		$kort = mb_substr( $kort, 0, $stop );
	}

	return rtrim( $kort, " ,.;:-" ) . '...';
}
