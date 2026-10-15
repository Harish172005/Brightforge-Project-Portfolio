=== WPC Order Test for WooCommerce ===
Contributors: wpclever
Tags: woocommerce, payment gateway, test order, checkout, orders
Tested up to: 7.1
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Testing payment gateway for WooCommerce. Allows admins to simulate checkout without real payments and auto-completes the test orders.

== Description ==

WPC Order Test provides a dedicated payment gateway designed specifically for WooCommerce site administrators. It allows you to quickly and easily place test orders to verify your checkout process, email notifications, and order processing logic without having to make actual payments.

Once the "WPC Order Test" payment method is selected during checkout, the order will be automatically marked as "Completed," streamlining your testing workflow.

**Key Features:**

*   **Admin-Only Access:** The test payment gateway is only visible and usable by users with administrator roles (`manage_options` capability), ensuring your real customers never see it.
*   **Auto-Complete Orders:** Orders placed using this gateway are automatically marked as "Completed".
*   **Customizable Details:** Easily customize the payment method title, description, and instructions displayed on the checkout page from the WooCommerce settings.
*   **Safe Testing:** Simulate the entire checkout flow securely without processing real credit cards or external payments.

= Need more features? =

Please try other plugins from us:

- [WPC Smart Compare](https://wordpress.org/plugins/woo-smart-compare/ "WPC Smart Compare")
- [WPC Smart Quick View](https://wordpress.org/plugins/woo-smart-quick-view/ "WPC Smart Quick View")
- [WPC Smart Wishlist](https://wordpress.org/plugins/woo-smart-wishlist/ "WPC Smart Wishlist")
- [WPC Smart Notification](https://wordpress.org/plugins/wpc-smart-notification/ "WPC Smart Notification")
- [WPC AJAX Add to Cart](https://wordpress.org/plugins/wpc-ajax-add-to-cart/ "WPC AJAX Add to Cart")

== Installation ==

1. Upload the `wpc-order-test` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **WooCommerce > Settings > Payments**.
4. Enable the "WPC Order Test" gateway and configure its settings (Title, Description, Instructions).
5. Save changes.

== Frequently Asked Questions ==

= Why is the WPC Order Test payment method not showing up for my customers? =

This plugin is intentionally designed to be available only for site administrators. This prevents real customers from checking out without paying.

= Does this plugin process real payments? =

No, it completely bypasses any real payment processing and simply marks the order as paid and completed in WooCommerce.

== Changelog ==

= 1.0.5 =
* Updated: Compatible with WP 7.1 & Woo 11.1

= 1.0.4 =
* Fixed: Minor CSS/JS issues in the backend
* Updated: Compatible with WP 7.0 & Woo 10.9

= 1.0.3 =
* Updated: Optimized the code
* Updated: Compatible with WP 7.0 & Woo 10.8

= 1.0.2 =
* Fixed: Minor CSS/JS issues in the backend
* Updated: Compatible with WP 6.9 & Woo 10.7

= 1.0.1 =
* Fixed: Minor CSS/JS issues in the backend
* Updated: Compatible with WP 6.9 & Woo 10.6

= 1.0.0 =
* Initial release.
