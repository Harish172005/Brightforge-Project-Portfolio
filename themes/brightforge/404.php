<?php
/**
 * 404 page.
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap wrap--narrow not-found">
	<h1><?php esc_html_e( 'That page does not exist', 'brightforge' ); ?></h1>
	<p><?php esc_html_e( 'The link may be broken or the page may have moved. Search the site or go back to our work.', 'brightforge' ); ?></p>
	<?php get_search_form(); ?>
	<p>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'brightforge' ); ?></a>
		<?php if ( post_type_exists( 'project' ) ) : ?>
			<a class="button button--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'See our projects', 'brightforge' ); ?></a>
		<?php endif; ?>
	</p>
</div>
<?php
get_footer();
