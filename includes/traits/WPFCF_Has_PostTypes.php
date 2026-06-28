<?php
namespace WPFCF;

trait WPFCF_Has_PostTypes {

  /**
   * List of arguments for the post type to be registered
   * when create_post_type function is invoked. 
   * 
   * This is where you put the arguments of the post type(s) 
   * you want to register.
   */
  public $post_types_collection = [
    'wpfcf_field_groups' => [
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
    ],
  ];

  /**
   * Register the post type
   */
  function create_post_type( String $post_type_slug ) {

    add_action('init', function() use ( $post_type_slug ) {

      register_post_type( 
        $post_type_slug, 
        $this->post_types_collection[ $post_type_slug ] 
      );
    });
  } 

}