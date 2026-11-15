<?php
/**
 * Dati dell'attività: recapiti, testi comuni e aree di attività.
 *
 * I dati si modificano dal pannello in "Dati dell'attività" (pagina opzioni di Secure Custom Fields).
 * I valori qui sotto sono quelli di riserva: si usano se il plugin è spento o un campo è vuoto, e con
 * gli stessi valori tools/seed-content.php compila i campi.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dati dell'attività predefiniti (= campi della pagina opzioni "Dati dell'attività").
 *
 * @return array
 */
function toninato_info_defaults() {
	return array(
		// Studio.
		'name'             => 'Stefania Toninato',
		'title'            => 'Avvocato',
		'qualifica'        => 'Patrocinante in Cassazione',
		'tagline'          => 'Consulenza e assistenza legale in diritto civile e penale',
		'legal_name'       => 'Toninato Avv. Stefania',
		'piva'             => '08798450014',
		// Indirizzo.
		'address'          => 'Via Cavour, 38',
		'cap'              => '10024',
		'city'             => 'Moncalieri',
		'province'         => 'TO',
		'maps'             => 'https://www.google.com/maps/search/?api=1&query=Via+Cavour+38+10024+Moncalieri+TO',
		'appointment_note' => 'Si riceve solo su appuntamento',
		// Recapiti.
		'phones'           => array(
			array( 'number' => '011 647 4514', 'type' => 'fisso' ),
			array( 'number' => '011 068 9987', 'type' => 'fisso' ),
			array( 'number' => '349 749 5322', 'type' => 'cellulare' ),
		),
		'email'            => 'stefania.toninato@yahoo.it',
		// Riquadro contatti (CTA).
		'cta_title'        => 'Hai bisogno di un avvocato a Moncalieri?',
		'cta_subtitle'     => 'Contatta lo studio:',
		'cta_note'         => 'Lo studio riceve solo su appuntamento. Si garantisce risposta immediata o un nuovo contatto entro un\'ora.',
		// Riquadro laterale delle pagine interne.
		'box_title'        => 'Fissa una consulenza',
		'box_text'         => 'Lo studio riceve solo su appuntamento. Risposta immediata o un nuovo contatto entro un\'ora.',
		'box_button'       => 'Contatti',
		// Pagine dei servizi.
		'service_side'     => 'Altri servizi',
		'service_back'     => 'Tutti i servizi',
		// Solo da codice.
		'reviews'          => '[trustindex no-registration=google]', // shortcode del plugin "Widgets for Google Reviews"
	);
}

/**
 * Dati dell'attività: quelli salvati in "Dati dell'attività", con i predefiniti per i campi vuoti.
 *
 * @return array
 */
function toninato_info() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$info  = toninato_info_defaults();
	$ready = ! function_exists( 'get_field' ) || did_action( 'acf/init' ); // campi leggibili (o plugin assente)
	if ( function_exists( 'get_field' ) && did_action( 'acf/init' ) ) {
		foreach ( array_keys( $info ) as $key ) {
			if ( 'reviews' === $key ) {
				continue;
			}
			$value = get_field( $key, 'option' );
			if ( null !== $value && '' !== $value && false !== $value && array() !== $value ) {
				$info[ $key ] = $value;
			}
		}
	}
	// Telefoni: solo le righe con un numero, con il link tel: calcolato.
	$info['phones'] = array_values( array_filter( array_map( function ( $p ) {
		$number = trim( (string) ( $p['number'] ?? '' ) );
		return '' === $number ? null : array(
			'label' => $number,
			'tel'   => toninato_tel( $number ),
			'type'  => ( $p['type'] ?? '' ) === 'cellulare' ? 'cellulare' : 'fisso',
		);
	}, (array) $info['phones'] ) ) );
	if ( $ready ) {
		$cache = $info;
	}
	return $info;
}

