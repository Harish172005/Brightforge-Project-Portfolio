<?php
/**
 * Brightforge theme setup.
 *
 * @package Brightforge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRIGHTFORGE_VERSION', '1.0.1' );

/**
 * Theme supports, menus and image sizes.
 */
function brightforge_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 48,
		'width'       => 180,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_image_size( 'brightforge-card', 640, 400, true );

	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'brightforge' ),
		'footer'  => __( 'Footer menu', 'brightforge' ),
	) );
}
add_action( 'after_setup_theme', 'brightforge_setup' );

/**
 * Sidebar and four footer widget areas.
 */
function brightforge_widgets() {
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);

	register_sidebar( array_merge( $defaults, array(
		'name' => __( 'Blog sidebar', 'brightforge' ),
		'id'   => 'blog-sidebar',
	) ) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array_merge( $defaults, array(
			/* translators: %d: column number */
			'name' => sprintf( __( 'Footer column %d', 'brightforge' ), $i ),
			'id'   => 'footer-' . $i,
		) ) );
	}
}
add_action( 'widgets_init', 'brightforge_widgets' );

/**
 * Enqueue the stylesheet and the small navigation script.
 */
function brightforge_assets() {
	wp_enqueue_style( 'brightforge-style', get_template_directory_uri() . '/style.css', array(), BRIGHTFORGE_VERSION );
	wp_enqueue_script( 'brightforge-nav', get_template_directory_uri() . '/assets/js/nav.js', array(), BRIGHTFORGE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'brightforge_assets' );

/**
 * Preload the heading font so text does not flash.
 */
function brightforge_preload_font() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_template_directory_uri() . '/assets/fonts/bricolage-grotesque-latin-wght-normal.woff2' )
	);
}
add_action( 'wp_head', 'brightforge_preload_font', 1 );

/**
 * Customizer: contact details shown in the top bar and footer.
 */
function brightforge_customize( $wp_customize ) {
	$wp_customize->add_section( 'brightforge_contact', array(
		'title'    => __( 'Contact details', 'brightforge' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'brightforge_phone', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'brightforge_phone', array(
		'label'   => __( 'Phone', 'brightforge' ),
		'section' => 'brightforge_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'brightforge_email', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'brightforge_email', array(
		'label'   => __( 'Email', 'brightforge' ),
		'section' => 'brightforge_contact',
		'type'    => 'email',
	) );
}
add_action( 'customize_register', 'brightforge_customize' );

/**
 * The statuses a project can have. Keep in step with the plugin's list.
 */
function brightforge_project_statuses() {
	return array( 'Planned', 'In Progress', 'Completed' );
}

/**
 * Allow ?project_status=... in the project filter URLs.
 */
function brightforge_query_vars( $vars ) {
	$vars[] = 'project_status';
	return $vars;
}
add_filter( 'query_vars', 'brightforge_query_vars' );

/**
 * Project listings: page size and the status filter.
 * Industry and technology work by themselves (public taxonomies).
 * Status is a custom field, so it needs a meta query.
 */
function brightforge_project_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$is_listing = $query->is_post_type_archive( 'project' )
		|| $query->is_tax( array( 'project_type', 'technologies' ) )
		|| ( $query->is_search() && 'project' === $query->get( 'post_type' ) );

	if ( ! $is_listing ) {
		return;
	}

	$query->set( 'posts_per_page', max( 1, (int) get_option( 'brightforge_projects_per_page', 9 ) ) );

	$status = sanitize_text_field( (string) $query->get( 'project_status' ) );

	// Only a known status is used, so nothing else reaches the query.
	if ( in_array( $status, brightforge_project_statuses(), true ) ) {
		$query->set( 'meta_query', array(
			array(
				'key'   => '_brightforge_project_status',
				'value' => $status,
			),
		) );
	}
}
add_action( 'pre_get_posts', 'brightforge_project_query' );
/**
 * Template helper: the first industry (project_type term) of a project.
 *
 * @param int $post_id Project ID.
 * @return WP_Term|null
 */
function brightforge_project_industry( $post_id ) {
	$terms = get_the_terms( $post_id, 'project_type' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
}

/**
 * Template helper: CSS class for an industry colour (e.g. industry-fintech).
 *
 * @param WP_Term|null $term Industry term.
 * @return string
 */
function brightforge_industry_class( $term ) {
	return $term ? 'industry-' . sanitize_html_class( $term->slug ) : 'industry-default';
}

/**
 * Template helper: a project's status label, if the plugin has saved one.
 *
 * @param int $post_id Project ID.
 * @return string
 */
function brightforge_project_status( $post_id ) {
	return (string) get_post_meta( $post_id, '_brightforge_project_status', true );
}
