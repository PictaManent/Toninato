<?php
/**
 * Template Name: Servizi (elenco)
 *
 * Pagina "Servizi": testo introduttivo e griglia di tutte le aree di attività.
 */

get_header();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero', null, array( 'overline' => 'Aree di attività' ) ); ?>

		<section class="section services">
			<div class="container">
				<?php if ( get_the_content() ) : ?>
					<div class="entry-content entry-content--center">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/services-grid' ); ?>
			</div>
		</section>

		<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endwhile; ?>
</main>

<?php
get_footer();
