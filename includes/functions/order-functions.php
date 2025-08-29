<?php
if (!defined('ABSPATH')) exit;

/**
 * Calculate vendor shipping total for a package
 * 
 * @param array $package_contents Package contents
 * @return array Shipping calculation results
 */
function asprsh_calculate_shipping_total_for_package($package_contents) {
    $total_weight = 0;
    $total_cost = 0;
    
    // Get shipping method settings
    $shipping_method = WC()->shipping->load_shipping_methods()['asprsh_product_shipping'] ?? null;
    
    if (!$shipping_method) {
        return [
            'total' => 0,
            'breakdown' => []
        ];
    }
    
    // Calculate total weight
    foreach ($package_contents as $item) {
        if (empty($item['product_id'])) continue;
        
        $qty = intval($item['quantity']);
        $weight_data = asprsh_get_cart_item_weight($item);
        $weight_kg = asprsh_convert_weight_to_kg($weight_data);
        $total_weight += ($weight_kg * $qty);
    }
    
    // Calculate shipping cost based on method settings
    $flat_rate_enabled = $shipping_method->flat_rate_enabled ?? 'no';
    $weight_based_enabled = $shipping_method->weight_based_enabled ?? 'no';
    $base_rate = floatval($shipping_method->base_rate ?? 0);
    
    if ($flat_rate_enabled === 'yes') {
        // Flat rate: charge once per package
        $total_cost = $base_rate;
    } elseif ($weight_based_enabled === 'yes') {
        // Weight rule: up to 1kg = base rate; otherwise base rate * total weight
        $total_cost = ($total_weight <= 1.0) ? $base_rate : ($base_rate * $total_weight);
    }
    
    return [
        'total' => $total_cost,
        'weight' => $total_weight,
        'breakdown' => []
    ];
}

/**
 * Inject our custom shipping rate
 */
add_filter('woocommerce_package_rates', function($rates, $package) {
    // Ensure we have contents
    if (empty($package['contents'])) return $rates;
    
    $calc = asprsh_calculate_shipping_total_for_package($package['contents']);
    $total_cost = floatval($calc['total']);
    
    // If we calculated something, inject our rate
    if ($total_cost > 0) {
        $rate_id = 'asprsh_product_shipping';
        $label = esc_html__('Shipping Fee', 'as-product-shipping');
        
        // Build a WC_Shipping_Rate without requiring registration
        $rates[$rate_id] = new WC_Shipping_Rate(
            $rate_id,       // id
            $label,         // label
            $total_cost,    // cost
            [],             // taxes
            'asprsh_product_shipping' // method_id
        );
    }
    
    return $rates;
}, 999, 2);