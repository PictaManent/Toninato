<?php
/**
 * Crea pagine, immagini e menu del sito Toninato.
 *
 *   docker compose run --rm wpcli wp eval-file tools/seed-content.php
 *   docker compose run --rm wpcli wp eval-file tools/seed-content.php update   # riscrive anche i testi
 *
 * Senza "update" crea solo ciò che manca e non tocca le pagine già esistenti.
 * Autore di pagine e immagini: l'utente indicato in TONINATO_AUTHOR.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}

const TONINATO_AUTHOR = 'rmweb';

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$update = in_array( 'update', $args ?? array(), true );

$author = get_user_by( 'login', TONINATO_AUTHOR );
if ( ! $author ) {
	WP_CLI::error( 'Utente ' . TONINATO_AUTHOR . ' non trovato.' );
}
wp_set_current_user( $author->ID );

/* -------------------------------------------------------------------------
 * Blocchi Gutenberg
 * ---------------------------------------------------------------------- */

function tn_p( $text, $class = '' ) {
	$attr = $class ? ' {"className":"' . $class . '"}' : '';
	$cls  = $class ? ' class="' . $class . '"' : '';
	return "<!-- wp:paragraph{$attr} -->\n<p{$cls}>{$text}</p>\n<!-- /wp:paragraph -->\n\n";
}

function tn_h( $text, $level = 2 ) {
	$attr = 2 === $level ? '' : ' {"level":' . $level . '}';
	return "<!-- wp:heading{$attr} -->\n<h{$level} class=\"wp-block-heading\">{$text}</h{$level}>\n<!-- /wp:heading -->\n\n";
}

function tn_ul( $items ) {
	$out = "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>{$item}</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</ul>\n<!-- /wp:list -->\n\n";
}

function tn_img( $id, $alt ) {
	$url = wp_get_attachment_image_url( $id, 'large' );
	return "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n"
		. "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $url ) . "\" alt=\"" . esc_attr( $alt ) . "\" class=\"wp-image-{$id}\"/></figure>\n"
		. "<!-- /wp:image -->\n\n";
}

function tn_gallery( $ids ) {
	$out = "<!-- wp:gallery {\"columns\":2,\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-2 is-cropped\">";
	foreach ( $ids as $id ) {
		$url  = wp_get_attachment_image_url( $id, 'large' );
		$alt  = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$out .= "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n"
			. "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $url ) . "\" alt=\"" . esc_attr( $alt ) . "\" class=\"wp-image-{$id}\"/></figure>\n"
			. "<!-- /wp:image -->";
	}
	return $out . "</figure>\n<!-- /wp:gallery -->\n\n";
}

/* -------------------------------------------------------------------------
 * Immagini: importate una sola volta dalla cartella del tema
 * ---------------------------------------------------------------------- */

function tn_media( $file, $alt ) {
	$found = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'meta_key'       => '_toninato_source',
		'meta_value'     => $file,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	if ( $found ) {
		return (int) $found[0];
	}

	$src = get_template_directory() . '/assets/images/' . $file;
	if ( ! is_file( $src ) ) {
		WP_CLI::warning( "Immagine mancante: $file" );
		return 0;
	}
	$tmp = wp_tempnam( $file );
	copy( $src, $tmp );
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, $alt );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		WP_CLI::warning( "Import fallito per $file: " . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_toninato_source', $file );
	WP_CLI::log( "  immagine importata: $file (#$id)" );
	return (int) $id;
}

