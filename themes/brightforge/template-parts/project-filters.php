<?php
/**
 * Industry filter links and project search for the projects listing.
 *
 * @package Brightforge
 */

$industries = get_terms( array(
	'taxonomy'   => 'project_type',
	'hide_empty' => true,
) );
$current    = is_tax( 'project_type' ) ? get_queried_object_id() : 0;
?>
<div class="filters">
	<?php if ( ! is_wp_error( $industries ) && $industries ) : ?>
		<nav class="filters__list" aria-label="<?php esc_attr_e( 'Filter by industry', 'brightforge' ); ?>">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" <?php echo $current ? '' : 'aria-current="page"'; ?>><?php esc_html_e( 'All', 'brightforge' ); ?></a>
			<?php foreach ( $industries as $industry ) : ?>
				<a href="<?php echo esc_url( get_term_link( $industry ) ); ?>" <?php echo ( $current === $industry->term_id ) ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html( $industry->name ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<form class="filters__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="project-search"><?php esc_html_e( 'Search projects', 'brightforge' ); ?></label>
		<input id="project-search" type="search" name="s" placeholder="<?php esc_attr_e( 'Search projects', 'brightforge' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
		<input type="hidden" name="post_type" value="project">
		<button type="submit" class="button"><?php esc_html_e( 'Search', 'brightforge' ); ?></button>
	</form>
</div>
