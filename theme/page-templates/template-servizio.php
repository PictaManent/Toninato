<?php
/**
 * Template Name: Servizio (dettaglio)
 *
 * Singola area di attività: contenuto, contatti e altri servizi.
 */

get_header();

$info = toninato_info();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero' ); ?>

		<section class="section page-body">
			<div class="container page-layout">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
					<?php the_content(); ?>
					<p class="entry-back">
						<a class="link-more" href="<?php echo esc_url( toninato_page_url( 'servizi' ) ); ?>">
							<?php echo esc_html( $info['service_back'] ); ?> <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</p>
				</article>
				<aside class="page-aside">
					<?php get_template_part( 'template-parts/contact-box' ); ?>

					<nav class="side-box" aria-label="<?php echo esc_attr( $info['service_side'] ); ?>">
						<h2 class="side-box__title"><?php echo esc_html( $info['service_side'] ); ?></h2>
						<ul class="side-menu">
							<?php foreach ( toninato_service_items() as $service ) : ?>
								<?php $current = get_post_field( 'post_name' ) === $service['slug']; ?>
								<li<?php echo $current ? ' class="is-current"' : ''; ?>>
									<a href="<?php echo esc_url( $service['url'] ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>>
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
