<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
Plugin Name: EPOS Gateway for WooCommerce
Plugin URI: https://github.com/esasby/cmsgate-woocommerce-epos
Description: Модуль для выставления счетов в систему ЕРИП через сервис EPOS
Version: 2.0.5
Author: ESAS
Author Email: n.mekh@alcosi.eu
Text Domain: epos-gateway-for-woocommerce
WC requires at least: 3.0.0
WC tested up to: 8.8.2
*/

// Include our Gateway Class and register Payment Gateway with WooCommerce
add_action('plugins_loaded', 'wc_cmsgate_epos_init', 0);
function wc_cmsgate_epos_init()
{
    // If the parent WC_Payment_Gateway class doesn't exist
    // it means WooCommerce is not installed on the site
    // so do nothing
    if (!class_exists('WC_Payment_Gateway')) return;
    // If we made it this far, then include our Gateway Class
    include_once('WcCmsgateEpos.php');
    // Now that we have successfully included our class,
    // Lets add it too WooCommerce
    add_filter('woocommerce_payment_gateways', 'wc_cmsgate_epos_add_payment_gateway');

    function wc_cmsgate_epos_add_payment_gateway($methods)
    {
        $methods[] = 'WcCmsgateEpos';
        return $methods;
    }
}

// Add custom action links
require_once dirname(__FILE__) . '/vendor/esas/cmsgate-woocommerce-lib/src/esas/cmsgate/woocommerce/cmsgate-woocommerce-hooks.php';
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cmsgate_settings_link');