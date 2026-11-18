<?php
/**
 * Campi personalizzati (plugin Secure Custom Fields, compatibile con ACF).
 *
 * I gruppi di campi si gestiscono da SCF → Gruppi di campi e vengono salvati anche come file JSON
 * in theme/acf-json/ (Local JSON): così la struttura dei campi sta in git e va sullo stage con il deploy.
 * Sullo stage, se un gruppo compare con "Sincronizza", basta sincronizzarlo per poterlo modificare lì.
 *
 * Se il plugin non è attivo o un campo è vuoto, i template usano i valori predefiniti qui sotto.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Funzioni di lettura
 * ---------------------------------------------------------------------- */

/**
 * Valore di un campo, o il predefinito se il plugin manca o il campo è vuoto.
 *
 * @param string $name     Nome del campo.
 * @param mixed  $fallback Valore predefinito.
 * @param mixed  $post_id  Pagina (predefinito: quella corrente).
 * @return mixed
 */
function toninato_field( $name, $fallback = '', $post_id = false ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}
	$value = get_field( $name, $post_id );
	return ( null === $value || '' === $value || false === $value || array() === $value ) ? $fallback : $value;
}

/**
 * URL di un'immagine scelta in un campo (ID allegato), o di un'immagine del tema.
 *
 * @param int|string $id       ID dell'allegato (o vuoto).
 * @param string     $fallback File in assets/images.
 * @param string     $size     Dimensione.
 * @return string
 */
function toninato_image_url( $id, $fallback, $size = 'large' ) {
	$url = $id ? wp_get_attachment_image_url( (int) $id, $size ) : '';
	return $url ? $url : toninato_img( $fallback );
}

/**
 * Tag <img> di un'immagine scelta in un campo, o di un'immagine del tema.
 *
 * @param int|string $id       ID dell'allegato (o vuoto).
 * @param string     $fallback File in assets/images.
 * @param string     $alt      Testo alternativo di riserva.
 * @param array      $attr     Attributi (width/height usati solo per il file del tema).
 * @return string
 */
function toninato_image_tag( $id, $fallback, $alt = '', $attr = array() ) {
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'size' => 'large', 'width' => '', 'height' => '' ) );
	if ( $id && wp_attachment_is_image( (int) $id ) ) {
		return wp_get_attachment_image( (int) $id, $attr['size'], false, array( 'loading' => $attr['loading'] ) );
	}
	return sprintf(
		'<img src="%s" alt="%s"%s%s loading="%s">',
		esc_url( toninato_img( $fallback ) ),
		esc_attr( $alt ),
		$attr['width'] ? ' width="' . (int) $attr['width'] . '"' : '',
		$attr['height'] ? ' height="' . (int) $attr['height'] . '"' : '',
		esc_attr( $attr['loading'] )
	);
}

/**
 * Link da un campo "Link" ({url, title, target}), con un link di riserva.
 *
 * @param mixed $link     Valore del campo.
 * @param array $fallback Link predefinito.
 * @return array
 */
function toninato_link( $link, $fallback ) {
	return ( is_array( $link ) && ! empty( $link['url'] ) ) ? wp_parse_args( $link, array( 'title' => $fallback['title'], 'target' => '' ) ) : $fallback;
}

/**
 * Attributi href/target di un link.
 *
 * @param array $link Link {url, title, target}.
 * @return string
 */
