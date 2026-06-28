<?php
namespace WPFCF;

trait WPFCF_Has_Admin_Page {

  /**
   * List of arguments for the custom admin page to be registered
   * when create_admin_page function is invoked. 
   * 
   * This is where you put the arguments of the custom admin page(s) 
   * you want to register.
   */
  public $admin_page_collection = [
    'wpfcf_admin_page' => [
      'page_title'          => 'WP Free Custom Fields',
      'menu_title'          => 'WPFCF',
      'capability_required' => 'manage_options',
      'menu_slug'           => 'wpfcf_admin_page',
      'callback_render'     => null,
      'icon'                => 'dashicons-welcome-widgets-menus',
      'position_in_menu'    => null
    ],
  ];

  function create_admin_page( $admin_page_slug ) {
    
    add_action( 'admin_init', function() use ( $admin_page_slug ) {

      add_menu_page(
        $this->admin_page_collection[ $admin_page_slug ]['page_title'],
        $this->admin_page_collection[ $admin_page_slug ]['menu_title'],
        $this->admin_page_collection[ $admin_page_slug ]['capability_required'],
        $admin_page_slug,                             
        $this->admin_page_collection[ $admin_page_slug ]['callback_render'],
        $this->admin_page_collection[ $admin_page_slug ]['icon'],
        $this->admin_page_collection[ $admin_page_slug ]['position_in_menu']
      );
    });
  }
}