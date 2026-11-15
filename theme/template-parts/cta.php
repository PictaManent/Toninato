<?php
/**
 * Riquadro "Contatta lo studio".
 */

$info = toninato_info();
?>
<section class="section cta">
	<div class="container">
		<div class="cta__card">
			<h2 class="cta__title">Hai bisogno di un avvocato a Moncalieri?</h2>
			<p class="cta__subtitle">Contatta lo studio:</p>
			<div class="cta__phones">
				<a href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>"><?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $info['phone']['label'] ); ?></a>
				<a href="tel:<?php echo esc_attr( $info['phone2']['tel'] ); ?>"><?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $info['phone2']['label'] ); ?></a>
				<a href="tel:<?php echo esc_attr( $info['mobile']['tel'] ); ?>"><?php echo toninato_icon( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $info['mobile']['label'] ); ?></a>
			</div>
			<a class="cta__email" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
				<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( antispambot( $info['email'] ) ); ?>
			</a>
			<p class="cta__note">
				<?php echo toninato_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				Lo studio riceve solo su appuntamento. Si garantisce risposta immediata o un nuovo contatto entro un'ora.
			</p>
		</div>
	</div>
</section>
