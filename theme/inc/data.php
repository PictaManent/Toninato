<?php
/**
 * Dati dello studio: contatti e aree di attività.
 * Unico punto da modificare quando cambiano recapiti o servizi.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recapiti e dati dello studio.
 *
 * @return array
 */
function toninato_info() {
	return array(
		'name'      => 'Stefania Toninato',
		'title'     => 'Avvocato',
		'qualifica' => 'Patrocinante in Cassazione',
		'since'     => '2004',
		'address'   => 'Via Cavour, 38',
		'cap'       => '10024',
		'city'      => 'Moncalieri',
		'province'  => 'TO',
		'phone'     => array( 'label' => '011 647 4514', 'tel' => '+390116474514' ),
		'mobile'    => array( 'label' => '349 749 5322', 'tel' => '+393497495322' ),
		'email'     => 'stefania.toninato@yahoo.it',
		'piva'      => '08798450014',
		'maps'      => 'https://www.google.com/maps/search/?api=1&query=Via+Cavour+38+10024+Moncalieri+TO',
	);
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
		array(
			'slug'  => 'ristrutturazione-del-debito',
			'title' => 'Ristrutturazione del debito',
			'image' => 'servizio-debito.jpg',
			'text'  => 'Accordi per modificare le condizioni originarie di un prestito e alleggerire l\'onere del debitore.',
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
		array( 'label' => 'Lo Studio', 'url' => home_url( '/lo-studio/' ) ),
		array( 'label' => 'Garanzia di professionalità', 'url' => home_url( '/garanzia-di-professionalita/' ) ),
		array( 'label' => 'Diritto civile', 'url' => home_url( '/diritto-civile/' ) ),
		array( 'label' => 'Diritto penale', 'url' => home_url( '/diritto-penale/' ) ),
		array( 'label' => 'Contatti', 'url' => home_url( '/contatti/' ) ),
	);
}
