<?php
/**
 * Projects listing (the "case studies" page).
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap">
	<header class="page-head">
		<h1><?php post_type_archive_title(); ?></h1>
	</header>

	<?php get_template_part( 'template-parts/project-filters' ); ?>

	<?php if ( have_posts() ) : ?>
		<div class="grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/card', 'project' ); ?>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
