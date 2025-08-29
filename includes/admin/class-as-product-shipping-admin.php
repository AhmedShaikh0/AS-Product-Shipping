<?php
if (!defined('ABSPATH')) exit;

/**
 * AS Product Shipping Admin
 * 
 * Admin functionality for the AS Product Shipping plugin.
 */
class ASPRSH_Product_Shipping_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add separate metabox for shipping settings
        add_action('add_meta_boxes', array($this, 'add_shipping_metabox'));
        add_action('save_post', array($this, 'save_shipping_metabox'));
        
        // Add weight column to products list
        add_filter('manage_edit-product_columns', array($this, 'add_weight_column'));
        add_action('manage_product_posts_custom_column', array($this, 'render_weight_column'), 10, 2);
        
        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Add admin menu item
     */
    public function add_admin_menu() {
        add_menu_page(
            __('AS Product Shipping', 'as-product-shipping'),
            __('AS Shipping', 'as-product-shipping'),
            'manage_woocommerce',
            'as-product-shipping',
            array($this, 'settings_page'),
            'dashicons-products',
            56
        );
    }
    
    /**
     * Settings page callback
     */
    public function settings_page() {
        // Redirect to WooCommerce shipping settings
        wp_redirect(admin_url('admin.php?page=wc-settings&tab=shipping'));
        exit;
    }
    
    /**
     * Add separate metabox for shipping settings
     */
    public function add_shipping_metabox() {
        add_meta_box(
            'as-product-shipping-metabox',
            __('AS Product Shipping', 'as-product-shipping'),
            array($this, 'render_shipping_metabox'),
            'product',
            'normal',
            'default'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_assets($hook) {
        // Only enqueue on product edit pages
        if ($hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }
        
        global $post_type;
        if ($post_type !== 'product') {
            return;
        }
        
        // Enqueue our CSS
        wp_enqueue_style(
            'asprsh-admin-css',
            ASPRSH_URL . 'assets/css/admin.css',
            array(),
            ASPRSH_VERSION
        );
        
        // Enqueue our JS
        wp_enqueue_script(
            'asprsh-admin-js',
            ASPRSH_URL . 'assets/js/admin.js',
            array('jquery'),
            ASPRSH_VERSION,
            true
        );
    }
    
    /**
     * Render shipping metabox
     */
    public function render_shipping_metabox($post) {
        // Add nonce for security
        wp_nonce_field('asprsh_shipping_metabox', 'asprsh_shipping_metabox_nonce');
        
        // Get current values
        $custom_shipping_enabled = get_post_meta($post->ID, '_asprsh_custom_shipping_enabled', true);
        $shipping_type = get_post_meta($post->ID, '_asprsh_shipping_type', true);
        $custom_shipping_rate = get_post_meta($post->ID, '_asprsh_custom_shipping_rate', true);
        $weight = get_post_meta($post->ID, '_asprsh_weight', true);
        
        // Set default values
        if (!$shipping_type) {
            $shipping_type = 'default';
        }
        
        ?>
        <div class="asprsh-shipping-options">
            <p class="asprsh-shipping-description" style="font-weight: bold; color: #0073aa;">
                <?php esc_html_e('Note: You do not need to use WooCommerce\'s default shipping features in the Product Data box. This custom shipping section will override any default shipping settings.', 'as-product-shipping'); ?>
            </p>
            
            <div class="asprsh-shipping-field">
                <label for="asprsh_custom_shipping_enabled">
                    <input type="checkbox" id="asprsh_custom_shipping_enabled" name="asprsh_custom_shipping_enabled" <?php checked($custom_shipping_enabled, 'yes'); ?> value="yes" />
                    <?php esc_html_e('Enable Custom Shipping', 'as-product-shipping'); ?>
                </label>
                <p class="asprsh-shipping-description"><?php esc_html_e('Enable custom shipping options for this product (Flat Rate or Weight Based)', 'as-product-shipping'); ?></p>
            </div>
            
            <div class="asprsh-shipping-field" id="asprsh_shipping_type_field">
                <label for="asprsh_shipping_type"><?php esc_html_e('Shipping Type', 'as-product-shipping'); ?></label>
                <select id="asprsh_shipping_type" name="asprsh_shipping_type">
                    <option value="default" <?php selected($shipping_type, 'default'); ?>><?php esc_html_e('Use Global Settings', 'as-product-shipping'); ?></option>
                    <option value="flat" <?php selected($shipping_type, 'flat'); ?>><?php esc_html_e('Flat Rate', 'as-product-shipping'); ?></option>
                    <option value="weight" <?php selected($shipping_type, 'weight'); ?>><?php esc_html_e('Weight Based', 'as-product-shipping'); ?></option>
                </select>
                <p class="asprsh-shipping-description"><?php esc_html_e('Select the shipping type for this product: Flat Rate (fixed price) or Weight Based (calculated by weight)', 'as-product-shipping'); ?></p>
            </div>
            
            <div class="asprsh-shipping-field" id="asprsh_custom_shipping_rate_field">
                <label for="asprsh_custom_shipping_rate"><?php esc_html_e('Custom Shipping Rate', 'as-product-shipping'); ?></label>
                <input type="number" id="asprsh_custom_shipping_rate" name="asprsh_custom_shipping_rate" step="any" min="0" value="<?php echo esc_attr($custom_shipping_rate); ?>" />
                <p class="asprsh-shipping-description"><?php esc_html_e('Enter a custom shipping rate for this product (applies to Flat Rate or Weight Based shipping)', 'as-product-shipping'); ?></p>
            </div>
            
            <div class="asprsh-shipping-field" id="asprsh_weight_field">
                <label for="asprsh_weight"><?php esc_html_e('Custom Weight (kg)', 'as-product-shipping'); ?></label>
                <input type="number" id="asprsh_weight" name="asprsh_weight" step="any" min="0" value="<?php echo esc_attr($weight); ?>" />
                <p class="asprsh-shipping-description"><?php esc_html_e('Enter the product weight in kilograms. Used for shipping calculations.', 'as-product-shipping'); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save shipping metabox data
     */
    public function save_shipping_metabox($post_id) {
        // Check if nonce is set
        if (!isset($_POST['asprsh_shipping_metabox_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['asprsh_shipping_metabox_nonce'])), 'asprsh_shipping_metabox')) {
            return;
        }
        
        // Check if user has permission to edit
        if (!current_user_can('edit_product', $post_id)) {
            return;
        }
        
        // Check if not autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save custom shipping enabled
        $custom_shipping_enabled = isset($_POST['asprsh_custom_shipping_enabled']) ? 'yes' : 'no';
        update_post_meta($post_id, '_asprsh_custom_shipping_enabled', $custom_shipping_enabled);
        
        // Save shipping type
        if (isset($_POST['asprsh_shipping_type'])) {
            update_post_meta($post_id, '_asprsh_shipping_type', sanitize_text_field(wp_unslash($_POST['asprsh_shipping_type'])));
        }
        
        // Save custom shipping rate
        if (isset($_POST['asprsh_custom_shipping_rate'])) {
            update_post_meta($post_id, '_asprsh_custom_shipping_rate', wc_format_decimal(sanitize_text_field(wp_unslash($_POST['asprsh_custom_shipping_rate']))));
        }
        
        // Save weight
        if (isset($_POST['asprsh_weight'])) {
            update_post_meta($post_id, '_asprsh_weight', wc_format_decimal(sanitize_text_field(wp_unslash($_POST['asprsh_weight']))));
        }
    }
    
    /**
     * Add weight column to products list
     */
    public function add_weight_column($columns) {
        $columns['asprsh_weight'] = __('Weight (kg)', 'as-product-shipping');
        return $columns;
    }
    
    /**
     * Render weight column
     */
    public function render_weight_column($column, $post_id) {
        if ($column === 'asprsh_weight') {
            $weight = get_post_meta($post_id, '_asprsh_weight', true);
            if (!empty($weight)) {
                echo esc_html($weight);
            } else {
                echo '—';
            }
        }
    }
}

// Initialize the admin class
new ASPRSH_Product_Shipping_Admin();