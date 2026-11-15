<?php
/**
 * Riquadro contatti (CTA): titolo, telefoni, email e nota da "Dati dell'attività".
 */

$info = toninato_info();
?>
<section class="section cta">
	<div class="container">
		<div class="cta__card">
			<h2 class="cta__title"><?php echo esc_html( $info['cta_title'] ); ?></h2>
			<?php if ( $info['cta_subtitle'] ) : ?>
				<p class="cta__subtitle"><?php echo esc_html( $info['cta_subtitle'] ); ?></p>
			<?php endif; ?>
			<div class="cta__phones">
				<?php foreach ( toninato_phones() as $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone['tel'] ); ?>"><?php echo toninato_icon( 'cellulare' === $phone['type'] ? 'mobile' : 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $phone['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
			<a class="cta__email" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
				<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( antispambot( $info['email'] ) ); ?>
			</a>
			<?php if ( $info['cta_note'] ) : ?>
				<p class="cta__note">
					<?php echo toninato_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php echo esc_html( $info['cta_note'] ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
