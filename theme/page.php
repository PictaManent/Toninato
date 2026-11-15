<?php
/**
 * Template predefinito delle pagine: intestazione con immagine e contenuto.
 */

get_header();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero' ); ?>

		<section class="section page-body">
			<div class="container page-layout">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
					<?php the_content(); ?>
				</article>
				<aside class="page-aside">
					<?php get_template_part( 'template-parts/contact-box' ); ?>
				</aside>
			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