$img = array(
	'avvocato'      => tn_media( 'avvocato-toninato.jpg', 'L\'Avvocato Stefania Toninato nel suo studio di Moncalieri' ),
	'targa'         => tn_media( 'targa-studio.jpg', 'Targa dello studio dell\'Avvocato Stefania Toninato' ),
	'biglietto'     => tn_media( 'biglietto-studio.jpg', 'Biglietto da visita dello Studio Legale Toninato' ),
	'ufficio'       => tn_media( 'studio-ufficio.jpg', 'Ufficio dell\'Avvocato Toninato' ),
	'ingresso'      => tn_media( 'studio-ingresso.jpg', 'Sala d\'attesa dello studio' ),
	'scrivania'     => tn_media( 'studio-scrivania.jpg', 'Scrivania dello studio legale' ),
	'riunioni'      => tn_media( 'studio-sala-riunioni.jpg', 'Sala riunioni dello studio' ),
);
foreach ( toninato_services() as $s ) {
	$img[ 'servizio-' . $s['slug'] ] = tn_media( $s['image'], $s['title'] );
}

/* -------------------------------------------------------------------------
 * Pagine
 * ---------------------------------------------------------------------- */

$pages = array();

$pages['home'] = array(
	'title'    => 'Home',
	'template' => 'page-templates/template-home.php',
	'image'    => $img['avvocato'],
	'order'    => 0,
	'content'  =>
		tn_h( 'Assistenza e consulenza legale in diritto civile e penale a Moncalieri', 1 )
		. tn_p( 'L\'Avvocato <strong>Stefania Toninato</strong> offre un impeccabile e professionale servizio di consulenza legale presso lo studio in <strong>via Cavour 38, a Moncalieri</strong>, in provincia di Torino. Grazie a una consolidata esperienza nel settore e a un percorso di formazione in costante aggiornamento, garantisce assistenza legale all\'insegna della <strong>serietà</strong>, della <strong>discrezione</strong> e della <strong>professionalità</strong>, dal primo all\'ultimo grado di giudizio.' )
		. tn_h( 'Garanzia di professionalità' )
		. tn_p( 'Laureata a pieni voti presso l\'<strong>Università degli Studi di Torino</strong>, con studio a Moncalieri dal 2004, l\'Avvocato Toninato assiste <strong>privati e aziende</strong> in controversie civili e penali, <strong>fino al giudizio in Cassazione</strong>. Avvalendosi della collaborazione di medici legali, ingegneri, architetti, commercialisti e consulenti del lavoro, lo studio offre un servizio di consulenza e assistenza legale completo.' ),
);

$pages['lo-studio'] = array(
	'title'    => 'Lo Studio',
	'excerpt'  => 'Assistenza legale fino all\'ultimo grado di giudizio in Cassazione.',
	'image'    => $img['ufficio'],
	'order'    => 10,
	'content'  =>
		tn_p( 'Lo studio legale dell\'avvocato Toninato ha la capacità di assistervi fino all\'ultimo grado di giudizio in Cassazione.', 'lead' )
		. tn_p( 'All\'insegna della discrezione e mettendo in campo competenze mantenute in costante aggiornamento, l\'avvocato Toninato si occupa della risoluzione di controversie legali in ambito di diritto civile e penale. Lo studio garantisce un servizio di consulenza completo ed altamente professionale.' )
		. tn_p( 'L\'avvocato, inoltre, mantiene un contatto diretto col cliente ed offre reperibilità telefonica immediata.' )
		. tn_gallery( array( $img['ufficio'], $img['ingresso'], $img['scrivania'], $img['riunioni'] ) ),
);

$pages['garanzia-di-professionalita'] = array(
	'title'    => 'Garanzia di professionalità',
	'excerpt'  => 'Formazione, esperienza e una rete di professionisti al servizio del cliente.',
	'image'    => $img['avvocato'],
	'order'    => 20,
	'content'  =>
		tn_p( 'Laureata a pieni voti presso l\'Università degli Studi di Torino, l\'avvocato Toninato, con studio in Moncalieri dall\'anno 2004, vanta un eccellente percorso di formazione e un\'esperienza che le permettono di garantire un\'impeccabile assistenza legale rivolta sia a privati che aziende.', 'lead' )
		. tn_p( 'Avvalendosi della collaborazione con stimati professionisti quali medici legali, ingegneri, architetti, commercialisti e consulenti del lavoro, lo studio Toninato è in grado di offrire un servizio di consulenza e assistenza legale completo.' )
		. tn_p( 'Con grande discrezione, etica e rispetto del codice deontologico, l\'avvocato Toninato è a disposizione per assistervi in controversie civili e penali, fino al giudizio in Cassazione.' )
		. tn_h( 'Su appuntamento' )
		. tn_p( 'Lo studio riceve solo su appuntamento: per fissare una consulenza è possibile mettersi in contatto con lo studio tramite uno dei recapiti indicati nella pagina dei contatti. <strong>Si garantisce risposta immediata o un nuovo contatto entro un\'ora.</strong>' ),
);