/**
 * Numero per i link tel: (+39 aggiunto se manca il prefisso internazionale).
 *
 * @param string $number Numero come scritto (es. "011 647 4514").
 * @return string
 */
function toninato_tel( $number ) {
	$digits = preg_replace( '/[^\d+]/', '', $number );
	if ( str_starts_with( $digits, '00' ) ) {
		return '+' . substr( $digits, 2 );
	}
	return str_starts_with( $digits, '+' ) ? $digits : '+39' . $digits;
}

/**
 * Telefoni dell'attività, tutti o di un tipo ("fisso" o "cellulare").
 *
 * @param string $type Tipo (vuoto: tutti).
 * @return array Elenco di { label, tel, type }.
 */
function toninato_phones( $type = '' ) {
	$phones = toninato_info()['phones'];
	return $type ? array_values( array_filter( $phones, fn( $p ) => $p['type'] === $type ) ) : $phones;
}

/**
 * Aree di attività dello studio.
 *
 * @return array
 */
function toninato_services() {
	return array(
		array(
			'slug'  => 'diritto-di-famiglia',
			'title' => 'Diritto di famiglia',
			'image' => 'servizio-famiglia.jpg',
			'text'  => 'Separazioni consensuali e giudiziali, divorzi congiunti e contenziosi, modifica delle condizioni di separazione, famiglie di fatto.',
		),
		array(
			'slug'  => 'risarcimento-danni',
			'title' => 'Risarcimento danni',
			'image' => 'servizio-risarcimento.jpg',
			'text'  => 'Da circolazione veicoli, responsabilità medica e civile in genere.',
		),
		array(
			'slug'  => 'successioni-e-donazioni',
			'title' => 'Successioni e donazioni',
			'image' => 'servizio-successioni.jpg',
			'text'  => 'Successione legittima e testamentaria, donazioni e tutela dei diritti dei legittimari.',
		),
		array(
			'slug'  => 'interdizioni-e-inabilitazioni',
			'title' => 'Interdizioni e inabilitazioni',
			'image' => 'servizio-interdizioni.jpg',
			'text'  => 'Procedimenti davanti al tribunale per la protezione di chi non è in grado di provvedere ai propri interessi.',
		),
		array(
			'slug'  => 'diritto-contrattuale',
			'title' => 'Diritto contrattuale',
			'image' => 'servizio-contrattuale.jpg',
			'text'  => 'Redazione e interpretazione dei contratti, responsabilità pre-contrattuale ed extra-contrattuale, inadempimento.',
		),
		array(
			'slug'  => 'diritto-fallimentare',
			'title' => 'Diritto fallimentare',
			'image' => 'servizio-fallimentare.jpg',
			'text'  => 'Domande di ammissione al passivo e opposizioni allo stato passivo.',
		),
		array(
			'slug'  => 'recupero-crediti',
			'title' => 'Recupero crediti',
			'image' => 'servizio-crediti.jpg',
			'text'  => 'Procedure monitorie, esecuzioni immobiliari e mobiliari.',
		),
	);
}

/**
 * Voci del menu usate finché non viene creato un menu in Aspetto → Menu.
 *
 * @return array
 */
function toninato_default_menu() {
	return array(
		array( 'label' => 'Home', 'url' => home_url( '/' ) ),
		array( 'label' => 'Lo Studio', 'url' => toninato_page_url( 'lo-studio' ) ),
		array( 'label' => 'Garanzia di professionalità', 'url' => toninato_page_url( 'garanzia-di-professionalita' ) ),
		array( 'label' => 'Servizi', 'url' => toninato_page_url( 'servizi' ) ),
		array( 'label' => 'Diritto civile', 'url' => toninato_page_url( 'diritto-civile' ) ),
		array( 'label' => 'Diritto penale', 'url' => toninato_page_url( 'diritto-penale' ) ),
		array( 'label' => 'Contatti', 'url' => toninato_page_url( 'contatti' ) ),
	);
}
