<?php
/**
 * Site header: top bar, logo, primary navigation.
 *
 * @package Brightforge
 */

$phone = get_theme_mod( 'brightforge_phone' );
$email = get_theme_mod( 'brightforge_email' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'brightforge' ); ?></a>

<?php if ( $phone || $email ) : ?>
	<div class="topbar">
		<div class="wrap topbar__inner">
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<header class="site-header">
	<div class="site-header__inner wrap">
		<div class="site-brand">

			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php endif; ?>

			<div class="site-brand__text">
				<a class="site-brand__name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>

				<span class="site-brand__tagline">
					<?php bloginfo( 'description' ); ?>
				</span>
			</div>

		</div>
		<button class="nav-toggle" aria-controls="site-nav" aria-expanded="false">
			<?php esc_html_e( 'Menu', 'brightforge' ); ?>
		</button>

		<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'brightforge' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 2,
			) );
			?>
		</nav>
	</div>
</header>

<main id="content" class="site-main">
