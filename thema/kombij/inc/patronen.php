<?php
/**
 * Bouwstenen voor de patronen.
 *
 * Een patroon is blokopmaak: een HTML-commentaar met instellingen, en daarbinnen
 * de HTML die daar precies bij hoort. Met de hand is dat foutgevoelig en lang.
 * Met deze kleine functies schrijft een patroon alleen nog wat er staat, en
 * klopt de opmaak eromheen altijd.
 *
 * Alles wat hieruit komt is gewone blokopmaak. In de editor zijn het dus losse
 * blokken die de klant kan aanpassen.
 *
 * @package kombij
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Een blokcommentaar met instellingen.
 *
 * @param string $naam  Bloknaam zonder "core/".
 * @param array  $attrs Instellingen.
 * @return string
 */
function kbj_blok_attrs( $naam, $attrs ) {
	$json = $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';

	return $naam . $json;
}

/**
 * Een sectie over de volle breedte.
 *
 * @param string $inhoud  De blokken erin.
 * @param array  $opties  achtergrond, klasse, breed, boven, onder, ornament.
 * @return string
 */
function kbj_sectie( $inhoud, $opties = array() ) {
	$opties = wp_parse_args(
		$opties,
		array(
			'achtergrond' => '',
			'klasse'      => '',
			'breed'       => true,
			'boven'       => '60',
			'onder'       => '60',
			'ornament'    => false,
			'anker'       => '',
		)
	);

	$klassen = trim( 'kbj-sectie ' . $opties['klasse'] . ( $opties['ornament'] ? ' kbj-ornament' : '' ) );

	$attrs = array(
		'align'     => 'full',
		'className' => $klassen,
		'style'     => array(
			'spacing' => array(
				'padding' => array(
					'top'    => 'var:preset|spacing|' . $opties['boven'],
					'bottom' => 'var:preset|spacing|' . $opties['onder'],
				),
			),
		),
		'layout'    => array( 'type' => 'constrained' ),
	);

	if ( '' !== $opties['anker'] ) {
		$attrs['anchor'] = $opties['anker'];
	}

	if ( $opties['breed'] ) {
		$attrs['layout']['contentSize'] = '1240px';
	}

	$html_klassen = 'wp-block-group alignfull ' . $klassen;

	if ( '' !== $opties['achtergrond'] ) {
		$attrs['backgroundColor'] = $opties['achtergrond'];
		$html_klassen            .= ' has-' . $opties['achtergrond'] . '-background-color has-background';

		if ( 'nachtblauw' === $opties['achtergrond'] ) {
			$attrs['textColor'] = 'wit';
			$html_klassen      .= ' has-wit-color has-text-color';
		}
	}

	return sprintf(
		"<!-- wp:group %s -->\n<div%s class=\"%s\" style=\"padding-top:var(--wp--preset--spacing--%s);padding-bottom:var(--wp--preset--spacing--%s)\">%s</div>\n<!-- /wp:group -->\n\n",
		wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
		'' !== $opties['anker'] ? ' id="' . esc_attr( $opties['anker'] ) . '"' : '',
		esc_attr( $html_klassen ),
		esc_attr( $opties['boven'] ),
		esc_attr( $opties['onder'] ),
		$inhoud
	);
}

/**
 * Een groep zonder eigen vormgeving, om blokken bij elkaar te houden.
 *
 * @param string $inhoud De blokken erin.
 * @param string $klasse Klasse.
 * @return string
 */
function kbj_groep( $inhoud, $klasse = '' ) {
	$attrs = array( 'layout' => array( 'type' => 'default' ) );

	if ( '' !== $klasse ) {
		$attrs['className'] = $klasse;
	}

	return sprintf(
		"<!-- wp:%s -->\n<div class=\"wp-block-group%s\">%s</div>\n<!-- /wp:group -->\n",
		kbj_blok_attrs( 'group', $attrs ),
		'' !== $klasse ? ' ' . esc_attr( $klasse ) : '',
		$inhoud
	);
}

