<?php
/**
 * Single blog post.
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap layout">
	<div class="layout__main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="page-head">
					<h1><?php the_title(); ?></h1>
					<p class="meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<?php esc_html_e( 'by', 'brightforge' ); ?> <?php the_author(); ?>
					</p>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry__image"><?php the_post_thumbnail( 'large' ); ?></div>
				<?php endif; ?>

				<div class="entry__content">
					<?php the_content(); ?>
				</div>

				<footer class="entry__terms">
					<?php the_category( ', ' ); ?>
					<?php the_tags( '<p class="tags">', ', ', '</p>' ); ?>
				</footer>
			</article>

			<?php the_post_navigation(); ?>
		<?php endwhile; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
