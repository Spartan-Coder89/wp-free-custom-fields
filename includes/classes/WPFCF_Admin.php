<?php
namespace WPFCF;


class WPFCF_Admin {

  use WPFCF_Has_PostTypes;
  use WPFCF_Has_Admin_Page;
  use WPFCF_Has_Metaboxes;
  use WPFCF_Has_Assets;
  
  function __construct() {

    //  Implemented from WPFCF_Has_Admin_Page trait
    $this->create_admin_page( [
      'page_title'          => 'WP Free Custom Fields',
      'menu_title'          => 'WPFCF',
      'capability_required' => 'manage_options',
      'menu_slug'           => 'wpfcf_admin_page',
      'callback_render'     => null,
      'icon'                => 'dashicons-welcome-widgets-menus',
      'position_in_menu'    => null
    ]);

    //  Implemented from WPFCF_Has_PostTypes trait
    $this->create_post_type( 'wpfcf_field_groups', [
      'labels'              => [
        'name'               => 'Field Groups',
        'singular_name'      => 'Field Group',
        'menu_name'          => 'Field Groups',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Field Group',
        'edit_item'          => 'Edit Field Group',
        'new_item'           => 'New Field Group',
        'view_item'          => 'View Field Group',
        'search_items'       => 'Search my field groups',
        'not_found'          => 'No my field groups found',
        'not_found_in_trash' => 'No field groups found in Trash',
        'all_items'          => 'My field groups',
      ],
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => 'wpfcf_admin_page',
      'show_in_admin_bar'   => false,
      'show_in_nav_menus'   => false,
      'show_in_rest'        => false,
      'query_var'           => false,
      'rewrite'             => false,
      'capability_type'     => 'post',
      'hierarchical'        => false,
      'supports'            => ['title'],
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false
    ]);

    //  Implemented from WPFCF_Has_Metaboxes trait
    $this->create_metaboxes( [
      [
        'html_id'         => 'wpfcf_field_group',
        'title'           => 'Fields',
        'callback_render' => function() { echo 'Hellow'; },
        'screen'          => 'wpfcf_field_groups',
        'context'         => 'normal',
        'priority'        => 'high'
      ],
      [
        'html_id'         => 'wpfcf_field_group_settings',
        'title'           => 'Settings',
        'callback_render' => function() { echo 'Hellow'; },
        'screen'          => 'wpfcf_field_groups',
        'context'         => 'normal',
        'priority'        => 'default'
      ],
    ]);

    //  Implemented from WPFCF_Has_Assets trait
    $this->enqueue_cpt_admin_scripts( [
      'post_type'       => 'wpfcf_field_groups',
      'allowed_screens' => ['post.php', 'post-new.php'],
      'enqueue_style'   => [
        'handle'      => 'admin',
        'source'      => WPFCF_URL .'/assets/css/admin.css',
        'dependency'  => [],
        'version'     => WPFCF_VERSION,
      ],
      'enqueue_script'  => [
        'handle'      => 'alpinejs',
        'source'      => WPFCF_URL .'/assets/js/alpinejs@3.15.12.js',
        'dependency'  => [],
        'version'     => WPFCF_VERSION,
        'in_footer'   => [
          'strategy'  => 'defer',
          'in_footer' => false,
        ]
      ]
    ]);
  }
}