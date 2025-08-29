<?php
if (!defined('ABSPATH')) exit;

/**
 * AS Product Shipping Method
 * 
 * A custom WooCommerce shipping method that provides both flat rate and weight-based shipping.
 */
class ASPRSH_Product_Shipping_Method extends WC_Shipping_Method {
    
    /**
     * Constructor
     */
    public function __construct($instance_id = 0) {
        $this->id = 'asprsh_product_shipping';
        $this->instance_id = absint($instance_id);
        $this->method_title = esc_html__('AS Product Shipping', 'as-product-shipping');
        $this->method_description = esc_html__('Custom shipping method with flat rate and weight-based options.', 'as-product-shipping');
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );
        
        $this->init();
    }
    
    /**
     * Initialize the shipping method
     */
    public function init() {
        // Load the settings
        $this->init_form_fields();
        $this->init_settings();
        
        // Define user set variables
        $this->title = $this->get_option('title');
        $this->flat_rate_enabled = $this->get_option('flat_rate_enabled', 'no');
        $this->weight_based_enabled = $this->get_option('weight_based_enabled', 'no');
        $this->base_rate = $this->get_option('base_rate', '0');
        
        // Save settings in admin
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }
    
    /**
     * Initialize form fields for the settings
     */
    public function init_form_fields() {
        $this->instance_form_fields = array(
            'title' => array(
                'title' => esc_html__('Method Title', 'as-product-shipping'),
                'type' => 'text',
                'description' => esc_html__('This controls the title which the user sees during checkout.', 'as-product-shipping'),
                'default' => esc_html__('AS Product Shipping', 'as-product-shipping'),
                'desc_tip' => true,
            ),
            'flat_rate_enabled' => array(
                'title' => esc_html__('Flat Rate Shipping', 'as-product-shipping'),
                'type' => 'checkbox',
                'label' => esc_html__('Enable flat rate shipping', 'as-product-shipping'),
                'default' => 'no',
            ),
            'weight_based_enabled' => array(
                'title' => esc_html__('Weight Based Shipping', 'as-product-shipping'),
                'type' => 'checkbox',
                'label' => esc_html__('Enable weight based shipping', 'as-product-shipping'),
                'default' => 'no',
            ),
            'base_rate' => array(
                'title' => esc_html__('Base Rate', 'as-product-shipping'),
                'type' => 'price',
                'description' => esc_html__('The base shipping rate used for calculations.', 'as-product-shipping'),
                'default' => '0',
                'desc_tip' => true,
            ),
        );
    }
    
    /**
     * Calculate shipping cost
     */
    public function calculate_shipping($package = array()) {
        $cart_weight = 0;
        $cart_total = 0;
        $custom_shipping_cost = 0;
        $use_custom_shipping = false;
        
        // Check if any product in the cart has custom shipping enabled
        foreach ($package['contents'] as $item_id => $values) {
            $_product = $values['data'];
            $product_id = $_product->get_id();
            
            // Check if custom shipping is enabled for this product
            $custom_shipping_enabled = get_post_meta($product_id, '_asprsh_custom_shipping_enabled', true);
            
            if ($custom_shipping_enabled === 'yes') {
                $use_custom_shipping = true;
                
                // Get shipping type for this product
                $shipping_type = get_post_meta($product_id, '_asprsh_shipping_type', true);
                
                if ($shipping_type === 'flat') {
                    // Flat rate for this product
                    $custom_rate = get_post_meta($product_id, '_asprsh_custom_shipping_rate', true);
                    $custom_shipping_cost += floatval($custom_rate) * $values['quantity'];
                } elseif ($shipping_type === 'weight') {
                    // Weight-based shipping for this product
                    $product_weight = get_post_meta($product_id, '_asprsh_weight', true);
                    if (empty($product_weight)) {
                        $product_weight = 1; // Default to 1kg
                    }
                    
                    $custom_rate = get_post_meta($product_id, '_asprsh_custom_shipping_rate', true);
                    if (empty($custom_rate)) {
                        $custom_rate = $this->base_rate; // Fallback to global rate
                    }
                    
                    $item_weight = floatval($product_weight) * $values['quantity'];
                    $cart_weight += $item_weight;
                    
                    // Calculate weight-based cost for this item
                    $item_cost = ($item_weight <= 1.0) ? floatval($custom_rate) : (floatval($custom_rate) * $item_weight);
                    $custom_shipping_cost += $item_cost;
                }
            } else {
                // Use global settings for this product
                // Get weight from product
                $product_weight = $_product->get_weight();
                
                // If no weight set on product, check for custom weight meta
                if (empty($product_weight)) {
                    $product_weight = get_post_meta($product_id, '_asprsh_weight', true);
                }
                
                // If still no weight, use default of 1kg
                if (empty($product_weight)) {
                    $product_weight = 1;
                }
                
                $cart_weight += floatval($product_weight) * $values['quantity'];
                $cart_total += $values['line_total'];
            }
        }
        
        $rate = 0;
        
        // Calculate shipping based on method settings or custom product settings
        if ($use_custom_shipping) {
            // Use custom shipping cost calculated above
            $rate = $custom_shipping_cost;
        } else {
            // Calculate shipping based on global settings
            if ($this->flat_rate_enabled === 'yes') {
                // Flat rate: charge once per package
                $rate = floatval($this->base_rate);
            } elseif ($this->weight_based_enabled === 'yes') {
                // Weight rule: up to 1kg = base rate; otherwise base rate * total weight
                $base_rate = floatval($this->base_rate);
                $rate = ($cart_weight <= 1.0) ? $base_rate : ($base_rate * $cart_weight);
            }
        }
        
        // Add the rate only if it's greater than 0
        if ($rate > 0) {
            $this->add_rate(array(
                'id' => $this->get_rate_id(),
                'label' => $this->title,
                'cost' => $rate,
                'package' => $package,
            ));
        }
    }
}