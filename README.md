# AS Product Shipping

[![Version](https://img.shields.io/badge/version-1.0.1-blue.svg)](https://github.com/AhmedShaikh0/AS-Product-Shipping)  
[![License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg)](https://www.gnu.org/licenses/gpl-3.0.html)  

A **WooCommerce shipping plugin** with flat rate and weight-based shipping options for individual products.

---

## Description

**AS Product Shipping** allows store owners to set custom shipping rates and weights for individual products. It provides both flat rate and weight-based shipping options **without needing complex WooCommerce shipping zones configuration**.

### Features

- **Per-Product Shipping Options** – Set custom shipping options for each product  
- **Flat Rate Shipping** – Charge a fixed rate per product  
- **Weight-Based Shipping** – Calculate shipping based on product weight  
- **Product Weight Management** – Easily assign custom weights  
- **Flexible Configuration** – Works with or without global shipping zones  
- **Order Details** – Displays weight information in order details  
- **No Default Shipping Required** – Overrides WooCommerce's default shipping features  

### How It Works

1. Install and activate the plugin  
2. Go to **WooCommerce > Settings > Shipping**  
3. Add the **"AS Product Shipping"** method to your shipping zones  
4. Configure your global shipping rates (flat rate or weight-based)  
5. Set custom weights and shipping options for products in the product editor using the plugin’s custom shipping section  
6. Customers will see your shipping options at checkout  

> Perfect for store owners who want granular control over shipping costs per product while keeping the overall shipping setup simple.

---

## Installation

1. Upload the plugin files to `/wp-content/plugins/as-product-shipping` or install via the WordPress plugins screen  
2. Activate the plugin through the **Plugins** screen  
3. Go to **WooCommerce > Settings > Shipping** to configure the shipping method  

---

## Frequently Asked Questions

**Q: Do I need to set up shipping zones?**  
A: No, this plugin works without requiring shipping zones.

**Q: How do I set product weights?**  
A: Set custom weights in the product editor under the **Shipping** tab.

**Q: Do I need to use WooCommerce's default shipping settings?**  
A: No, this plugin overrides WooCommerce's default shipping features.

**Q: Can I use both flat rate and weight-based shipping?**  
A: Yes, you can enable both globally, but only one will be used for calculations. Flat rate takes priority if enabled.

**Q: Can I set different shipping options for different products?**  
A: Yes! Enable custom shipping per product and set either flat rate or weight-based shipping with custom rates.

---

## Screenshots

1. Shipping method configuration  
2. Product shipping options  
3. Weight display on product page  
4. Shipping options at checkout  

---

## Changelog

### 1.0.1
- Fixed issues with inline CSS/JS – now properly enqueued using WordPress functions  
- Fixed generic naming prefixes to be more unique  
- Improved code structure and organization  

### 1.0.0
- Initial release  
- Added per-product shipping options  
- Added flat rate and weight-based shipping calculation  
- Improved UI/UX with dedicated admin menu  
- Enhanced shipping calculation logic  
- Fixed security issues with output escaping  
- Updated all `_e()` calls to `esc_html_e()` for better security  
- Improved overall code security and WordPress compliance  

---

## Upgrade Notice

### 1.0.1
Fixed inline CSS/JS issues and generic naming prefixes. Improved code structure.

### 1.0.0
Initial release with per-product shipping options and improved security.

---

## License

This plugin is licensed under **GPLv3 or later**.  
[https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html)
