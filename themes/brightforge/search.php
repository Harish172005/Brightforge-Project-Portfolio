<?php
/**
 * Search results. Project searches show the project grid and filters.
 *
 * @package Brightforge
 */

get_header();

$is_projects = 'project' === get_query_var( 'post_type' );
?>
<div class="wrap <?php echo $is_projects ? '' : 'layout'; ?>">
	<div class="<?php echo $is_projects ? '' : 'layout__main'; ?>">
		<header class="page-head">
			<h1>
				<?php
				if ( $is_projects ) {
					esc_html_e( 'Projects', 'brightforge' );
				} else {
					/* translators: %s: search query */
					printf( esc_html__( 'Results for "%s"', 'brightforge' ), esc_html( get_search_query() ) );
				}
				?>
			</h1>
		</header>

		<?php if ( $is_projects ) : ?>
			<?php get_template_part( 'template-parts/project-filters' ); ?>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="<?php echo $is_projects ? 'grid' : ''; ?>">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
					if ( $is_projects ) {
						get_template_part( 'template-parts/card', 'project' );
					} else {
						get_template_part( 'template-parts/content', 'post' );
					}
					?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
	<?php
	if ( ! $is_projects ) {
		get_sidebar();
	}
	?>
</div>
<?php
get_footer();