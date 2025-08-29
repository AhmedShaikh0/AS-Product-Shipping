jQuery(document).ready(function($) {
    function toggleShippingFields() {
        var customEnabled = $('#asprsh_custom_shipping_enabled').is(':checked');
        var shippingType = $('#asprsh_shipping_type').val();
        
        // Show/hide shipping type and rate fields
        if (customEnabled) {
            $('#asprsh_shipping_type_field').show();
            if (shippingType !== 'default') {
                $('#asprsh_custom_shipping_rate_field').show();
            } else {
                $('#asprsh_custom_shipping_rate_field').hide();
            }
        } else {
            $('#asprsh_shipping_type_field').hide();
            $('#asprsh_custom_shipping_rate_field').hide();
        }
        
        // Show/hide weight field based on shipping type
        if (customEnabled && shippingType === 'weight') {
            $('#asprsh_weight_field').show();
        } else if (!customEnabled) {
            $('#asprsh_weight_field').show();
        } else {
            $('#asprsh_weight_field').hide();
        }
    }
    
    // Initial toggle
    toggleShippingFields();
    
    // Toggle on changes
    $('#asprsh_custom_shipping_enabled').change(toggleShippingFields);
    $('#asprsh_shipping_type').change(toggleShippingFields);
});