<?php
if (!defined('ABSPATH')) exit;

/**
 * AS Product Shipping Frontend
 * 
 * Frontend functionality for the AS Product Shipping plugin.
 */
class ASPRSH_Product_Shipping_Frontend {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Display weight on product pages
        add_action('woocommerce_product_meta_end', array($this, 'display_product_weight'));
        
        // Save weight data to order items during checkout
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_weight_to_order_item'), 10, 4);
        
        // Display weight in order details
        add_action('woocommerce_order_item_meta_end', array($this, 'display_order_item_weight'), 10, 4);
    }
    
    /**
     * Display product weight on product pages
     */
    public function display_product_weight() {
        global $product;
        
        $weight = get_post_meta($product->get_id(), '_asprsh_weight', true);
        
        if (!empty($weight)) {
            echo '<div class="asprsh-product-weight">';
            echo '<strong>' . esc_html__('Weight:', 'as-product-shipping') . '</strong> ';
            echo esc_html($weight) . ' kg';
            echo '</div>';
        }
    }
    
    /**
     * Save weight data to order items during checkout
     */
    public function save_weight_to_order_item($item, $cart_item_key, $values, $order) {
        $product_id = $values['product_id'];
        $weight = get_post_meta($product_id, '_asprsh_weight', true);
        
        if (!empty($weight)) {
            $item->add_meta_data('Weight', $weight . ' kg', true);
        }
    }
    
    /**
     * Display weight in order details
     */
    public function display_order_item_weight($item_id, $item, $order, $plain_text = false) {
        $weight = wc_get_order_item_meta($item_id, 'Weight', true);
        
        if (!empty($weight)) {
            if ($plain_text) {
                echo "\n" . esc_html__('Weight:', 'as-product-shipping') . ' ' . esc_html($weight);
            } else {
                echo '<br/><small class="asprsh-order-item-weight">' . esc_html__('Weight:', 'as-product-shipping') . ' ' . esc_html($weight) . '</small>';
            }
        }
    }
}

// Initialize the frontend class
new ASPRSH_Product_Shipping_Frontend();
