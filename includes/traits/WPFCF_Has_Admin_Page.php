<?php
namespace WPFCF;

trait WPFCF_Has_Admin_Page {

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