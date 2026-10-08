<?php
/**
 * Site footer for Brightforge Digital.
 *
 * @package Brightforge
 */

$phone = get_theme_mod( 'brightforge_phone' );
$email = get_theme_mod( 'brightforge_email' );
?>

</main>

<footer class="site-footer">

	<div class="wrap footer-content">

		<!-- Company Information -->
		<div class="footer-column footer-company">

			<h2 class="footer-logo">
				<?php bloginfo( 'name' ); ?>
			</h2>

			<p>
				<?php esc_html_e(
					'Building reliable, scalable, and user-focused digital solutions that help businesses grow.',
					'brightforge'
				); ?>
			</p>

		</div>

		<!-- Contact Information -->
		<div class="footer-column">

			<h3>
				<?php esc_html_e( 'Get In Touch', 'brightforge' ); ?>
			</h3>

			<ul class="footer-contact">

				<?php if ( $email ) : ?>
					<li>
						<strong>
							<?php esc_html_e( 'Email:', 'brightforge' ); ?>
						</strong><br>

						<a href="mailto:<?php echo esc_attr( $email ); ?>">
							<?php echo esc_html( $email ); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
					<li>
						<strong>
							<?php esc_html_e( 'Phone:', 'brightforge' ); ?>
						</strong><br>

						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
							<?php echo esc_html( $phone ); ?>
						</a>
					</li>
				<?php endif; ?>

				<li>
					<strong>
						<?php esc_html_e( 'Location:', 'brightforge' ); ?>
					</strong><br>

					<?php esc_html_e( 'Bangalore, India', 'brightforge' ); ?>
				</li>

			</ul>

		</div>

		<!-- Footer Widget Areas -->
		<?php for ( $i = 1; $i <= 4; $i++ ) : ?>

			<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>

				<div class="footer-column">

					<?php dynamic_sidebar( 'footer-' . $i ); ?>

				</div>

			<?php endif; ?>

		<?php endfor; ?>

	</div>

	<div class="footer-bottom">

		<div class="wrap footer-bottom__inner">

			<?php get_template_part( 'template-parts/footer-credit' ); ?>

			<div class="footer-bottom__links">

				<?php if ( has_nav_menu( 'footer' ) ) : ?>

					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
						)
					);
					?>

				<?php elseif ( get_privacy_policy_url() ) : ?>

					<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">
						<?php esc_html_e( 'Privacy Policy', 'brightforge' ); ?>
					</a>

				<?php endif; ?>

			</div>

		</div>

	</div>

</footer>

<?php wp_footer(); ?>

</body>
</html>