<?php
/**
 * Plugin Name: PDF Builder for WPForms
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: The first and only PDF drag and drop builder for WPForms
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 1.2.7
 * Text Domain: RedNao PDF For WPForm
 * Domain Path: /languages/
 * Network: true
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: pdf-for-wpforms
 */


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Loader\WPFormSubLoader;

require_once plugin_dir_path(__FILE__).'autoload.php';
new WPFormSubLoader();


