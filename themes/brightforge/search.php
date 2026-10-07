<?php
/**
 * Search results.
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap layout">
	<div class="layout__main">
		<header class="page-head">
			<h1>
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Results for "%s"', 'brightforge' ), esc_html( get_search_query() ) );
				?>
			</h1>
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
