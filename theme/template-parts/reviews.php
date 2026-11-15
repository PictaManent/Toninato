<?php
/**
 * Recensioni Google, dal plugin "Widgets for Google Reviews" (Trustindex).
 *
 * La sezione compare solo se il plugin è attivo e collegato alla scheda Google
 * dello studio: finché non lo è, ai visitatori non viene mostrato niente.
 *
 * Argomenti (opzionali): overline, title.
 */

$args = wp_parse_args( $args ?? array(), array( 'overline' => 'Recensioni Google', 'title' => 'Dicono dello studio' ) );

if ( ! shortcode_exists( 'trustindex' ) ) {
	return;
}

$widget = trim( do_shortcode( toninato_info()['reviews'] ) );
if ( '' === $widget ) {
	return;
}
?>
<section class="section reviews" id="recensioni">
	<div class="container">
		<header class="section-head">
			<p class="overline"><?php echo esc_html( $args['overline'] ); ?></p>
			<h2 class="section-title"><?php echo esc_html( $args['title'] ); ?></h2>
		</header>
		<div class="reviews__widget">
			<?php echo $widget; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato dal plugin ?>
		</div>
	</div>
</section>
