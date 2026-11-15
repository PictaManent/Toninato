<?php
/**
 * Piè di pagina del sito.
 */

$info = toninato_info();
?>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__col site-footer__about">
			<?php toninato_brand( 'brand--footer' ); ?>
			<p class="site-footer__tagline">
				<?php echo esc_html( $info['qualifica'] ); ?><br>
				<?php echo esc_html( $info['tagline'] ); ?>
			</p>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__title">Lo Studio</h2>
			<p>
				<a href="<?php echo esc_url( $info['maps'] ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( $info['address'] ); ?><br>
					<strong><?php echo esc_html( $info['cap'] . ' ' . $info['city'] . ' (' . $info['province'] . ')' ); ?></strong>
				</a>
			</p>
			<?php if ( $info['appointment_note'] ) : ?>
				<p class="site-footer__note"><?php echo esc_html( $info['appointment_note'] ); ?></p>
			<?php endif; ?>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__title">Contatti</h2>
			<ul class="contact-list">
				<?php toninato_contact_items(); ?>
			</ul>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__title">Link utili</h2>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'footer-menu',
				'depth'          => 1,
				'fallback_cb'    => 'toninato_menu_fallback',
			) );
			?>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) . ' ' . $info['legal_name'] ); ?> &middot; P.IVA <?php echo esc_html( $info['piva'] ); ?>
			</p>
			<p>
				<?php if ( get_privacy_policy_url() ) : ?>
					<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy Policy</a> &middot;
				<?php endif; ?>
				Design by R.M.Web
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
