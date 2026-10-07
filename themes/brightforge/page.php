<?php
/**
 * Static pages (About, Services, Contact, Privacy Policy...).
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap wrap--narrow">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
			<header class="page-head">
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="entry__content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</div>
<?php
get_footer();
