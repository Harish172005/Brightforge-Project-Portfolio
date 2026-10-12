<?php
/**
 * Archives: categories, tags, dates, authors and the project taxonomies.
 * Project taxonomy pages use the plugin's [projects] shortcode.
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

		<?php if ( $is_projects && shortcode_exists( 'projects' ) ) : ?>

			<?php
			$term = get_queried_object();
			$key  = ( 'technologies' === $term->taxonomy ) ? 'technology' : 'industry';

			echo do_shortcode( sprintf( '[projects filters="yes" %s="%s"]', $key, esc_attr( $term->slug ) ) );
			?>

		<?php elseif ( have_posts() ) : ?>

			<div class="post-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'post' ); ?>
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