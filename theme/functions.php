<?php
/**
 * Funzioni del tema Toninato.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TONINATO_VERSION', wp_get_theme()->get( 'Version' ) );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus( array(
		'primary' => __( 'Menu principale', 'toninato' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'toninato-style', get_stylesheet_uri(), array(), TONINATO_VERSION );
} );
