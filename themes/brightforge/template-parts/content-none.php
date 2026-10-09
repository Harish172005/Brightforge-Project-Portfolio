<?php
/**
 * Shown when a query returns nothing.
 *
 * @package Brightforge
 */
?>
<section class="empty">
	<h2><?php esc_html_e( 'Nothing found', 'brightforge' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search, or browse the latest work from the menu.', 'brightforge' ); ?></p>
	<?php get_search_form(); ?>
</section>
