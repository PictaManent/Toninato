<?php
/**
 * Riquadro contatti per le colonne laterali.
 */

$info = toninato_info();
?>
<div class="side-box side-box--dark">
	<h2 class="side-box__title"><?php echo esc_html( $info['box_title'] ); ?></h2>
	<?php if ( $info['box_text'] ) : ?>
		<p><?php echo esc_html( $info['box_text'] ); ?></p>
	<?php endif; ?>
	<ul class="contact-list contact-list--light">
		<?php toninato_contact_items(); ?>
	</ul>
	<a class="btn btn--primary btn--block" href="<?php echo esc_url( toninato_page_url( 'contatti' ) ); ?>"><?php echo esc_html( $info['box_button'] ); ?></a>
</div>
