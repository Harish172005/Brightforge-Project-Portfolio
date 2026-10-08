<?php
/**
 * Archives: categories, tags, dates, authors and the project taxonomies.
 *
 * @package Brightforge
 */

get_header();

$is_projects = is_tax( array( 'project_type', 'technologies' ) );
?>
<div class="wrap <?php echo $is_projects ? '' : 'layout'; ?>">
	<div class="<?php echo $is_projects ? '' : 'layout__main'; ?>">
		<header class="page-head">
			<h1><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="page-head__lead">', '</div>' ); ?>
		</header>

		<?php if ( $is_projects ) : ?>
			<?php get_template_part( 'template-parts/project-filters' ); ?>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="<?php echo $is_projects ? 'grid' : 'post-list'; ?>">
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
