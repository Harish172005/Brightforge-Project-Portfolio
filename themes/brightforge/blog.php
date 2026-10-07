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

					<article
						id="post-<?php the_ID(); ?>"
						<?php post_class( 'blog-post' ); ?>
					>

						<?php if ( has_post_thumbnail() ) : ?>

							<div class="blog-post__image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'large' ); ?>
								</a>
							</div>

						<?php endif; ?>

						<div class="blog-post__content">

							<h2 class="blog-post__title">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>

							<div class="blog-post__meta">
								<?php
								echo esc_html( get_the_date() );
								?>
							</div>

							<div class="blog-post__excerpt">
								<?php the_excerpt(); ?>
							</div>

							<a
								class="blog-post__read-more"
								href="<?php the_permalink(); ?>"
							>
								<?php esc_html_e( 'Read More', 'brightforge' ); ?>
							</a>

						</div>

					</article>

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