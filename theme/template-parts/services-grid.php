<?php
/**
 * Griglia delle aree di attività.
 *
 * Le schede sono le pagine figlie di "Servizi" (vedi toninato_service_items()).
 *
 * Argomenti (opzionali): exclude (slug da non mostrare).
 */

$args = wp_parse_args( $args ?? array(), array( 'exclude' => '' ) );

$cards = toninato_service_items();
?>
<div class="services__grid">
	<?php foreach ( $cards as $card ) : ?>
		<?php
		if ( $card['slug'] === $args['exclude'] ) {
			continue;
		}
		?>
		<article class="service-card">
			<a class="service-card__link" href="<?php echo esc_url( $card['url'] ); ?>">
				<div class="service-card__media">
					<?php if ( $card['thumb'] ) : ?>
						<?php echo wp_get_attachment_image( $card['thumb'], 'medium_large', false, array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
					<?php elseif ( $card['image'] ) : ?>
						<img src="<?php echo esc_url( toninato_img( $card['image'] ) ); ?>" alt="" width="900" height="600" loading="lazy">
					<?php endif; ?>
				</div>
				<div class="service-card__body">
					<h3 class="service-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
					<?php if ( $card['text'] ) : ?>
						<p class="service-card__text"><?php echo esc_html( $card['text'] ); ?></p>
					<?php endif; ?>
					<span class="service-card__more">Scopri di più <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</div>
			</a>
		</article>
	<?php endforeach; ?>
</div>
