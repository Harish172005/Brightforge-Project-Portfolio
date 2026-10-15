<?php
/*
Plugin Name: WPC Order Test for WooCommerce
Plugin URI: https://wpclever.net/
Description: Testing payment gateway for WooCommerce. Allows admins to simulate checkout without real payments and auto-completes the test orders.
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-order-test
Domain Path: /languages/
Requires Plugins: woocommerce
Version: 1.0.5
Requires at least: 5.9
WC requires at least: 4.0
WC tested up to: 11.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WPCTO_VERSION' ) && define( 'WPCTO_VERSION', '1.0.5' );
! defined( 'WPCTO_LITE' ) && define( 'WPCTO_LITE', __FILE__ );
! defined( 'WPCTO_FILE' ) && define( 'WPCTO_FILE', __FILE__ );
! defined( 'WPCTO_URI' ) && define( 'WPCTO_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCTO_DIR' ) && define( 'WPCTO_DIR', plugin_dir_path( __FILE__ ) );

// WPC Core
require_once __DIR__ . '/includes/wpc-core/wpc-core.php';
wpc_core_register( [
	'file'    => __FILE__,
	'version' => WPCTO_VERSION,
	'prefix'  => 'wpcto',
] );

/**
 * Register WPC Order Test Gateway
 */
add_action( 'plugins_loaded', 'wpcto_init_gateway_class' );
function wpcto_init_gateway_class() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcto-gateway.php';
}

add_filter( 'woocommerce_payment_gateways', 'wpcto_add_gateway_class' );
function wpcto_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_Gateway_Wpcto';

	return $gateways;
}

/**
 * Add settings link
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpcto_plugin_links' );
function wpcto_plugin_links( $links ) {
	$plugin_links = [
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wpcto' ) . '">' . esc_html__( 'Settings', 'wpc-order-test' ) . '</a>'
	];

	return array_merge( $plugin_links, $links );
}
