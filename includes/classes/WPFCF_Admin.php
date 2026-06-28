<?php
namespace WPFCF;

class WPFCF_Admin {

  public function __construct() {
    add_action('admin_menu', [$this, 'register_wpfcf_admin_page']);
  }

  //  Create custom admin page
  public function register_wpfcf_admin_page() {

    add_menu_page(
      __( 'WP Free Custom Fields', 'wp-free-custom-fields' ), // Page title
      __( 'WP Free Custom Fields', 'wp-free-custom-fields' ),     // Menu title
      'manage_options',                              // Capability required
      'wp-free-custom-fields',                              // Menu slug (used as the parent slug below)
      [$this, 'render_wpfcf_admin_page'],                  // Callback to render the page
      'dashicons-list-view'                         // Icon
    );
  }

  //  Render the wpfcf admin page
  public function render_wpfcf_admin_page() {
    echo 'Hello';
  }

  //  Create custom post type named wp-free-custom-fields  
  //  Create metaboxes for the custom post type
  //  Hook to save 
}