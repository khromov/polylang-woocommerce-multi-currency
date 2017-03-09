#### Polylang for WooCommerce Multi-currency support

This simple plugin adds support for setting a WooCommerce currency for each Polylang 
language you are using.

Requires Polylang, WooCommerce and [Polylang for WooCommerce](https://polylang.pro/polylang-for-woocommerce/) **or** [Hyyan WooCommerce Polylang Integration](https://wordpress.org/plugins/woo-poly-integration/).

This plugin also disables syncing of certain product settings that Polylang for WooCommerce performs (like price and stock management) 
so that you can set individual pricing, tax class and stock on a per-language basis.

###### Screenshots

![Screenshot](https://khromov.se/wp-content/uploads/2016/09/pl-wc.png)

###### Known issues


* Setting the currency does not work when initially adding a language. Please add 
the language first, and then edit it to set the currency.
* This plugin does not handle shipping methods optimally. The workaround is to use a specific shipping method per country and price that in the local currency, or (preferably) to use the [WooCommerce Per Product Shipping plugin](https://woocommerce.com/products/per-product-shipping/) so that you can price shipping on a per-product level (which works nicely with Polylang translations.)
