<?php
/**
 * Brightforge Child theme.
 *
 * The parent loads its own stylesheet. This file adds the child stylesheet
 * after it, so rules written in the child's style.css win.
 *
 * @package Brightforge_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brightforge_child_assets() {
	wp_enqueue_style(
		'brightforge-child-style',
		get_stylesheet_uri(),
		array( 'brightforge-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'brightforge_child_assets' );
