<?php
/**
 * Intestazione delle pagine interne: titolo su immagine di copertina.
 *
 * Sopratitolo: campo "Sopratitolo" della pagina (gruppo "Intestazione della pagina"), altrimenti
 * il predefinito del template (toninato_page_overline_default()).
 *
 * Argomenti (opzionali): overline, title, lead, fallback (immagine del tema).
 */

$args     = wp_parse_args( $args ?? array(), array(
	'overline' => '',
	'title'    => get_the_title(),
	'lead'     => has_excerpt() ? get_the_excerpt() : '',
	'fallback' => 'studio-ufficio.jpg',
) );
$parent   = wp_get_post_parent_id( get_the_ID() );
$overline = toninato_field( 'page_overline', $args['overline'] ? $args['overline'] : toninato_page_overline_default( get_the_ID() ) );
?>
<section class="page-hero" style="--hero-image: url('<?php echo esc_url( toninato_cover_url( get_the_ID(), $args['fallback'] ) ); ?>');">
	<div class="container page-hero__inner">
		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Percorso', 'toninato' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
			<?php if ( $parent ) : ?>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_permalink( $parent ) ); ?>"><?php echo esc_html( get_the_title( $parent ) ); ?></a>
			<?php endif; ?>
			<span aria-hidden="true">/</span>
			<span aria-current="page"><?php echo esc_html( $args['title'] ); ?></span>
		</nav>
		<?php if ( $overline ) : ?>
			<p class="overline"><?php echo esc_html( $overline ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
		<?php if ( $args['lead'] ) : ?>
			<p class="page-hero__lead"><?php echo esc_html( $args['lead'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
