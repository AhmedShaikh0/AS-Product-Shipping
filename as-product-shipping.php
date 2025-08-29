<?php
/**
 * Plugin Name: AS Product Shipping
 * Description: A WooCommerce shipping plugin that provides both flat rate and weight-based shipping options for individual products. Allows store owners to set custom shipping rates and weights per product without requiring complex shipping zones configuration.
 * Version: 1.0.1
 * Author: Ahmed Shaikh
 * Author URI: https://github.com/ahmedShaikh0
 * Plugin URI: https://github.com/ahmedShaikh0/as-product-shipping
 * Text Domain: as-product-shipping
 * 
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('ASPRSH_VERSION', '1.0.1');
define('ASPRSH_PATH', plugin_dir_path(__FILE__));
define('ASPRSH_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class ASPRSH_Product_Shipping {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize the plugin
        add_action('init', array($this, 'init'));
        add_action('woocommerce_init', array($this, 'woocommerce_init'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Translations are automatically loaded by WordPress since version 4.6
    }
    
    /**
     * Initialize WooCommerce specific functionality
     */
    public function woocommerce_init() {
        // Add shipping method
        add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));
        
        // Register our custom shipping method
        add_action('woocommerce_shipping_init', array($this, 'shipping_init'));
    }
    
    /**
     * Add our shipping method to WooCommerce
     */
    public function add_shipping_method($methods) {
        $methods['asprsh_product_shipping'] = 'ASPRSH_Product_Shipping_Method';
        return $methods;
    }
    
    /**
     * Initialize our shipping method
     */
    public function shipping_init() {
        if (!class_exists('ASPRSH_Product_Shipping_Method')) {
            include_once ASPRSH_PATH . 'includes/class-as-product-shipping-method.php';
        }
    }
}

/**
 * Initialize the plugin
 */
function asprsh_product_shipping_init() {
    new ASPRSH_Product_Shipping();
}
add_action('plugins_loaded', 'asprsh_product_shipping_init');

// Include admin functionality
if (is_admin()) {
    include_once ASPRSH_PATH . 'includes/admin/class-as-product-shipping-admin.php';
}

// Include frontend functionality
include_once ASPRSH_PATH . 'includes/frontend/class-as-product-shipping-frontend.php';

// Include weight helpers
include_once ASPRSH_PATH . 'includes/helpers/weight-helpers.php';

// Include order functions
include_once ASPRSH_PATH . 'includes/functions/order-functions.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'asprsh_product_shipping_activate');
register_deactivation_hook(__FILE__, 'asprsh_product_shipping_deactivate');

function asprsh_product_shipping_activate() {
    // Activation code if needed
    flush_rewrite_rules();
}

function asprsh_product_shipping_deactivate() {
    // Deactivation code if needed
    flush_rewrite_rules();
}