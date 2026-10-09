<?php
/**
 * Project card used in grids. Colour spine comes from the industry.
 *
 * @package Brightforge
 */

$industry = brightforge_project_industry( get_the_ID() );
$status   = brightforge_project_status( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card ' . brightforge_industry_class( $industry ) ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'brightforge-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="card__body">
		<p class="card__meta">
			<?php if ( $industry ) : ?>
				<span class="badge"><?php echo esc_html( $industry->name ); ?></span>
			<?php endif; ?>
			<?php if ( $status ) : ?>
				<span class="status"><?php echo esc_html( $status ); ?></span>
			<?php endif; ?>
		</p>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
	</div>
</article>
