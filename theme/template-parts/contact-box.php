<?php
/**
 * Riquadro contatti per le colonne laterali.
 */

$info = toninato_info();
?>
<div class="side-box side-box--dark">
	<h2 class="side-box__title">Fissa una consulenza</h2>
	<p>Lo studio riceve solo su appuntamento. Risposta immediata o un nuovo contatto entro un'ora.</p>
	<ul class="contact-list contact-list--light">
		<li>
			<?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>"><?php echo esc_html( $info['phone']['label'] ); ?></a>
		</li>
		<li>
			<?php echo toninato_icon( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a href="tel:<?php echo esc_attr( $info['mobile']['tel'] ); ?>"><?php echo esc_html( $info['mobile']['label'] ); ?></a>
		</li>
		<li>
			<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>"><?php echo esc_html( antispambot( $info['email'] ) ); ?></a>
		</li>
	</ul>
	<a class="btn btn--primary btn--block" href="<?php echo esc_url( toninato_page_url( 'contatti' ) ); ?>">Contatti</a>
</div>
