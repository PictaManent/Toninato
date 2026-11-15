<?php
/**
 * Intestazione del sito.
 */

$info = toninato_info();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Vai al contenuto', 'toninato' ); ?></a>

<div class="topbar">
	<div class="container topbar__inner">
		<a class="topbar__item" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
			<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php echo esc_html( antispambot( $info['email'] ) ); ?></span>
		</a>
		<span class="topbar__sep" aria-hidden="true">·</span>
		<a class="topbar__item" href="<?php echo esc_url( $info['maps'] ); ?>" target="_blank" rel="noopener">
			<?php echo toninato_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php echo esc_html( $info['address'] . ', ' . $info['city'] . ' (' . $info['province'] . ')' ); ?></span>
		</a>
		<span class="topbar__sep topbar__sep--hide-sm" aria-hidden="true">·</span>
		<?php $topbar_phone = toninato_phones( 'fisso' )[0] ?? ( toninato_phones()[0] ?? null ); ?>
		<?php if ( $topbar_phone ) : ?>
			<a class="topbar__item topbar__item--hide-sm" href="tel:<?php echo esc_attr( $topbar_phone['tel'] ); ?>">
				<?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php echo esc_html( $topbar_phone['label'] ); ?></span>
			</a>
		<?php endif; ?>
	</div>
</div>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<?php toninato_brand(); ?>

		<button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false">
			<span class="nav-toggle__open"><?php echo toninato_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="nav-toggle__close"><?php echo toninato_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'toninato' ); ?></span>
		</button>

		<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Menu principale', 'toninato' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'menu',
				'depth'          => 2,
				'fallback_cb'    => 'toninato_menu_fallback',
			) );
			?>
		</nav>
	</div>
</header>
