=== AS Product Shipping ===
Contributors: ahmedshaikh0
Tags: woocommerce, shipping, flat rate, weight based, ecommerce
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.1
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A WooCommerce shipping plugin with flat rate and weight-based shipping options for individual products.

== Description ==

AS Product Shipping is a WooCommerce shipping plugin that allows store owners to set custom shipping rates and weights for individual products. The plugin provides both flat rate and weight-based shipping options without requiring complex WooCommerce shipping zones configuration.

### Features:

* **Per-Product Shipping Options**: Set custom shipping options for individual products
* **Flat Rate Shipping**: Charge a fixed rate per product
* **Weight-Based Shipping**: Calculate shipping based on product weight
* **Product Weight Management**: Easily set custom weights for your products
* **Flexible Configuration**: Works with or without global shipping zone settings
* **Order Details**: Weight information is displayed in order details
* **No Need for Default Shipping**: Replaces WooCommerce's default shipping features - no need to configure shipping in the Product Data box

### How It Works:

1. Install and activate the plugin
2. Go to WooCommerce > Settings > Shipping
3. Add the "AS Product Shipping" method to your shipping zones
4. Configure your global shipping rates (flat rate or weight-based)
5. Set custom weights and shipping options for your products in the product editor using this plugin's custom shipping section (no need to use WooCommerce's default shipping features in the Product Data box)
6. Customers will see your shipping options at checkout

This plugin is perfect for store owners who want granular control over shipping costs for individual products while maintaining a simple overall shipping configuration.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/as-product-shipping` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to WooCommerce > Settings > Shipping to configure the shipping method

== Frequently Asked Questions ==

= Do I need to set up shipping zones? =

No, this plugin works without requiring shipping zones configuration.

= How do I set product weights? =

You can set custom weights for your products in the product editor under the "Shipping" tab.

= Do I need to use WooCommerce's default shipping settings? =

No, you do not need to use WooCommerce's default shipping features in the Product Data box. This plugin's custom shipping section will override any default shipping settings, so you can focus on using just this plugin's interface.

= Can I use both flat rate and weight-based shipping? =

You can enable both options globally, but only one will be used for calculations. The plugin will use flat rate if enabled, otherwise it will use weight-based shipping.

= Can I set different shipping options for different products? =

Yes! You can enable custom shipping for individual products and set either flat rate or weight-based shipping with custom rates.

== Screenshots ==

1. Shipping method configuration
2. Product shipping options
3. Weight display on product page
4. Shipping options at checkout

== Changelog ==

= 1.0.1 =
* Fixed issues with inline CSS/JS - now properly enqueued using WordPress functions
* Fixed generic naming prefixes to be more unique
* Improved code structure and organization

= 1.0.0 =
* Initial release
* Added per-product shipping options
* Added flat rate and weight-based shipping calculation
* Improved UI/UX with dedicated admin menu
* Enhanced shipping calculation logic
* Fixed security issues with output escaping
* Updated all _e() calls to esc_html_e() for better security
* Improved overall code security and compliance with WordPress coding standards

== Upgrade Notice ==

= 1.0.1 =
Fixed issues with inline CSS/JS and generic naming prefixes. Improved code structure.

= 1.0.0 =
Initial release of AS Product Shipping with per-product shipping options and improved security.