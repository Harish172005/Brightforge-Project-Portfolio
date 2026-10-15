<?php
/**
 * WPC Order Test Payment Gateway
 */

defined( 'ABSPATH' ) || exit;

class WC_Gateway_Wpcto extends WC_Payment_Gateway {
	public $instructions;

	/**
	 * Constructor calls the setup function
	 */
	public function __construct() {
		$this->id                 = 'wpcto';
		$this->icon               = apply_filters( 'wpcto_icon', '' );
		$this->has_fields         = false;
		$this->method_title       = esc_html__( 'WPC Order Test', 'wpc-order-test' );
		$this->method_description = esc_html__( 'Allows admin users to place test orders and marks them as completed.', 'wpc-order-test' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title        = $this->get_option( 'title', esc_html__( 'WPC Order Test', 'wpc-order-test' ) );
		$this->description  = $this->get_option( 'description', esc_html__( 'Place a test order. The order will be completed automatically.', 'wpc-order-test' ) );
		$this->instructions = $this->get_option( 'instructions', $this->description );

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	/**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'enabled'      => [
				'title'   => esc_html__( 'Enable/Disable', 'wpc-order-test' ),
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Enable WPC Order Test', 'wpc-order-test' ),
				'default' => 'yes',
			],
			'title'        => [
				'title'       => esc_html__( 'Title', 'wpc-order-test' ),
				'type'        => 'text',
				'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'wpc-order-test' ),
				'default'     => esc_html__( 'WPC Order Test', 'wpc-order-test' ),
				'desc_tip'    => true,
			],
			'description'  => [
				'title'       => esc_html__( 'Description', 'wpc-order-test' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'Payment method description that the customer will see on your checkout.', 'wpc-order-test' ),
				'default'     => esc_html__( 'Place a test order. The order will be completed automatically.', 'wpc-order-test' ),
				'desc_tip'    => true,
			],
			'instructions' => [
				'title'       => esc_html__( 'Instructions', 'wpc-order-test' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'Instructions that will be added to the thank you page and emails.', 'wpc-order-test' ),
				'default'     => esc_html__( 'Test order placed successfully.', 'wpc-order-test' ),
				'desc_tip'    => true,
			],
		];
	}

	/**
	 * Check if this gateway is available
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = parent::is_available();

		if ( ! $is_available ) {
			return false;
		}

		// Only available for administrators
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		// Mark as completed
		$order->payment_complete();

		// Alternatively, or additionally, to explicitly set to Completed status
		$order->update_status( 'completed', esc_html__( 'Test order payment completed via WPC Order Test.', 'wpc-order-test' ) );

		// Empty cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return [
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		];
	}
}
