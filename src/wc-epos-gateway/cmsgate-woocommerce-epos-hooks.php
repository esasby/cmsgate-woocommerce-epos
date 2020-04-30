<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
Plugin Name: WC EPOS Gateway
Plugin URI: https://bitbucket.esas.by/projects/CG/repos/cmsgate-woocommerce-epos/browse
Description: Модуль для выставления счетов в систему ЕРИП через сервис EPOS
Version: 1.0.0
Author: ESAS
Author Email: n.mekh@hutkigrosh.by
Text Domain: wc-epos-gateway
WC requires at least: 3.0.0
WC tested up to: 4.0.1
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