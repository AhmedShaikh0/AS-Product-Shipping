<?php
if (!defined('ABSPATH')) exit;

/**
 * Convert weight string to kg
 * 
 * @param string $weight_data Weight data string
 * @return float Weight in kg
 */
function asprsh_convert_weight_to_kg($weight_data) {
    if (empty($weight_data)) return 1.0;
    
    // If it's already a number, assume it's in kg
    if (is_numeric($weight_data)) {
        return floatval($weight_data);
    }
    
    // Try to extract number and unit
    if (preg_match('/(\d+(?:\.\d+)?)\s*(kg|g|gram|kilogram)/i', $weight_data, $m)) {
        $value = floatval($m[1]);
        $unit  = strtolower($m[2]);
        if (in_array($unit, ['g','gram'])) return $value / 1000;
        return $value;
    }
    
    // Handle simple format like "500g" or "2kg"
    $w = strtolower(trim($weight_data));
    if (preg_match('/^(\d+(?:\.\d+)?)(kg|g)$/', $w, $m)) {
        $value = floatval($m[1]);
        $unit  = $m[2];
        return $unit === 'g' ? $value / 1000 : $value;
    }
    
    // Legacy format support
    $legacy = [
        '50g'=>0.05,'100g'=>0.1,'250g'=>0.25,'500g'=>0.5,
        '1kg'=>1.0,'1.5kg'=>1.5,'2kg'=>2.0,'5kg'=>5.0
    ];
    if (isset($legacy[$w])) return $legacy[$w];
    
    // Default to 1kg if we can't parse
    return 1.0;
}

/**
 * Get cart item weight
 * 
 * @param array $cart_item Cart item data
 * @return string Weight data
 */
function asprsh_get_cart_item_weight($cart_item) {
    // Check if we've already saved weight data
    if (!empty($cart_item['asprsh_weight'])) {
        return $cart_item['asprsh_weight'];
    }
    
    // Get weight from product
    $product_id = $cart_item['product_id'];
    $weight = get_post_meta($product_id, '_asprsh_weight', true);
    
    if (!empty($weight)) {
        return $weight;
    }
    
    // Fallback to product's built-in weight
    $product = $cart_item['data'];
    if ($product && $product->has_weight()) {
        return $product->get_weight();
    }
    
    // Default to 1kg
    return '1';
}

/**
 * Get order item weight
 * 
 * @param WC_Order_Item $order_item Order item
 * @return string Weight data
 */
function asprsh_get_order_item_weight($order_item) {
    // Check if we've saved weight data
    $weight = wc_get_order_item_meta($order_item->get_id(), 'Weight', true);
    if (!empty($weight)) return $weight;
    
    // Get product ID and check for custom weight
    $product_id = $order_item->get_product_id();
    $weight = get_post_meta($product_id, '_asprsh_weight', true);
    if (!empty($weight)) return $weight . ' kg';
    
    // Default to 1kg
    return '1 kg';
}