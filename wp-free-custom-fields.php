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


define( 'WPFCF_PATH', plugin_dir_path(__FILE__) );
define( 'WPFCF_URL', plugins_url( '', __FILE__ ) );

spl_autoload_register( function ( $class ) {

  if( strpos( $class, 'WPFCF\\' ) !== 0 ) {
    return;
  }

  $relative_class = substr( $class, strlen('WPFCF\\') );
  $file = plugin_dir_path(__FILE__) .'includes/classes/' . $relative_class . '.php';

  if (file_exists($file)) {
    require_once $file;
  }
});


add_action( 'plugins_loaded', function() {
  new WPFCF\WPFCF_Admin;
});