$pages['servizi'] = array(
	'title'    => 'Servizi',
	'excerpt'  => 'Assistenza in controversie civili e penali, fino al giudizio in Cassazione.',
	'template' => 'page-templates/template-servizi.php',
	'image'    => $img['biglietto'],
	'order'    => 30,
	'content'  =>
		tn_p( 'L\'Avvocato Toninato è a disposizione per assistervi in controversie civili e penali, fino al giudizio in Cassazione. Queste le principali aree di attività dello studio.' ),
);

$service_content = array(
	'diritto-di-famiglia'           => tn_p( 'Separazioni consensuali e giudiziali, divorzi congiunti e contenziosi, modifica delle condizioni di separazione, problematiche inerenti alle famiglie di fatto.', 'lead' ),
	'risarcimento-danni'            => tn_p( 'Risarcimento dei danni da circolazione veicoli, responsabilità medica e civile in genere.', 'lead' ),
	'successioni-e-donazioni'       =>
		tn_p( 'Quella della donazione non può essere una scorciatoia per aggirare la successione, che prevede che una parte di eredità venga comunque assegnata ai cosiddetti legittimari e ad alcuni parenti stretti.', 'lead' )
		. tn_h( 'La successione legittima', 3 )
		. tn_p( 'È la successione regolata dalla legge, quando manca in tutto o in parte il testamento. Successori legittimi sono quei soggetti che, in caso di morte di una persona che non abbia fatto testamento o non abbia disposto di tutti i suoi beni, hanno titolo a succedere in base alle norme. Rientrano in questa categoria il coniuge, i figli, gli ascendenti, i fratelli o i loro discendenti; in assenza subentrano gli altri parenti fino al sesto grado.' )
		. tn_h( 'La successione testamentaria', 3 )
		. tn_p( 'È la successione regolata dal testamento, cioè dall\'autonomia della persona che muore. La legge riconosce, anche in presenza di testamento, dei diritti ai cosiddetti legittimari e ad alcuni parenti stretti (coniuge, figli e, in mancanza, agli ascendenti) una quota del patrimonio.' ),
	'interdizioni-e-inabilitazioni' =>
		tn_h( 'Interdizione', 3 )
		. tn_p( 'La pronuncia di interdizione è la forma di protezione prevista per coloro che per abituale infermità di mente sono del tutto incapaci di provvedere ai propri interessi. L\'interdizione è pronunciata all\'esito di un procedimento avanti al tribunale competente. La pronuncia comporta, da parte del Giudice tutelare, la nomina di un tutore (che in via provvisoria può essere nominato dal Giudice della causa di interdizione).' )
		. tn_h( 'Inabilitazione', 3 )
		. tn_p( 'La pronuncia di inabilitazione è la forma di protezione prevista per gli infermi di mente la cui condizione non è così grave da giustificare una pronuncia di interdizione (ove non sia misura sufficiente la nomina di amministratore di sostegno). È pronunciata all\'esito di un procedimento avanti al tribunale competente. La pronuncia comporta, da parte del Giudice tutelare, la nomina di un curatore (che in via provvisoria può essere nominato dal Giudice della causa di inabilitazione).' ),
	'diritto-contrattuale'          => tn_p( 'Redazione e interpretazione dei contratti, responsabilità pre-contrattuale ed extra-contrattuale, risarcimento per inadempimento contrattuale.', 'lead' ),
	'diritto-fallimentare'          => tn_p( 'Domande di ammissione al passivo e opposizioni allo stato passivo.', 'lead' ),
	'recupero-crediti'              => tn_p( 'Procedure monitorie, esecuzioni immobiliari e mobiliari.', 'lead' ),
	'ristrutturazione-del-debito'   => tn_p( 'La ristrutturazione del debito è una procedura che prevede un accordo con il quale le condizioni originarie di un prestito (tassi, scadenze, divisa, periodo di garanzia) vengono modificate per alleggerire l\'onere del debitore.', 'lead' ),
);

