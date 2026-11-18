<?php
/**
 * Template Name: Home
 *
 * Homepage. Testi e immagini si modificano dal pannello: il testo di presentazione è il contenuto
 * della pagina, il resto sta nel riquadro "Contenuti della Home" (campi in inc/fields.php).
 * Ogni campo vuoto usa il contenuto predefinito di toninato_home_defaults().
 */

get_header();

$d = toninato_home_defaults();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

	<?php
	$hero_lines = preg_split( '/\R/', (string) toninato_field( 'hero_lines', $d['hero_lines'] ) );
	$hero_btn_1 = toninato_link( toninato_field( 'hero_button_1' ), $d['hero_button_1'] );
	$hero_btn_2 = toninato_link( toninato_field( 'hero_button_2' ), $d['hero_button_2'] );
	$intro_link = toninato_link( toninato_field( 'intro_link' ), $d['intro_link'] );
	?>

	<!-- Hero -->
	<section class="hero" style="--hero-image: url('<?php echo esc_url( toninato_image_url( toninato_field( 'hero_image' ), $d['hero_image'], 'full' ) ); ?>');">
		<div class="container hero__inner">
			<div class="hero__content">
				<span class="hero__mark" aria-hidden="true">ST</span>
				<p class="hero__title"><?php echo esc_html( toninato_field( 'hero_title', $d['hero_title'] ) ); ?></p>
				<p class="hero__name"><?php echo esc_html( toninato_field( 'hero_name', $d['hero_name'] ) ); ?></p>
				<?php foreach ( array_filter( array_map( 'trim', $hero_lines ) ) as $line ) : ?>
					<p class="hero__line"><?php echo esc_html( $line ); ?></p>
				<?php endforeach; ?>
				<p class="hero__place"><?php echo esc_html( toninato_field( 'hero_place', $d['hero_place'] ) ); ?></p>
				<div class="hero__actions">
					<a class="btn btn--primary" <?php echo toninato_link_attrs( $hero_btn_1 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $hero_btn_1['title'] ); ?></a>
					<a class="btn btn--ghost" <?php echo toninato_link_attrs( $hero_btn_2 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $hero_btn_2['title'] ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<!-- Presentazione (contenuto della pagina) -->
	<section class="intro">
		<div class="container">
			<div class="intro__card">
				<div class="intro__text">
					<?php the_content(); ?>
					<a class="link-more" <?php echo toninato_link_attrs( $intro_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
						<?php echo esc_html( $intro_link['title'] ); ?> <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
				<figure class="intro__media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( toninato_img( 'avvocato-toninato.jpg' ) ); ?>" alt="L'Avvocato Stefania Toninato nel suo studio di Moncalieri" width="1350" height="550" loading="eager">
					<?php endif; ?>
				</figure>
			</div>
		</div>
	</section>

	<!-- Aree di attività -->
	<section class="section services" id="aree-di-attivita">
		<div class="container">
			<header class="section-head">
				<p class="overline"><?php echo esc_html( toninato_field( 'services_overline', $d['services_overline'] ) ); ?></p>
				<h2 class="section-title"><?php echo esc_html( toninato_field( 'services_title', $d['services_title'] ) ); ?></h2>
				<?php $lead = toninato_field( 'services_lead', $d['services_lead'] ); ?>
				<?php if ( $lead ) : ?>
					<p class="section-lead"><?php echo esc_html( $lead ); ?></p>
				<?php endif; ?>
			</header>
			<?php get_template_part( 'template-parts/services-grid' ); ?>
		</div>
	</section>

	<!-- Valori -->
	<section class="section values">
		<div class="container">
			<header class="section-head section-head--light">
				<p class="overline"><?php echo esc_html( toninato_field( 'values_overline', $d['values_overline'] ) ); ?></p>
				<h2 class="section-title"><?php echo esc_html( toninato_field( 'values_title', $d['values_title'] ) ); ?></h2>
			</header>

			<div class="values__grid">
				<?php foreach ( toninato_field( 'values', $d['values'] ) as $value ) : ?>
					<div class="value">
						<span class="value__icon"><?php echo toninato_icon( $value['icon'] ?: 'scale' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3 class="value__title"><?php echo esc_html( $value['title'] ); ?></h3>
						<?php if ( $value['text'] ) : ?>
							<p><?php echo esc_html( $value['text'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Ambiti -->
	<section class="section areas">
		<div class="container areas__grid">
			<?php foreach ( toninato_field( 'areas', $d['areas'] ) as $i => $area ) : ?>
				<?php
				$fallback = $d['areas'][ $i ] ?? $d['areas'][0];
				$icon     = ! empty( $area['icon'] ) ? $area['icon'] : $fallback['icon'];
				$link     = toninato_link( $area['link'] ?? null, array( 'url' => '', 'title' => '', 'target' => '' ) );
				?>
				<article class="area">
					<div class="area__side">
						<span class="area__icon"><?php echo toninato_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="area__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					</div>
					<span class="area__mark" aria-hidden="true"><?php echo toninato_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div class="area__body">
						<?php if ( $area['overline'] ) : ?>
							<p class="overline"><?php echo esc_html( $area['overline'] ); ?></p>
						<?php endif; ?>
						<h2 class="area__title"><?php echo esc_html( $area['title'] ); ?></h2>
						<?php if ( $area['text'] ) : ?>
							<p><?php echo esc_html( $area['text'] ); ?></p>
						<?php endif; ?>
						<?php if ( $link['url'] ) : ?>
							<a class="link-more" <?php echo toninato_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
								<?php echo esc_html( $link['title'] ?: 'Approfondisci' ); ?> <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/reviews', null, array(
		'overline' => toninato_field( 'reviews_overline', $d['reviews_overline'] ),
		'title'    => toninato_field( 'reviews_title', $d['reviews_title'] ),
	) );
	?>

	<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endwhile; ?>
</main>

<?php
get_footer();
