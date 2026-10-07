<?php
/**
 * Blog / Posts Archive
 *
 * @package Brightforge
 */

get_header();
?>

<div class="wrap layout">

	<main class="layout__main">

		<header class="page-head">
			<h1>
				<?php
				esc_html_e( 'Blog', 'brightforge' );
				?>
			</h1>

			<p>
				<?php
				esc_html_e(
					'Latest insights, updates, and articles from Brightforge Digital.',
					'brightforge'
				);
				?>
			</p>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="blog-posts">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'post' ); ?>

				<?php endwhile; ?>

			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>

			<p>
				<?php
				esc_html_e( 'No posts found.', 'brightforge' );
				?>
			</p>

		<?php endif; ?>

	</main>

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>