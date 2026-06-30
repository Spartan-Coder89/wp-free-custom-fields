<?php
namespace WPFCF;

trait WPFCF_Has_Admin_Page {

  /**
   * Creates a custom wp-admin page
   * 
   * Format of accepted parameter:
   * [
   *  'page_title'          => 'The title of the page',
   *  'menu_title'          => 'The text that will appear in the menu',
   *  'capability_required' => 'manage_options' ..the user capability required to see the page,
   *  'menu_slug'           => 'define the page slug here',
   *  'callback_render'     => function() {} ..this where the template of the page goes,
   *  'icon'                => 'dashicons-welcome-widgets-menus' or the url of your custom icon,
   *  'position_in_menu'    => null
   * ]
   */
  function create_admin_page( Array $admin_page_args ) {
    
    add_action( 'admin_init', function() use ( $admin_page_args ) {

      add_menu_page(
        $admin_page_args['page_title'],
        $admin_page_args['menu_title'],
        $admin_page_args['capability_required'],
        $admin_page_args['menu_slug'],                             
        $admin_page_args['callback_render'],
        $admin_page_args['icon'],
        $admin_page_args['position_in_menu']
      );
    });
  }
}