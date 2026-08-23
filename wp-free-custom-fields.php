<?php 

/**
 * @package WPFreeAdvancedCustomFields
 */

/** 
 * Plugin Name: WP Free Custom Fields
 * Plugin URI: https://simonjiloma.com/wp-free-advanced-custom-fields
 * Description: Free advanced custom fields for wordpress
 * Version: 0.0.1
 * Author: Simon Jiloma
 * Author URI: https://simonjiloma.com
 * License: GPLv2 or later
 * Text Domain: wp-free-advanced-custom-fields
 */


if (!defined('ABSPATH')) die;

define( 'WPFCF_VERSION', '0.1' );
define( 'WPFCF_PATH', plugin_dir_path(__FILE__) );
define( 'WPFCF_URL', plugins_url( '', __FILE__ ) );

require_once WPFCF_PATH .'wpfcf_autoloader.php';

add_action( 'plugins_loaded', function() {
  new WPFCF\WPFCF_Configs;
  new WPFCF\WPFCF_Rest;
  new WPFCF\WPFCF_Admin;
  new WPFCF\WPFCF_Render_Fields;
  new WPFCF\WPFCF_Save_Rendered_Fields;
});