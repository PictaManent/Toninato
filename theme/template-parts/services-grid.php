<?php
/**
 * Griglia delle aree di attività.
 *
 * Argomenti (opzionali): exclude (slug da non mostrare).
 */

$args = wp_parse_args( $args ?? array(), array( 'exclude' => '' ) );
?>
<div class="services__grid">
	<?php foreach ( toninato_services() as $service ) : ?>
		<?php
		if ( $service['slug'] === $args['exclude'] ) {
			continue;
		}
		?>
		<article class="service-card">
			<a class="service-card__link" href="<?php echo esc_url( toninato_page_url( 'servizi/' . $service['slug'] ) ); ?>">
				<div class="service-card__media">
					<img src="<?php echo esc_url( toninato_img( $service['image'] ) ); ?>" alt="" width="900" height="600" loading="lazy">
				</div>
				<div class="service-card__body">
					<h3 class="service-card__title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="service-card__text"><?php echo esc_html( $service['text'] ); ?></p>
					<span class="service-card__more">Scopri di più <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</div>
			</a>
		</article>
	<?php endforeach; ?>
</div>
