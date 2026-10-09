<?php
/**
 * A blog post (or any other result) in a list.
 *
 * @package Brightforge
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-row' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-row__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'brightforge-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="post-row__body">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="meta">
			<?php if ( 'post' !== get_post_type() ) : ?>
				<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>,
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>
		<?php the_excerpt(); ?>
	</div>
</article>
