<?php
/**
 * Projects listing. The list, filters and pagination come from the plugin's [projects] shortcode.
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap">
	<header class="page-head">
		<h1><?php post_type_archive_title(); ?></h1>
	</header>

	<?php if ( shortcode_exists( 'projects' ) ) : ?>
		<?php echo do_shortcode( '[projects filters="yes"]' ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Please activate the Project Management Toolkit plugin to show projects.', 'brightforge' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();