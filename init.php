<?php

/*
 * Plugin Name:   Woo-Tip #1 - Custom field in Shpping Class
 * Description:  This plugin will add two custom fields in Shipping Class
 * Version:     0.0.1
 * Author:      KT-12
 * Author URI:  https://kt12.in/
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require plugin_dir_path(__FILE__) . 'class-custom-woo-shipping-class-fields.php';
$CWSC = new Custom_Woo_Shipping_Class_Fields();
$CWSC->run();