/**
 * Een kop.
 *
 * @param int    $niveau 1 tot 3.
 * @param string $tekst  De kop, mag <span class="kbj-nadruk"> bevatten.
 * @param string $klasse Klasse.
 * @param bool   $midden Gecentreerd.
 * @return string
 */
function kbj_kop( $niveau, $tekst, $klasse = '', $midden = false ) {
	$attrs   = array();
	$klassen = 'wp-block-heading';

	if ( 2 !== $niveau ) {
		$attrs['level'] = $niveau;
	}

	if ( $midden ) {
		$attrs['textAlign'] = 'center';
		$klassen           .= ' has-text-align-center';
	}

	if ( '' !== $klasse ) {
		$attrs['className'] = $klasse;
		$klassen           .= ' ' . $klasse;
	}

	return sprintf(
		"<!-- wp:%1\$s -->\n<h%2\$d class=\"%3\$s\">%4\$s</h%2\$d>\n<!-- /wp:heading -->\n",
		kbj_blok_attrs( 'heading', $attrs ),
		$niveau,
		esc_attr( $klassen ),
		$tekst
	);
}

/**
 * Een alinea.
 *
 * @param string $tekst  De tekst, mag eenvoudige opmaak bevatten.
 * @param string $klasse Klasse.
 * @param bool   $midden Gecentreerd.
 * @return string
 */
function kbj_p( $tekst, $klasse = '', $midden = false ) {
	$attrs   = array();
	$klassen = array();

	if ( $midden ) {
		$attrs['align'] = 'center';
		$klassen[]      = 'has-text-align-center';
	}

	if ( '' !== $klasse ) {
		$attrs['className'] = $klasse;
		$klassen[]          = $klasse;
	}

	return sprintf(
		"<!-- wp:%s -->\n<p%s>%s</p>\n<!-- /wp:paragraph -->\n",
		kbj_blok_attrs( 'paragraph', $attrs ),
		$klassen ? ' class="' . esc_attr( implode( ' ', $klassen ) ) . '"' : '',
		$tekst
	);
}

/**
 * De kop van een sectie: een regel erboven, de kop en een korte inleiding.
 *
 * @param string $boven  De regel erboven.
 * @param string $titel  De kop.
 * @param string $intro  De inleiding, mag leeg zijn.
 * @param bool   $midden Gecentreerd.
 * @param int    $niveau Kopniveau.
 * @return string
 */
function kbj_sectiekop( $boven, $titel, $intro = '', $midden = true, $niveau = 2 ) {
	$inhoud = '';

	if ( '' !== $boven ) {
		$inhoud .= kbj_p( $boven, 'kbj-boven' );
	}

	$inhoud .= kbj_kop( $niveau, $titel );

	if ( '' !== $intro ) {
		$inhoud .= kbj_p( $intro );
	}

	return kbj_groep( $inhoud, 'kbj-sectiekop' . ( $midden ? ' kbj-midden' : '' ) );
}

/**
 * Een lijst.
 *
 * @param string[] $items  De regels, mogen eenvoudige opmaak bevatten.
 * @param string   $klasse Klasse op de lijst.
 * @param bool     $genummerd Een genummerde lijst.
 * @return string
 */
function kbj_lijst( $items, $klasse = '', $genummerd = false ) {
	$attrs = array();

	if ( $genummerd ) {
		$attrs['ordered'] = true;
	}

	if ( '' !== $klasse ) {
		$attrs['className'] = $klasse;
	}

	$regels = '';

	foreach ( $items as $item ) {
		$regels .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->\n";
	}

	$tag = $genummerd ? 'ol' : 'ul';

	return sprintf(
		"<!-- wp:%1\$s -->\n<%2\$s class=\"wp-block-list%3\$s\">%4\$s</%2\$s>\n<!-- /wp:list -->\n",
		kbj_blok_attrs( 'list', $attrs ),
		$tag,
		'' !== $klasse ? ' ' . esc_attr( $klasse ) : '',
		$regels
	);
}

/**
 * Een rij knoppen.
 *
 * @param array[] $knoppen Elk: tekst, url, en optioneel 'rand' => true.
 * @param bool    $midden  Gecentreerd.
 * @return string
 */
