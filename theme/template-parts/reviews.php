<?php
/**
 * Recensioni Google, dal plugin "Widgets for Google Reviews" (Trustindex).
 *
 * La sezione compare solo se il plugin è attivo e collegato alla scheda Google
 * dello studio: finché non lo è, ai visitatori non viene mostrato niente.
 */

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
			<p class="overline">Recensioni Google</p>
			<h2 class="section-title">Dicono dello studio</h2>
		</header>
		<div class="reviews__widget">
			<?php echo $widget; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML generato dal plugin ?>
		</div>
	</div>
</section>
