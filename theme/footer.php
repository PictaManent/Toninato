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
				Consulenza e assistenza legale in diritto civile e penale
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
			<p class="site-footer__note">Si riceve solo su appuntamento</p>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__title">Contatti</h2>
			<ul class="contact-list">
				<li>
					<?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<a href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>"><?php echo esc_html( $info['phone']['label'] ); ?></a>
				</li>
				<li>
					<?php echo toninato_icon( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<a href="tel:<?php echo esc_attr( $info['mobile']['tel'] ); ?>"><?php echo esc_html( $info['mobile']['label'] ); ?></a>
				</li>
				<li>
					<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<a href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>"><?php echo esc_html( antispambot( $info['email'] ) ); ?></a>
				</li>
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
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Toninato Avv. Stefania &middot; P.IVA <?php echo esc_html( $info['piva'] ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
				&middot; Design by R.M.Web
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
