<?php
/**
 * Template Name: Contatti
 *
 * Recapiti dello studio e contenuto della pagina.
 */

get_header();

$info      = toninato_info();
$c         = toninato_contatti_defaults();
$fissi     = toninato_phones( 'fisso' );
$cellulari = toninato_phones( 'cellulare' );
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/page-hero', null, array( 'fallback' => 'targa-studio.jpg' ) ); ?>

		<section class="section page-body">
			<div class="container">
				<div class="contact-grid">
					<a class="contact-card" href="<?php echo esc_url( $info['maps'] ); ?>" target="_blank" rel="noopener">
						<span class="contact-card__icon"><?php echo toninato_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label"><?php echo esc_html( toninato_field( 'address_label', $c['address_label'] ) ); ?></span>
						<span class="contact-card__value"><?php echo esc_html( $info['address'] ); ?><br><?php echo esc_html( $info['cap'] . ' ' . $info['city'] . ' (' . $info['province'] . ')' ); ?></span>
						<span class="contact-card__more"><?php echo esc_html( toninato_field( 'address_action', $c['address_action'] ) ); ?></span>
					</a>
					<?php
					$phone_cards = array(
						array( 'list' => $fissi, 'icon' => 'phone', 'label' => toninato_field( 'phone_label', $c['phone_label'] ), 'action' => toninato_field( 'phone_action', $c['phone_action'] ) ),
						array( 'list' => $cellulari, 'icon' => 'mobile', 'label' => toninato_field( 'mobile_label', $c['mobile_label'] ), 'action' => toninato_field( 'mobile_action', $c['mobile_action'] ) ),
					);
					foreach ( $phone_cards as $card ) :
						if ( ! $card['list'] ) {
							continue;
						}
						$single = 1 === count( $card['list'] );
						?>
						<?php if ( $single ) : ?>
							<a class="contact-card" href="tel:<?php echo esc_attr( $card['list'][0]['tel'] ); ?>">
						<?php else : ?>
							<div class="contact-card">
						<?php endif; ?>
							<span class="contact-card__icon"><?php echo toninato_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="contact-card__label"><?php echo esc_html( $card['label'] ); ?></span>
							<?php if ( $single ) : ?>
								<span class="contact-card__value"><?php echo esc_html( $card['list'][0]['label'] ); ?></span>
							<?php else : ?>
								<span class="contact-card__value contact-card__value--list">
									<?php foreach ( $card['list'] as $phone ) : ?>
										<a href="tel:<?php echo esc_attr( $phone['tel'] ); ?>"><?php echo esc_html( $phone['label'] ); ?></a>
									<?php endforeach; ?>
								</span>
							<?php endif; ?>
							<span class="contact-card__more"><?php echo esc_html( $card['action'] ); ?></span>
						<?php echo $single ? '</a>' : '</div>'; ?>
					<?php endforeach; ?>
					<a class="contact-card" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
						<span class="contact-card__icon"><?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="contact-card__label"><?php echo esc_html( toninato_field( 'email_label', $c['email_label'] ) ); ?></span>
						<span class="contact-card__value"><?php echo esc_html( antispambot( $info['email'] ) ); ?></span>
						<span class="contact-card__more"><?php echo esc_html( toninato_field( 'email_action', $c['email_action'] ) ); ?></span>
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
