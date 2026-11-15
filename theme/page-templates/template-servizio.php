<?php
/**
 * Template Name: Servizio (dettaglio)
 *
 * Singola area di attività: contenuto, contatti e altri servizi.
 */

get_header();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero', null, array( 'overline' => 'Servizi' ) ); ?>

		<section class="section page-body">
			<div class="container page-layout">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
					<?php the_content(); ?>
					<p class="entry-back">
						<a class="link-more" href="<?php echo esc_url( toninato_page_url( 'servizi' ) ); ?>">
							Tutti i servizi <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</p>
				</article>
				<aside class="page-aside">
					<?php get_template_part( 'template-parts/contact-box' ); ?>

					<nav class="side-box" aria-label="<?php esc_attr_e( 'Altri servizi', 'toninato' ); ?>">
						<h2 class="side-box__title">Altri servizi</h2>
						<ul class="side-menu">
							<?php foreach ( toninato_services() as $service ) : ?>
								<?php
								$url     = toninato_page_url( 'servizi/' . $service['slug'] );
								$current = get_post_field( 'post_name' ) === $service['slug'];
								?>
								<li<?php echo $current ? ' class="is-current"' : ''; ?>>
									<a href="<?php echo esc_url( $url ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>>
										<?php echo esc_html( $service['title'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</nav>
				</aside>
			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
