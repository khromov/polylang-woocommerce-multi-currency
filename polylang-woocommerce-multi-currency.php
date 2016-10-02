<?php
/**
 * Plugin Name: Polylang for WooCommerce Multi-currency support
 * Description: Add multi-currency support when using Polylang for WooCommerce
 * Version:     1.1
 * Author:      khromov
 * Text Domain:
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* Edit form */
add_action('pll_language_edit_form_fields', 'pll_mc_add_form');
add_action('pll_language_add_form_fields', 'pll_mc_add_form');
add_action('admin_init', 'pll_mc_save_form', 999);

/* Set currency */
add_filter('woocommerce_currency', 'pll_mc_woocommerce_currency', 999);

/* Don't sync price / stock-related information in Polylang for WC */
add_filter('pllwc_copy_post_metas', 'pll_mc_remove_metadata_sync', 10, 5);

/**
 * Dynamically filter currency based on language
 *
 * @param string $currency
 * @return string
 */
function pll_mc_woocommerce_currency($currency) {
    $lang = function_exists('pll_current_language') ? pll_current_language() : false;
    return $lang ? get_option("pll_mc_{$lang}_currency", $currency) : $currency;
}

/**
 * Edit form
 *
 * @param PLL_Language $lang
 */
function pll_mc_add_form($lang = null) {
    //TODO: Use a dropdown with get_woocommerce_currencies() instead.
    ?>
    <div class="form-field">
        <label for="pll_mc_wc_currency"><?php esc_html_e( 'WooCommerce Currency', 'polylang' );?></label><?php
        printf(
            '<input name="pll_mc_wc_currency" id="pll_mc_wc_currency" type="text" value="%s" />',
            ! empty( $lang->slug ) ? esc_attr( get_option("pll_mc_{$lang->slug}_currency", '') ) : ''
        );?>
        <p><?php esc_html_e( 'Set the WooCommerce currency for the language', 'polylang' );?></p>
    </div>
    <?php
}

/**
 * Saves data from form
 */
function pll_mc_save_form() {

    //Check if action set
    $action = isset( $_POST['pll_action'] ) ? $_POST['pll_action'] : '';

    //FIXME: How to actually verify nonce is valid?
    if ( 'update' === $action && ! empty( $_POST['slug'] ) ) {
        $lang = $_POST['slug'];
        $currency = isset($_POST['pll_mc_wc_currency']) ? $_POST['pll_mc_wc_currency'] : '';

        $lang_slugs = array();
        foreach(PLL()->model->get_languages_list() as $single_lang) {
            $lang_slugs[] = $single_lang->slug;
        }

        //Language valid
        if (in_array($lang, $lang_slugs)) {
            update_option("pll_mc_{$lang}_currency", $currency);
        }
    }
}

function pll_mc_remove_metadata_sync($to_copy, $sync, $from, $to, $lang) {
    $remove_fields = array(
        '_featured',
        '_manage_stock',
        '_max_price_variation_id',
        '_max_regular_price_variation_id',
        '_max_sale_price_variation_id',
        '_max_variation_price',
        '_max_variation_regular_price',
        '_max_variation_sale_price',
        '_min_price_variation_id',
        '_min_regular_price_variation_id',
        '_min_sale_price_variation_id',
        '_min_variation_price',
        '_min_variation_regular_price',
        '_min_variation_sale_price',
        '_regular_price',
        '_sale_price',
        '_sale_price_dates_from',
        '_sale_price_dates_to',
        '_sold_individually',
        '_tax_class',
        '_tax_status',
        '_price',
        '_stock',
        '_stock_status',
        '_tax_class',
        '_tax_status',
        '_visibility',
        'total_sales',
    );

    foreach($remove_fields as $key_num => $key_to_remove) {
        if(($key = array_search($key_to_remove, $to_copy)) !== false) {
            unset($to_copy[$key]);
        }
    }

    return $to_copy;
}
