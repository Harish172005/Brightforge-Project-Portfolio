<?php
/**
 * Site footer for Brightforge Digital.
 *
 * @package Brightforge
 */
?>

</main>

<footer class="site-footer">

	<div class="wrap footer-content">

		<!-- Company Information -->
		<div class="footer-column footer-company">

			<h2 class="footer-logo">
				Brightforge Digital
			</h2>

			<p>
				Building reliable, scalable, and user-focused digital
				solutions that help businesses grow.
			</p>

		</div>


		<!-- Contact Information -->
		<div class="footer-column">

			<h3>Get In Touch</h3>

			<ul class="footer-contact">

				<li>
					<strong>Email:</strong><br>
					<a href="mailto:hello@brightforgedigital.com">
						hello@brightforgedigital.com
					</a>
				</li>

				<li>
					<strong>Phone:</strong><br>
					<a href="tel:+919999999999">
						+91 8542341265
					</a>
				</li>

				<li>
					<strong>Location:</strong><br>
					Bangalore, India
				</li>

			</ul>

		</div>

	</div>


	<!-- Footer Bottom -->

	<div class="footer-bottom">

		<div class="wrap footer-bottom__inner">

			<p>
				&copy; <?php echo esc_html( date( 'Y' ) ); ?>
				Brightforge Digital. All rights reserved.
			</p>

			<div class="footer-bottom__links">

				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">
					Privacy Policy
				</a>

			</div>

		</div>

	</div>

</footer>

<?php wp_footer(); ?>

</body>
</html>