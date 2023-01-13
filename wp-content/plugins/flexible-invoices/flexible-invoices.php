<?php
/**
 * Plugin Name: Flexible Invoices for WordPress
 * Plugin URI: https://wordpress.org/plugins/flexible-invoices/
 * Description: Invoicing for WordPress made simple. Available <a href="https://www.wpdesk.net/products/flexible-invoices-woocommerce/" target="_blank">extension for WooCommerce</a>.
 * Version: 4.4.6
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: flexible-invoices
 * Domain Path: /lang/
 * Requires at least: 4.5
 * Tested up to: 5.4.1
 * WC requires at least: 3.8
 * WC tested up to: 4.2
 * Requires PHP: 5.6
 *
 * Copyright 2017 WP Desk Ltd.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Flexible Invoices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version           = '4.4.6';
$plugin_release_timestamp = '2020-05-26 22:05';

$plugin_name        = 'Flexible Invoices for WordPress';
$plugin_class_name  = 'Flexible_Invoices_Plugin';
$plugin_text_domain = 'flexible-invoices';
$product_id         = 'Flexible Invoices for WordPress';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

$requirements = [
	'php'     => '5.6',
	'wp'      => '4.5',
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/plugin-init-php52-free.php';

require __DIR__ . '/class/invoicePost.php';
require __DIR__ . '/class/core-functions.php';