function toninato_link_attrs( $link ) {
	$out = 'href="' . esc_url( $link['url'], array( 'http', 'https', 'tel', 'mailto' ) ) . '"';
	if ( ! empty( $link['target'] ) ) {
		$out .= ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"';
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Valori predefiniti della Home (= contenuti attuali del sito)
 * ---------------------------------------------------------------------- */

/**
 * Contenuti predefiniti della Home. Le immagini sono file del tema (assets/images).
 *
 * @return array
 */
function toninato_home_defaults() {
	$info = toninato_info();
	return array(
		'hero_image'        => 'hero-studio.jpg',
		'hero_title'        => $info['title'],
		'hero_name'         => $info['name'],
		'hero_lines'        => $info['qualifica'] . "\nConsulenza e assistenza in diritto civile e penale",
		'hero_place'        => $info['city'] . ' (' . $info['province'] . ')',
		'hero_button_1'     => array( 'url' => 'tel:' . ( toninato_phones( 'fisso' )[0]['tel'] ?? '' ), 'title' => 'Chiama lo studio', 'target' => '' ),
		'hero_button_2'     => array( 'url' => toninato_page_url( 'contatti' ), 'title' => 'Contatti', 'target' => '' ),
		'intro_link'        => array( 'url' => toninato_page_url( 'lo-studio' ), 'title' => 'Scopri lo studio', 'target' => '' ),
		'services_overline' => 'Di cosa si occupa lo studio',
		'services_title'    => 'Aree di attività',
		'services_lead'     => 'Assistenza in controversie civili e penali, fino al giudizio in Cassazione.',
		'values_overline'   => 'Perché scegliere lo studio',
		'values_title'      => 'Valori e punti di forza',
		'values'            => array(
			array( 'icon' => 'scale', 'title' => 'Fino in Cassazione', 'text' => 'Avvocato patrocinante in Cassazione: assistenza dal primo all\'ultimo grado di giudizio.' ),
			array( 'icon' => 'shield', 'title' => 'Discrezione ed etica', 'text' => 'Ogni incarico è seguito con grande discrezione, etica e nel pieno rispetto del codice deontologico.' ),
			array( 'icon' => 'chat', 'title' => 'Contatto diretto', 'text' => 'Rapporto diretto con il cliente e reperibilità telefonica immediata: risposta subito o entro un\'ora.' ),
			array( 'icon' => 'users', 'title' => 'Rete di professionisti', 'text' => 'Collaborazione con medici legali, ingegneri, architetti, commercialisti e consulenti del lavoro.' ),
		),
		'areas'             => array(
			array(
				'icon'     => 'document',
				'overline' => 'Privati e aziende',
				'title'    => 'Diritto civile',
				'text'     => 'Assistenza legale in ambito civile a persone fisiche e aziende: famiglia, risarcimento danni, successioni, locazioni, condominio, recupero crediti, contratti, procedure fallimentari, aste e ricerca immobili.',
				'link'     => array( 'url' => toninato_page_url( 'diritto-civile' ), 'title' => 'Approfondisci', 'target' => '' ),
			),
			array(
				'icon'     => 'gavel',
				'overline' => 'Tutela della persona',
				'title'    => 'Diritto penale',
				'text'     => 'Assistenza completa per la redazione di querele e nei processi con costituzione di parte civile, fino all\'ultimo grado di giudizio.',
				'link'     => array( 'url' => toninato_page_url( 'diritto-penale' ), 'title' => 'Approfondisci', 'target' => '' ),
			),
		),
		'reviews_overline'  => 'Recensioni Google',
		'reviews_title'     => 'Dicono dello studio',
	);
}

/**
 * Sopratitolo predefinito dell'intestazione di una pagina interna.
 *
 * @param int $post_id Pagina.
 * @return string
 */
function toninato_page_overline_default( $post_id ) {
	$by_template = array(
		'page-templates/template-servizi.php'  => 'Aree di attività',
		'page-templates/template-servizio.php' => 'Servizi',
		'page-templates/template-contatti.php' => 'Studio legale a Moncalieri',
	);
	$template = get_page_template_slug( $post_id );
	if ( isset( $by_template[ $template ] ) ) {
		return $by_template[ $template ];
	}
	$parent = wp_get_post_parent_id( $post_id );
	return $parent ? get_the_title( $parent ) : '';
}

/**
 * Contenuti predefiniti della pagina Contatti (etichette delle schede).
 *
 * @return array
 */
function toninato_contatti_defaults() {
	return array(
		'address_label'  => 'Indirizzo',
		'address_action' => 'Apri in Google Maps',
		'phone_label'    => 'Telefono studio',
		'phone_action'   => 'Chiama',
		'mobile_label'   => 'Cellulare',
		'mobile_action'  => 'Chiama',
		'email_label'    => 'Email',
		'email_action'   => 'Scrivi',
	);
}

/* -------------------------------------------------------------------------
 * Pagina opzioni "Dati dell'attività" (i campi sono nel gruppo group_tn_studio)
 * ---------------------------------------------------------------------- */

add_action( 'acf/init', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => "Dati dell'attività",
			'menu_title' => "Dati dell'attività",
			'menu_slug'  => 'dati-attivita',
			'capability' => 'edit_pages',
			'icon_url'   => 'dashicons-building',
			'position'   => 21,
			'redirect'   => false,
		) );
	}
} );

/* -------------------------------------------------------------------------
 * Gruppi di campi: Local JSON in theme/acf-json/
 * ---------------------------------------------------------------------- */

add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	return array( get_template_directory() . '/acf-json' );
} );
