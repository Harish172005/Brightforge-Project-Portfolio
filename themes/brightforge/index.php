<?php
/**
 * Blog index (fallback template).
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap layout">
	<div class="layout__main">
		<header class="page-head">
			<h1><?php echo is_home() && ! is_front_page() ? esc_html( single_post_title( '', false ) ) : esc_html__( 'Insights', 'brightforge' ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'post' ); ?>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