function kbj_knoppen( $knoppen, $midden = false ) {
	$html = '';

	foreach ( $knoppen as $knop ) {
		$rand  = ! empty( $knop['rand'] );
		$attrs = $rand ? array( 'className' => 'is-style-outline' ) : array();

		$html .= sprintf(
			"<!-- wp:%s -->\n<div class=\"wp-block-button%s\"><a class=\"wp-block-button__link wp-element-button\" href=\"%s\">%s</a></div>\n<!-- /wp:button -->\n",
			kbj_blok_attrs( 'button', $attrs ),
			$rand ? ' is-style-outline' : '',
			esc_url( $knop['url'] ),
			$knop['tekst']
		);
	}

	$attrs = array(
		'className' => 'kbj-acties',
		'layout'    => array(
			'type'           => 'flex',
			'justifyContent' => $midden ? 'center' : 'left',
		),
	);

	return sprintf(
		"<!-- wp:%s -->\n<div class=\"wp-block-buttons kbj-acties\">%s</div>\n<!-- /wp:buttons -->\n",
		kbj_blok_attrs( 'buttons', $attrs ),
		$html
	);
}

/**
 * Kolommen naast elkaar.
 *
 * @param array[] $kolommen Elk: inhoud, en optioneel breedte en klasse.
 * @param string  $klasse   Klasse op de rij.
 * @param bool    $midden   Verticaal gecentreerd.
 * @return string
 */
function kbj_kolommen( $kolommen, $klasse = '', $midden = false ) {
	$html = '';

	foreach ( $kolommen as $kolom ) {
		$attrs   = array();
		$klassen = 'wp-block-column';
		$stijl   = '';

		if ( ! empty( $kolom['breedte'] ) ) {
			$attrs['width'] = $kolom['breedte'];
			$stijl          = ' style="flex-basis:' . esc_attr( $kolom['breedte'] ) . '"';
		}

		if ( $midden ) {
			$attrs['verticalAlignment'] = 'center';
			$klassen                   .= ' is-vertically-aligned-center';
		}

		if ( ! empty( $kolom['klasse'] ) ) {
			$attrs['className'] = $kolom['klasse'];
			$klassen           .= ' ' . $kolom['klasse'];
		}

		$html .= sprintf(
			"<!-- wp:%s -->\n<div class=\"%s\"%s>%s</div>\n<!-- /wp:column -->\n",
			kbj_blok_attrs( 'column', $attrs ),
			esc_attr( $klassen ),
			$stijl,
			$kolom['inhoud']
		);
	}

	$attrs   = array();
	$klassen = 'wp-block-columns';

	if ( $midden ) {
		$attrs['verticalAlignment'] = 'center';
		$klassen                   .= ' are-vertically-aligned-center';
	}

	if ( '' !== $klasse ) {
		$attrs['className'] = $klasse;
		$klassen           .= ' ' . $klasse;
	}

	return sprintf(
		"<!-- wp:%s -->\n<div class=\"%s\">%s</div>\n<!-- /wp:columns -->\n",
		kbj_blok_attrs( 'columns', $attrs ),
		esc_attr( $klassen ),
		$html
	);
}

/**
 * Een foto uit assets/foto, recht of in de vorm van een spitsboog.
 *
 * @param string $bestand Bestandsnaam in assets/foto.
 * @param string $alt     Wat er te zien is.
 * @param array  $opties  vorm, verhouding, positie, eerst, bijschrift.
 * @return string
 */
function kbj_foto( $bestand, $alt, $opties = array() ) {
	$attrs = array_merge(
		array(
			'bestand' => $bestand,
			'alt'     => $alt,
		),
		$opties
	);

	return '<!-- wp:' . kbj_blok_attrs( 'kbj/beeld', $attrs ) . " /-->\n";
}

/**
 * Een vraag met het antwoord eronder, die het thema ook als FAQ aan Google geeft.
 *
 * @param string $vraag    De vraag.
 * @param string $antwoord Het antwoord, mag eenvoudige opmaak bevatten.
 * @return string
 */
