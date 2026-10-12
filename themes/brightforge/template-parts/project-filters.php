<?php
/**
 * Project filters: search, industry, technology and status.
 * The form sends a GET request, so filters combine and the result can be shared.
 *
 * @package Brightforge
 */

$to_options = function ( $terms ) {
	return ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name', 'slug' ) : array();
};

$industries   = $to_options( get_terms( array( 'taxonomy' => 'project_type', 'hide_empty' => true ) ) );
$technologies = $to_options( get_terms( array( 'taxonomy' => 'technologies', 'hide_empty' => true ) ) );
$statuses     = array_combine( brightforge_project_statuses(), brightforge_project_statuses() );
$listing_url  = get_post_type_archive_link( 'project' );

$render_select = function ( $name, $label, $options, $selected ) {
	?>
	<label class="filters__field">
		<span><?php echo esc_html( $label ); ?></span>
		<select name="<?php echo esc_attr( $name ); ?>">
			<option value=""><?php esc_html_e( 'All', 'brightforge' ); ?></option>
			<?php foreach ( $options as $value => $text ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?>>
					<?php echo esc_html( $text ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php
};
?>
<form class="filters" method="get" action="<?php echo esc_url( $listing_url ); ?>" role="search">
	<input type="hidden" name="post_type" value="project">

	<label class="filters__field filters__field--search">
		<span><?php esc_html_e( 'Search', 'brightforge' ); ?></span>
		<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search projects', 'brightforge' ); ?>">
	</label>

	<?php
	$render_select( 'project_type', __( 'Industry', 'brightforge' ), $industries, get_query_var( 'project_type' ) );
	$render_select( 'technologies', __( 'Technology', 'brightforge' ), $technologies, get_query_var( 'technologies' ) );
	$render_select( 'project_status', __( 'Status', 'brightforge' ), $statuses, get_query_var( 'project_status' ) );
	?>

	<button type="submit" class="button"><?php esc_html_e( 'Filter', 'brightforge' ); ?></button>
	<a class="filters__reset" href="<?php echo esc_url( $listing_url ); ?>"><?php esc_html_e( 'Reset', 'brightforge' ); ?></a>
</form>