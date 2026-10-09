<?php
/**
 * Child override of the parent's footer credit.
 *
 * @package Brightforge_Child
 */
?>
<p class="footer-credit">
	&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?>
	<?php bloginfo( 'name' ); ?>.
	<?php esc_html_e( 'All rights reserved.', 'brightforge-child' ); ?>
</p>
