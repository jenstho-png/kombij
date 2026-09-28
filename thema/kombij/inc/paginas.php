<?php
/**
 * De pagina's van de site, en het menu.
 *
 * De startpagina is het verhaal; de losse pagina's zijn de hoofdstukken waar je
 * verder kunt lezen. Elke pagina moet op zichzelf werken: iemand die via Google
 * binnenkomt op "Diensten" moet daar alles vinden wat hij nodig heeft, zonder
 * eerst naar de home te hoeven.
 *
 * Dit draait bij het activeren van het thema. Bestaat een pagina al, dan
 * blijven we eraf.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De pagina's die de site nodig heeft.
 *
 * De volgorde is de volgorde in het menu. Een lege `menu` betekent: wel een
 * pagina, niet in de balk bovenaan.
 *
 * Pas deze lijst aan voor de klant. Elk patroon dat je hier noemt moet ook in
 * /patterns bestaan, anders blijft de verwijzing gewoon staan en komt er een
 * lege plek. Dat is met opzet: beter een zichtbare lege plek dan een pagina die
 * stilletjes halve inhoud heeft.
 *
 * @return array[]
 */
function kbj_paginas() {
	return array(
		array(
			'slug'     => 'wonen-met-zorg',
			'tekening' => 'raam',
			'plek'     => 'wonen',
			'seo'      => 'Wonen met zorg in Maasbommel, kleinschalig en huiselijk',
			'titel'    => 'Wonen met zorg',
			'menu'     => 'Wonen',
			'boven'    => 'Wonen met zorg in Maasbommel',
			'kop'      => 'KomBij ons wonen',
			'intro'    => 'Gaat thuis wonen niet meer? Bij KomBij woont u in een kleine groep, in een eigen kamer, met 24 uur per dag zorg dichtbij.',
			'zoek'     => 'Kleinschalig wonen met 24 uur zorg in Maasbommel, voor zes bewoners met een WLZ-indicatie. Ook bij dementie of intensieve verpleging.',
			'bestand'  => 'slaapkamer-raam.webp',
			'alt'      => 'Een hoog glas-in-loodraam met gordijnen in een van de kamers',
			'patronen' => array( 'pagina-wonen' ),
		),
		array(
			'slug'     => 'logeren-met-zorg',
			'tekening' => 'maas',
			'plek'     => 'logeren',
			'seo'      => 'Logeren met zorg in Maasbommel, respijtzorg voor mantelzorgers',
			'titel'    => 'Logeren met zorg',
			'menu'     => 'Logeren',
			'boven'    => 'Logeren met zorg in Maasbommel',
			'kop'      => 'KomBij ons logeren',
			'intro'    => 'Even niet zorgen. Uw naaste logeert een paar nachten bij ons, met 24 uur per dag zorg. U komt op adem, en weet dat het goed geregeld is.',
			'zoek'     => 'Logeeropvang met 24 uur zorg in Maasbommel om mantelzorgers te ontlasten. Vanaf 2 nachten, via WLZ, WMO, PGB of particulier.',
			'bestand'  => 'zitplek.webp',
			'alt'      => 'Een fauteuil en een ronde tafel met bloemen in een logeerkamer',
			'patronen' => array( 'pagina-logeren' ),
		),
		array(
			'slug'     => 'dagbesteding',
			'tekening' => 'koffie',
			'plek'     => 'dagbesteding',
			'seo'      => 'Dagbesteding voor ouderen in Maasbommel',
			'titel'    => 'Dagbesteding',
			'menu'     => 'Dagbesteding',
			'boven'    => 'Dagbesteding in Maasbommel',
			'kop'      => 'KomBij ons de dag doorbrengen',
			'intro'    => 'Een fijne dag met structuur en gezelligheid. Van maandag tot en met vrijdag, van 10:30 tot 16:30, in een warme en veilige omgeving.',
			'zoek'     => 'Dagbesteding voor ouderen in Maasbommel, West Maas en Waal. Maandag tot en met vrijdag van 10:30 tot 16:30, via WMO, WLZ of particulier.',
			'bestand'  => 'dagbesteding.webp',
			'alt'      => 'Gasten en een begeleider aan de lange tafel in de kerk',
			'patronen' => array( 'pagina-dagbesteding' ),
		),
		array(
			'slug'     => 'kosten-en-financiering',
			'tekening' => 'gewelf',
			'voet'     => 'Kosten',
			'seo'      => 'Kosten en financiering: WLZ, WMO, PGB of particulier',
			'titel'    => 'Kosten en financiering',
			'menu'     => 'Kosten',
			'boven'    => 'Kosten en financiering',
			'kop'      => 'Wat kost zorg bij KomBij?',
			'intro'    => 'De meeste zorg bij KomBij wordt vergoed. Welke regeling voor u geldt, hangt af van uw situatie. Hier leggen we het rustig uit.',
			'zoek'     => 'Uitleg over WLZ, WMO, PGB en particulier betalen voor wonen, logeren en dagbesteding bij KomBij Maasbommel.',
			'bestand'  => 'vanaf-orgel.webp',
			'alt'      => 'Het schip van de kerk, gezien vanaf het orgel',
			'patronen' => array( 'pagina-kosten' ),
		),
		array(
			'slug'     => 'over-ons',
			'tekening' => 'toren',
			'seo'      => 'Over KomBij en de Lambertuskerk in Maasbommel',
			'titel'    => 'Over ons',
			'menu'     => 'Over ons',
			'boven'    => 'Over KomBij',
			'kop'      => 'Een familie, een monument en zorg vanuit ons hart',
			'intro'    => 'KomBij is een initiatief van Corrie Roelofsen, haar dochter Chantal en schoonzoon Edwin. Samen gaven ze een monumentaal gebouw in Maasbommel een nieuwe bestemming.',
			'zoek'     => 'Het verhaal van KomBij: een familie die de monumentale Lambertuskerk in Maasbommel verbouwde tot een kleinschalige zorglocatie.',
			'bestand'  => 'kerk-groen.webp',
			'alt'      => 'De toren van de Lambertuskerk tussen het groen',
			'patronen' => array( 'pagina-over' ),
		),
		array(
			'slug'     => 'contact',
			'tekening' => 'koffie',
			'seo'      => 'Contact, adres en rondleiding',
			'titel'    => 'Contact',
			'menu'     => 'Contact',
			'boven'    => 'Contact',
			'kop'      => 'KomBij ons langs',
			'intro'    => 'Wilt u een rondleiding, heeft u een vraag over zorg of kosten, of wilt u zich aanmelden? Bel, mail of stuur een bericht.',
			'zoek'     => 'Adres, telefoonnummer en openingstijden van KomBij, Raadhuisdijk 44 in Maasbommel. Plan een rondleiding.',
			'bestand'  => 'koffie.webp',
			'alt'      => 'Een kopje koffie wordt aangereikt',
			'knop'     => false,
			'patronen' => array( 'contact' ),
		),
		array(
			'slug'     => 'werken-bij',
			'tekening' => 'toren',
			'voet'     => 'Werken bij',
			'seo'      => 'Werken in de zorg bij KomBij in Maasbommel',
			'titel'    => 'Werken bij KomBij',
			'menu'     => '',
			'boven'    => 'Werken bij KomBij',
			'kop'      => 'KomBij ons werken',
			'intro'    => 'We zoeken collega’s en vrijwilligers voor wie persoonlijke aandacht vanzelf spreekt. In een warm team, in een monumentaal gebouw aan de Maas.',
			'zoek'     => 'Vacatures voor verzorgende IG, verpleegkundige, helpende plus en nachtdienst bij KomBij in Maasbommel. Ook vrijwilligers welkom.',
			'bestand'  => 'schip.webp',
			'alt'      => 'Het schip van de kerk met de nieuwe tussenverdieping',
			'knop'     => false,
			'patronen' => array( 'pagina-werken' ),
		),
		array(
			'slug'     => 'zoutkamer',
			'tekening' => 'gewelf',
			'seo'      => 'Zoutkamer in Maasbommel, tarieven en reserveren',
			'titel'    => 'Zoutkamer',
			'menu'     => '',
			'boven'    => 'Zoutkamer Maasbommel',
			'kop'      => 'De zoutkamer',
			'intro'    => 'Bij KomBij zijn twee zoutkamers: een grote voor 8 personen en een kleine voor 4. Voor wie bij KomBij woont, logeert of de dag doorbrengt, en voor iedereen die wil reserveren.',
			'zoek'     => 'Twee zoutkamers in Maasbommel, voor 8 en 4 personen. Open maandag tot en met vrijdag, reserveren online of telefonisch.',
			'bestand'  => '',
			'knop'     => false,
			'patronen' => array( 'pagina-zoutkamer' ),
		),
		array(
			'slug'     => 'bedankt',
			'titel'    => 'Bedankt',
			'menu'     => '',
			'kop'      => '',
			'intro'    => '',
			'patronen' => array( 'bedankt' ),
		),
		array(
			'slug'     => 'voorwaarden',
			'tekening' => 'gewelf',
			'voet'     => 'Voorwaarden',
			'seo'      => 'Voorwaarden, huisregels en privacyreglement',
			'titel'    => 'Voorwaarden en reglementen',
			'menu'     => '',
			'boven'    => 'Documenten',
			'kop'      => 'Voorwaarden en reglementen',
			'intro'    => 'De voorwaarden voor logeren, de huisregels en ons privacyreglement.',
			'knop'     => false,
			'patronen' => array( 'pagina-voorwaarden' ),
		),
	);
}

