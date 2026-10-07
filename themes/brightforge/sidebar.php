<?php
/**
 * Blog sidebar.
 *
 * @package Brightforge
 */

if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
	return;
}
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'brightforge' ); ?>">
	<?php dynamic_sidebar( 'blog-sidebar' ); ?>
</aside>
