<?php
/**
 * Template Name: Contatti
 *
 * Recapiti dello studio e contenuto della pagina.
 */

get_header();

$info = toninato_info();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero', null, array( 'overline' => 'Studio legale a Moncalieri', 'fallback' => 'targa-studio.jpg' ) ); ?>

		<section class="section page-body">
			<div class="container">
				<div class="contact-grid">
					<a class="contact-card" href="<?php echo esc_url( $info['maps'] ); ?>" target="_blank" rel="noopener">
						<span class="contact-card__icon"><?php echo toninato_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label">Indirizzo</span>
						<span class="contact-card__value"><?php echo esc_html( $info['address'] ); ?><br><?php echo esc_html( $info['cap'] . ' ' . $info['city'] . ' (' . $info['province'] . ')' ); ?></span>
						<span class="contact-card__more">Apri in Google Maps</span>
					</a>
					<div class="contact-card">
						<span class="contact-card__icon"><?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label">Telefono studio</span>
						<span class="contact-card__value contact-card__value--list">
							<a href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>"><?php echo esc_html( $info['phone']['label'] ); ?></a>
							<a href="tel:<?php echo esc_attr( $info['phone2']['tel'] ); ?>"><?php echo esc_html( $info['phone2']['label'] ); ?></a>
						</span>
						<span class="contact-card__more">Chiama</span>
					</div>
					<a class="contact-card" href="tel:<?php echo esc_attr( $info['mobile']['tel'] ); ?>">
						<span class="contact-card__icon"><?php echo toninato_icon( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label">Cellulare</span>
						<span class="contact-card__value"><?php echo esc_html( $info['mobile']['label'] ); ?></span>
						<span class="contact-card__more">Chiama</span>
					</a>
					<a class="contact-card" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
						<span class="contact-card__icon"><?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label">Email</span>
						<span class="contact-card__value"><?php echo esc_html( antispambot( $info['email'] ) ); ?></span>
						<span class="contact-card__more">Scrivi</span>
					</a>
				</div>

				<?php if ( get_the_content() ) : ?>
					<div class="entry-content entry-content--center contact-text">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
