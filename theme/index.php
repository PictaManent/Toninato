<?php
/**
 * Template di base (pagine, articoli, archivi).
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container page-content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<h1 class="entry__title"><?php the_title(); ?></h1>
					<div class="entry__content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;
		else :
			?>
			<h1 class="entry__title">Pagina non trovata</h1>
			<p>Il contenuto che cerchi non è disponibile. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Torna alla home</a>.</p>
			<?php
		endif;
		?>
	</div>
</main>

<?php
get_footer();