function kbj_vraag( $vraag, $antwoord ) {
	return sprintf(
		"<!-- wp:details {\"className\":\"kbj-vraag\"} -->\n<details class=\"wp-block-details kbj-vraag\"><summary>%s</summary>%s</details>\n<!-- /wp:details -->\n",
		esc_html( $vraag ),
		kbj_p( $antwoord )
	);
}

/**
 * Een blok vragen onder een kop.
 *
 * @param string  $titel  De kop.
 * @param array[] $vragen Elk: vraag en antwoord.
 * @param string  $boven  De regel erboven.
 * @return string
 */
function kbj_vragenblok( $titel, $vragen, $boven = 'Veelgestelde vragen' ) {
	$inhoud = kbj_sectiekop( $boven, $titel );

	foreach ( $vragen as $paar ) {
		$inhoud .= kbj_vraag( $paar[0], $paar[1] );
	}

	return kbj_sectie(
		$inhoud,
		array(
			'klasse' => 'kbj-vragen',
			'breed'  => false,
		)
	);
}

/**
 * Een citaat met een naam eronder.
 *
 * @param string $tekst Het citaat.
 * @param string $naam  Wie het zei.
 * @return string
 */
function kbj_citaat( $tekst, $naam ) {
	return sprintf(
		"<!-- wp:quote {\"className\":\"kbj-citaat\"} -->\n<blockquote class=\"wp-block-quote kbj-citaat\">%s<cite>%s</cite></blockquote>\n<!-- /wp:quote -->\n",
		kbj_p( $tekst ),
		esc_html( $naam )
	);
}

/**
 * Een kader met een lichte achtergrond, voor een prijs of een afspraak.
 *
 * @param string $inhoud De blokken erin.
 * @param string $extra  Extra klasse.
 * @return string
 */
function kbj_kader( $inhoud, $extra = '' ) {
	return kbj_groep( $inhoud, trim( 'kbj-kader ' . $extra ) );
}

/**
 * Het telefoonnummer zoals het in de tekst staat.
 *
 * @return string
 */
function kbj_tel_tekst() {
	return kbj_optie( 'telefoon', '06 25 52 05 63' );
}

/**
 * Het telefoonnummer als link die op een telefoon meteen belt.
 *
 * Internationaal genoteerd, dan werkt hij ook voor wie in het buitenland is.
 *
 * @return string
 */
function kbj_tel_url() {
	$cijfers = preg_replace( '/[^\d+]/', '', kbj_tel_tekst() );

	if ( 0 === strpos( $cijfers, '0' ) ) {
		$cijfers = '+31' . substr( $cijfers, 1 );
	}

	return 'tel:' . $cijfers;
}

/**
 * Het e-mailadres als link.
 *
 * @return string
 */
function kbj_mail_link() {
	$mail = kbj_optie( 'email', 'info@kombijmaasbommel.nl' );

	return sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $mail ) );
}

/**
 * De vier manieren om zorg te betalen, in gewone woorden.
 *
 * Op één plek, want ze staan op de startpagina en op de pagina over kosten.
 *
 * @return string[]
 */
function kbj_regelingen() {
	return array(
		'<strong>WLZ</strong><em>Wet langdurige zorg</em><span>Voor wie blijvend 24 uur per dag zorg of toezicht nodig heeft. Het CIZ beoordeelt dat. Geldt voor wonen, logeren en dagbesteding.</span>',
		'<strong>WMO</strong><em>Wet maatschappelijke ondersteuning</em><span>Via uw gemeente, voor wie nog thuis woont. Vaak voor dagbesteding, soms voor logeren.</span>',
		'<strong>PGB</strong><em>Persoonsgebonden budget</em><span>U krijgt zelf een budget voor zorg en kiest waar u die inkoopt. Dat kan ook bij KomBij.</span>',
		'<strong>Particulier</strong><em>Zelf betalen</em><span>Zonder indicatie kan het ook. U betaalt dan zelf, en we spreken vooraf een duidelijke prijs af.</span>',
	);
}