$i = 1;
foreach ( toninato_services() as $s ) {
	$pages[ 'servizi/' . $s['slug'] ] = array(
		'title'    => $s['title'],
		'slug'     => $s['slug'],
		'parent'   => 'servizi',
		'excerpt'  => $s['text'],
		'template' => 'page-templates/template-servizio.php',
		'image'    => $img[ 'servizio-' . $s['slug'] ],
		'order'    => $i++,
		'content'  => $service_content[ $s['slug'] ],
	);
}

$pages['diritto-civile'] = array(
	'title'    => 'Diritto civile',
	'excerpt'  => 'Assistenza legale in ambito civile a privati e aziende.',
	'image'    => $img['targa'],
	'order'    => 40,
	'content'  =>
		tn_p( 'L\'avvocato Toninato offre assistenza legale in ambito civile sia a privati che ad aziende.', 'lead' )
		. tn_h( 'Per le persone fisiche' )
		. tn_p( 'In merito alle persone fisiche, lo Studio si occupa di controversie relative a:' )
		. tn_ul( array(
			'<strong>Diritto di famiglia</strong> (separazioni consensuali e giudiziali, divorzi congiunti e contenziosi, modifica delle condizioni di separazione, problematiche inerenti alle famiglie di fatto)',
			'<strong>Risarcimento danni</strong> (da circolazione veicoli, responsabilità medica e civile in genere)',
			'<strong>Successioni e donazioni</strong>',
			'<strong>Interdizioni e inabilitazioni</strong>',
			'<strong>Locazioni</strong>',
			'<strong>Condominio</strong>',
			'<strong>Recupero crediti</strong> (procedure monitorie, esecuzioni immobiliari e mobiliari)',
			'<strong>Diritto contrattuale</strong> (redazione, interpretazione, responsabilità pre-contrattuale, extra-contrattuale, risarcimento per inadempimento contrattuale)',
			'<strong>Diritto fallimentare</strong> (domande di ammissione al passivo e opposizioni allo stato passivo)',
			'<strong>Aste e ricerca immobile</strong>',
			'<strong>Ristrutturazione del debito</strong>',
		) )
		. tn_h( 'Per le aziende' )
		. tn_p( 'La consulenza legale rivolta alle aziende include questioni e controversie in materia di:' )
		. tn_ul( array(
			'<strong>Diritto contrattuale</strong> (redazione, interpretazione, responsabilità pre-contrattuale, extra-contrattuale, risarcimento per inadempimento contrattuale)',
			'<strong>Recupero crediti</strong> (procedure monitorie, esecuzioni immobiliari e mobiliari)',
			'<strong>Diritto fallimentare</strong>',
			'<strong>Ristrutturazione del debito</strong>',
		) ),
);

$pages['diritto-penale'] = array(
	'title'    => 'Diritto penale',
	'excerpt'  => 'Querele e processi con costituzione di parte civile, fino all\'ultimo grado di giudizio.',
	'image'    => $img['biglietto'],
	'order'    => 50,
	'content'  =>
		tn_p( 'In ambito penale, lo studio legale dell\'avvocato Toninato offre assistenza completa per la redazione di querele e nei processi con costituzione di parte civile fino all\'ultimo grado di giudizio.', 'lead' )
		. tn_img( $img['biglietto'], 'Biglietto da visita dello Studio Legale Toninato' ),
);