/**
 * Oude adressen van de vorige site, en waar ze nu naartoe gaan.
 *
 * Google kent de oude pagina's nog. Zonder doorverwijzing krijgt wie daarop
 * klikt een 404, en gaat de opgebouwde positie in Google verloren.
 *
 * @return string[]
 */
function kbj_doorverwijzingen() {
	return array(
		'zorgeloos-wonen'                         => 'wonen-met-zorg',
		'zorgeloos-logeren'                       => 'logeren-met-zorg',
		'openingstijden'                          => 'contact',
		'werken-in-de-zorg-bij-kombij-maasbommel' => 'werken-bij',
		'algemene-voorwaarden'                    => 'voorwaarden',
	);
}

/**
 * Stuurt een oud adres permanent door naar het nieuwe.
 */
function kbj_doorverwijzen() {
	if ( ! is_404() || ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$pad = trim( (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ), '/' );
	$map = kbj_doorverwijzingen();

	if ( isset( $map[ $pad ] ) ) {
		wp_safe_redirect( home_url( '/' . $map[ $pad ] . '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'kbj_doorverwijzen', 5 );

/**
 * De inhoud van de startpagina: één verwijzing naar het patroon "homepage".
 *
 * Niet de secties zelf, maar de verwijzing naar de compositie. Daarmee bepaalt
 * het thema welke secties er staan en in welke volgorde.
 *
 * @return string
 */
function kbj_startpagina_inhoud() {
	return kbj_patroon_naar_blokken( '<!-- wp:pattern {"slug":"kombij/homepage"} /-->' );
}

/**
 * Vervangt verwijzingen naar patronen door de blokken zelf.
 *
 * Waarom dat nodig is: een pagina die alleen "<!-- wp:pattern -->" bevat, is in
 * de editor één blok. WordPress zet dat blok op de gewone tekstbreedte, en alles
 * wat erin zit blijft daarbinnen. Secties die op de site van rand tot rand lopen
 * staan dan in de editor in een kolom van 780 pixels, met kolommen die niet
 * naast elkaar passen en koppen die over vier regels breken.
 *
 * Staan de blokken er los in, dan is elke sectie een eigen blok in de editor,
 * loopt hij daar net zo breed als op de site, en kun je erop klikken.
 *
 * @param string $blokken De blokopmaak.
 * @param int    $diepte  Beveiliging tegen een patroon dat zichzelf aanroept.
 * @return string
 */
function kbj_patroon_naar_blokken( $blokken, $diepte = 0 ) {
	if ( $diepte > 5 || false === strpos( $blokken, 'wp:pattern' ) ) {
		return $blokken;
	}

	return preg_replace_callback(
		'#<!--\s*wp:pattern\s*(\{.*?\})\s*/-->#',
		function ( $treffer ) use ( $diepte ) {
			$attrs = json_decode( $treffer[1], true );
			$slug  = is_array( $attrs ) && isset( $attrs['slug'] ) ? (string) $attrs['slug'] : '';

			if ( '' === $slug ) {
				return $treffer[0];
			}

			$register = WP_Block_Patterns_Registry::get_instance();

			// Niet geregistreerd: dan laten staan. Beter een verwijzing die het
			// nog doet dan een gat in de pagina.
			if ( ! $register->is_registered( $slug ) ) {
				return $treffer[0];
			}

			$patroon = $register->get_registered( $slug );

			if ( empty( $patroon['content'] ) ) {
				return $treffer[0];
			}

			return kbj_patroon_naar_blokken( $patroon['content'], $diepte + 1 );
		},
		$blokken
	);
}

/**
 * De startinhoud van een pagina.
 *
 * @param array $pagina Gegevens van de pagina.
 * @return string Blokmarkering.
 */
function kbj_pagina_inhoud( $pagina ) {
	$blokken = '';

	// Een pagina zonder kop begint meteen met zijn eigen sectie.
	if ( '' !== $pagina['kop'] ) {
		$attrs = array(
			'boven' => isset( $pagina['boven'] ) ? $pagina['boven'] : '',
			'titel' => $pagina['kop'],
			'intro' => $pagina['intro'],
		);

		if ( ! empty( $pagina['bestand'] ) ) {
			$attrs['bestand'] = $pagina['bestand'];
			$attrs['alt']     = isset( $pagina['alt'] ) ? $pagina['alt'] : '';
		}

		if ( ! empty( $pagina['plek'] ) ) {
			$attrs['plek'] = $pagina['plek'];
		}

		if ( ! empty( $pagina['tekening'] ) ) {
			$attrs['illustratie'] = $pagina['tekening'];
		}

		if ( isset( $pagina['knop'] ) && ! $pagina['knop'] ) {
			$attrs['knop'] = false;
		}

		$blokken .= '<!-- wp:kbj/paginakop ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->';
	}

	if ( ! empty( $pagina['patronen'] ) ) {
		foreach ( $pagina['patronen'] as $slug ) {
			$blokken .= sprintf( '<!-- wp:pattern {"slug":"kombij/%s"} /-->', $slug );
		}

		return kbj_patroon_naar_blokken( $blokken );
	}

	// Geen vaste secties: een leeg tekstblok, klaar om in te typen.
	$blokken .= '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->'
		. '<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">'
		. '<!-- wp:paragraph --><p></p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->';

	return $blokken;
}

/**
 * Maakt de pagina's aan die er nog niet zijn.
 */
function kbj_installatie_paginas() {
	foreach ( kbj_paginas() as $pagina ) {
		if ( get_page_by_path( $pagina['slug'] ) ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_title'   => $pagina['titel'],
				'post_name'    => $pagina['slug'],
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => kbj_pagina_inhoud( $pagina ),
			)
		);
	}
}

/** Onder welke naam we onthouden hoe het menu er bij het schrijven uitzag. */
const KBJ_MENU_AFDRUK = 'kbj_menu_afdruk';

/**
 * De items van het hoofdmenu, in de volgorde van het thema.
 *
 * @return string Blokmarkering, leeg als de pagina's er nog niet zijn.
 */
function kbj_menu_markering() {
	$items  = '';
	$aantal = 0;

	foreach ( kbj_paginas() as $pagina ) {
		if ( '' === $pagina['menu'] ) {
			continue;
		}

		$object = get_page_by_path( $pagina['slug'] );

		if ( ! $object ) {
			continue;
		}

		++$aantal;

		$items .= sprintf(
			'<!-- wp:navigation-link {"label":"%s","type":"page","id":%d,"url":"%s","kind":"post-type"} /-->',
			esc_attr( $pagina['menu'] ),
			(int) $object->ID,
			esc_url( get_permalink( $object ) )
		);
	}

	return $aantal > 0 ? $items : '';
}

/**
 * Maakt het hoofdmenu.
 *
 * In een blokthema is een menu een eigen inhoudstype (wp_navigation). Staat er
 * één, dan pakt het navigatieblok hem vanzelf op. Zonder menu toont dat blok een
 * lijst van alle pagina's, inclusief de privacyverklaring, en dat wil je niet in
 * je hoofdmenu.
 */
function kbj_installatie_menu() {
	$bestaand = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish', 'draft' ),
		)
	);

	// Een menu dat WordPress zelf aanmaakte bevat alleen een paginalijst. Dat is
	// geen menu dat iemand heeft ingericht, dus dat vullen we alsnog.
	if ( $bestaand && false === strpos( $bestaand[0]->post_content, 'wp:page-list' ) ) {
		return;
	}

	$items = kbj_menu_markering();

	if ( '' === $items ) {
		return;
	}

	$gegevens = array(
		'post_title'   => __( 'Hoofdmenu', 'kombij' ),
		'post_name'    => 'hoofdmenu',
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_content' => $items,
	);

	if ( $bestaand ) {
		$gegevens['ID'] = $bestaand[0]->ID;
		wp_update_post( $gegevens );
	} else {
		wp_insert_post( $gegevens );
	}

	update_option( KBJ_MENU_AFDRUK, md5( $items ) );
}

/**
 * Houdt de volgorde van het menu gelijk aan die van het thema.
 *
 * Verandert de volgorde in kbj_paginas(), dan hoort het menu mee te veranderen.
 * Maar niet als de klant het menu zelf heeft aangepast: dan is zijn versie de
 * juiste. We onthouden daarom bij het schrijven hoe het menu eruitzag. Wijkt de
 * inhoud daar nu van af, dan heeft iemand hem met de hand veranderd en blijven
 * we eraf.
 */
function kbj_menu_gelijkhouden() {
	$bestaand = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish', 'draft' ),
		)
	);

	if ( ! $bestaand ) {
		return;
	}

	$afdruk = (string) get_option( KBJ_MENU_AFDRUK, '' );
	$nu     = md5( $bestaand[0]->post_content );

	// Zelf aangepast: afblijven.
	if ( '' !== $afdruk && $afdruk !== $nu ) {
		return;
	}

	$items = kbj_menu_markering();

	if ( '' === $items || md5( $items ) === $nu ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $bestaand[0]->ID,
			'post_content' => $items,
		)
	);

	update_option( KBJ_MENU_AFDRUK, md5( $items ) );
}
add_action( 'admin_init', 'kbj_menu_gelijkhouden' );
