<?php
/**
 * Funzioni di supporto per i template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL di un'immagine del tema.
 *
 * @param string $file Nome del file in assets/images.
 * @return string
 */
function toninato_img( $file ) {
	return get_template_directory_uri() . '/assets/images/' . $file;
}

/**
 * Icone SVG in linea (tratto 1.5, 24x24).
 *
 * @param string $name Nome dell'icona.
 * @return string
 */
function toninato_icon( $name ) {
	$paths = array(
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="m3.5 6 8.5 7 8.5-7"/>',
		'pin'      => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
		'mobile'   => '<rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/>',
		'scale'    => '<path d="M12 3v18M7 21h10M5 7h14M5 7l-3 7a3.5 3.5 0 0 0 6 0L5 7Zm14 0-3 7a3.5 3.5 0 0 0 6 0l-3-7Z"/>',
		'shield'   => '<path d="M12 3 5 6v5c0 4.5 3 8.3 7 10 4-1.7 7-5.5 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/>',
		'chat'     => '<path d="M4 5h16v11H9l-5 4V5Z"/><path d="M8 9.5h8M8 12.5h5"/>',
		'users'    => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><circle cx="17" cy="9" r="2.5"/><path d="M15.5 14.2A4.5 4.5 0 0 1 21 18.5"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path d="M6 6l12 12M18 6 6 18"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg class="icon icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * Marchio testuale dello studio.
 *
 * @param string $class Classe aggiuntiva.
 */
function toninato_brand( $class = '' ) {
	$info = toninato_info();
	?>
	<a class="brand <?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<span class="brand__mark" aria-hidden="true">ST</span>
		<span class="brand__text">
			<span class="brand__title"><?php echo esc_html( $info['title'] ); ?></span>
			<span class="brand__name"><?php echo esc_html( $info['name'] ); ?></span>
		</span>
	</a>
	<?php
}

/**
 * Menu di riserva quando non è assegnato nessun menu alla posizione.
 *
 * @param array $args Argomenti di wp_nav_menu.
 */
function toninato_menu_fallback( $args ) {
	$class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
	$home  = trailingslashit( home_url( '/' ) );
	$here  = trailingslashit( home_url( add_query_arg( array() ) ) );

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( toninato_default_menu() as $item ) {
		$current = ( trailingslashit( $item['url'] ) === $here ) || ( is_front_page() && trailingslashit( $item['url'] ) === $home );
		printf(
			'<li class="menu-item%s"><a href="%s"%s>%s</a></li>',
			$current ? ' current-menu-item' : '',
			esc_url( $item['url'] ),
			$current ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}