$pages['contatti'] = array(
	'title'    => 'Contatti',
	'excerpt'  => 'Lo studio riceve solo su appuntamento.',
	'template' => 'page-templates/template-contatti.php',
	'image'    => $img['targa'],
	'order'    => 60,
	'content'  =>
		tn_h( 'Come fissare una consulenza' )
		. tn_p( 'Lo studio riceve solo su appuntamento: per fissare una consulenza è possibile mettersi in contatto con lo studio tramite uno dei recapiti indicati. Si garantisce risposta immediata o un nuovo contatto entro un\'ora.' ),
);

$ids = array();
foreach ( $pages as $path => $p ) {
	$slug      = $p['slug'] ?? $path;
	$parent_id = isset( $p['parent'] ) ? ( $ids[ $p['parent'] ] ?? 0 ) : 0;
	$existing  = get_page_by_path( $path );

	$data = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $p['title'],
		'post_name'    => $slug,
		'post_parent'  => $parent_id,
		'post_author'  => $author->ID,
		'menu_order'   => $p['order'],
		'post_excerpt' => $p['excerpt'] ?? '',
		'post_content' => $p['content'],
	);

	if ( $existing && ! $update ) {
		$id = $existing->ID;
		WP_CLI::log( "= esiste già: $path (#$id)" );
	} else {
		if ( $existing ) {
			$data['ID'] = $existing->ID;
		}
		$id = wp_insert_post( wp_slash( $data ), true );
		if ( is_wp_error( $id ) ) {
			WP_CLI::error( "Errore su $path: " . $id->get_error_message() );
		}
		WP_CLI::log( ( $existing ? '~ aggiornata: ' : '+ creata: ' ) . "$path (#$id)" );
	}

	update_post_meta( $id, '_wp_page_template', $p['template'] ?? 'default' );
	if ( ! empty( $p['image'] ) && ( $update || ! has_post_thumbnail( $id ) ) ) {
		set_post_thumbnail( $id, $p['image'] );
	}
	$ids[ $path ] = $id;
}

// Homepage statica.
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['home'] );

/* -------------------------------------------------------------------------
 * Menu
 * ---------------------------------------------------------------------- */

function tn_menu( $name, $location, $items, $ids, $update ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu && ! $update ) {
		WP_CLI::log( "= menu già presente: $name" );
	} else {
		if ( $menu ) {
			foreach ( wp_get_nav_menu_items( $menu->term_id ) as $old ) {
				wp_delete_post( $old->ID, true );
			}
			$menu_id = $menu->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $name );
		}
		$pos = 1;
		foreach ( $items as $item ) {
			$parent_item = 0;
			$item_id     = wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-object-id' => $ids[ $item['page'] ],
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $pos++,
				'menu-item-title'     => $item['label'] ?? '',
			) );
			foreach ( $item['children'] ?? array() as $child ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-object-id' => $ids[ $child ],
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => $item_id,
					'menu-item-position'  => $pos++,
				) );
			}
		}
		$menu = wp_get_nav_menu_object( $menu_id );
		WP_CLI::log( "+ menu creato: $name" );
	}

	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

$service_paths = array_map( fn( $s ) => 'servizi/' . $s['slug'], toninato_services() );

tn_menu( 'Menu principale', 'primary', array(
	array( 'page' => 'home' ),
	array( 'page' => 'lo-studio' ),
	array( 'page' => 'garanzia-di-professionalita' ),
	array( 'page' => 'servizi', 'children' => $service_paths ),
	array( 'page' => 'diritto-civile' ),
	array( 'page' => 'diritto-penale' ),
	array( 'page' => 'contatti' ),
), $ids, $update );

tn_menu( 'Menu footer', 'footer', array(
	array( 'page' => 'lo-studio' ),
	array( 'page' => 'garanzia-di-professionalita' ),
	array( 'page' => 'servizi' ),
	array( 'page' => 'diritto-civile' ),
	array( 'page' => 'diritto-penale' ),
	array( 'page' => 'contatti' ),
), $ids, $update );

WP_CLI::success( 'Contenuti pronti: ' . count( $ids ) . ' pagine.' );
