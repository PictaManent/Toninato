<?php
/**
 * Funzioni del tema Toninato.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TONINATO_VERSION', wp_get_theme()->get( 'Version' ) );

require get_template_directory() . '/inc/data.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/fields.php';

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'toninato', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus( array(
		'primary' => __( 'Menu principale', 'toninato' ),
		'footer'  => __( 'Menu footer', 'toninato' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	wp_enqueue_style(
		'toninato-main',
		$uri . '/assets/css/main.css',
		array(),
		TONINATO_VERSION . '.' . filemtime( $dir . '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'toninato-main',
		$uri . '/assets/js/main.js',
		array(),
		TONINATO_VERSION . '.' . filemtime( $dir . '/assets/js/main.js' ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
} );

/**
 * Favicon del tema (monogramma "ST").
 * Se in Aspetto → Personalizza → Identità del sito è impostata un'Icona del sito, vale quella.
 */
add_action( 'wp_head', 'toninato_favicon', 2 );
add_action( 'admin_head', 'toninato_favicon' );
add_action( 'login_head', 'toninato_favicon' );
function toninato_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	$dir = get_template_directory_uri() . '/assets/favicon/';
	$ver = '?v=' . TONINATO_VERSION;
	printf( '<link rel="icon" href="%s" sizes="48x48">' . "\n", esc_url( $dir . 'favicon.ico' . $ver ) );
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( $dir . 'favicon.svg' . $ver ) );
	printf( '<link rel="icon" href="%s" type="image/png" sizes="192x192">' . "\n", esc_url( $dir . 'icon-192.png' . $ver ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $dir . 'apple-touch-icon.png' . $ver ) );
}

// /favicon.ico: senza Icona del sito WordPress rimanderebbe al proprio logo.
add_action( 'do_faviconico', function () {
	if ( ! has_site_icon() ) {
		wp_safe_redirect( get_template_directory_uri() . '/assets/favicon/favicon.ico', 302 );
		exit;
	}
} );

/**
 * Precarica i font principali per evitare lo "sfarfallio" del testo.
 */
add_action( 'wp_head', function () {
	$fonts = array( 'bacasime-antique-400.woff2', 'tajawal-400.woff2' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_template_directory_uri() . '/assets/fonts/' . $font )
		);
	}
}, 1 );
