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
