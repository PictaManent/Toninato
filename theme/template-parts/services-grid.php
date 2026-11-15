<?php
/**
 * Griglia delle aree di attività.
 *
 * Le schede sono le pagine figlie di "Servizi" (titolo, riassunto, immagine in evidenza,
 * nell'ordine di "Attributi pagina → Ordine"). Se non ce ne sono, usa l'elenco in inc/data.php.
 *
 * Argomenti (opzionali): exclude (slug da non mostrare).
 */

$args = wp_parse_args( $args ?? array(), array( 'exclude' => '' ) );

$cards  = array();
$parent = get_page_by_path( 'servizi' );
$pages  = $parent ? get_pages( array( 'parent' => $parent->ID, 'sort_column' => 'menu_order,post_title' ) ) : array();

if ( $pages ) {
	foreach ( $pages as $page ) {
		$cards[] = array(
			'slug'  => $page->post_name,
			'title' => get_the_title( $page ),
			'text'  => $page->post_excerpt,
			'url'   => get_permalink( $page ),
			'thumb' => get_post_thumbnail_id( $page ),
			'image' => '',
		);
	}
} else {
	foreach ( toninato_services() as $service ) {
		$cards[] = array(
			'slug'  => $service['slug'],
			'title' => $service['title'],
			'text'  => $service['text'],
			'url'   => toninato_page_url( 'servizi/' . $service['slug'] ),
			'thumb' => 0,
			'image' => $service['image'],
		);
	}
}
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
