<?php
/**
 * Single project (case study).
 *
 * @package Brightforge
 */

get_header();
?>
<div class="wrap wrap--narrow">
	<?php
	while ( have_posts() ) :
		the_post();

		$industry  = brightforge_project_industry( get_the_ID() );
		$status    = brightforge_project_status( get_the_ID() );
		$completed = get_post_meta( get_the_ID(), '_harish_project_completed', true );
		$url       = get_post_meta( get_the_ID(), '_harish_project_url', true );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry ' . brightforge_industry_class( $industry ) ); ?>>
			<header class="page-head">
				<?php if ( $industry ) : ?>
					<a class="badge" href="<?php echo esc_url( get_term_link( $industry ) ); ?>"><?php echo esc_html( $industry->name ); ?></a>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry__image"><?php the_post_thumbnail( 'large' ); ?></div>
			<?php endif; ?>

			<dl class="facts">
				<?php if ( $status ) : ?>
					<div><dt><?php esc_html_e( 'Status', 'brightforge' ); ?></dt><dd><?php echo esc_html( $status ); ?></dd></div>
				<?php endif; ?>
				<?php if ( $completed ) : ?>
					<div><dt><?php esc_html_e( 'Completed', 'brightforge' ); ?></dt><dd><?php echo esc_html( $completed ); ?></dd></div>
				<?php endif; ?>
				<?php if ( has_term( '', 'technologies' ) ) : ?>
					<div><dt><?php esc_html_e( 'Technologies', 'brightforge' ); ?></dt><dd><?php echo wp_kses_post( get_the_term_list( get_the_ID(), 'technologies', '', ', ' ) ); ?></dd></div>
				<?php endif; ?>
				<?php if ( $url ) : ?>
					<div><dt><?php esc_html_e( 'Live site', 'brightforge' ); ?></dt><dd><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ); ?></a></dd></div>
				<?php endif; ?>
			</dl>

			<div class="entry__content">
				<?php the_content(); ?>
			</div>
		</article>

		<?php
		the_post_navigation( array(
			'prev_text' => '%title',
			'next_text' => '%title',
		) );
		?>
	<?php endwhile; ?>
</div>
<?php
get_footer();
