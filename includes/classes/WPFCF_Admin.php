<?php
namespace WPFCF;


class WPFCF_Admin {

  use WPFCF_Has_PostTypes;
  use WPFCF_Has_Admin_Page;
  use WPFCF_Has_Metaboxes;
  use WPFCF_Has_Assets;
  
  function __construct() {

    global $post;
    $id = $post->ID;

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
        'name'               => 'WPFCF Field Groups',
        'singular_name'      => 'WPFCF Field Group',
        'menu_name'          => 'WPFCF Field Groups',
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
        'callback_render' => function() { 
          require_once WPFCF_PATH .'/includes/templates/fields.php';
         },
        'screen'          => 'wpfcf_field_groups',
        'context'         => 'normal',
        'priority'        => 'high'
      ],
      [
        'html_id'         => 'wpfcf_field_group_settings',
        'title'           => 'Settings',
        'callback_render' => function() { 
          require_once WPFCF_PATH .'/includes/templates/fields_settings.php';
        },
        'screen'          => 'wpfcf_field_groups',
        'context'         => 'normal',
        'priority'        => 'default'
      ],
    ]);

    //  Implemented from WPFCF_Has_Metaboxes trait
    $this->save_metabox( 'wpfcf_field_groups', 'wpfcf_group_fields_nonce', function($post_id, $post) {
      
      $fields_config = $this->sanitize_json_structure_string($_POST['fields_config']);
      $fields_settings_config = $this->sanitize_json_structure_string($_POST['fields_settings_config']);
  
      update_post_meta( $post_id, 'wpfcf_fields_config', $fields_config);
      update_post_meta( $post_id, 'wpfcf_fields_settings_config', $fields_settings_config);
    });

    //  Implemented from WPFCF_Has_Assets trait
    $this->enqueue_cpt_admin_scripts( [
      'post_type'       => 'wpfcf_field_groups',
      'allowed_screens' => ['post.php', 'post-new.php'],
      'assets'          => [
        [
          'type'        => 'style',
          'handle'      => 'wpfcf-admin',
          'source'      => WPFCF_URL .'/assets/css/admin.css',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
        ],
        [
          'type'        => 'script',
          'handle'      => 'wpfcf-alpinejs',
          'source'      => WPFCF_URL .'/assets/js/alpinejs@3.15.12.js',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
          'in_footer'   => [
            'strategy'  => 'defer',
            'in_footer' => false,
          ]
        ],
        [
          'type'        => 'script',
          'handle'      => 'wpfcf-store-globals',
          'source'      => WPFCF_URL .'/assets/js/wpfcf_store_globals.js',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
          'in_footer'   => true
        ],
        [
          'type'        => 'script',
          'handle'      => 'wpfcf-fields',
          'source'      => WPFCF_URL .'/assets/js/wpfcf_fields.js',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
          'in_footer'   => true
        ],
        [
          'type'        => 'script',
          'handle'      => 'wpfcf-add-edit-field-modal',
          'source'      => WPFCF_URL .'/assets/js/wpfcf_add_edit_field_modal.js',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
          'in_footer'   => true
        ],
        [
          'type'        => 'script',
          'handle'      => 'wpfcf-fields-settings',
          'source'      => WPFCF_URL .'/assets/js/wpfcf_fields_settings.js',
          'dependency'  => [],
          'version'     => WPFCF_VERSION,
          'in_footer'   => true
        ]
      ],
      'localize'        => [
        [
          'handle'      => 'wpfcf-store-globals',
          'object_name' => 'wpfcf_store_globals_obj',
          'data'        => [
            // note: VERY IMPORTANT: action must be EXPLICITLY NAMED 'wp_rest' if nonce is gonna be used for REST ENDPOINT access. 
            // If not, you will recieve rest cookie check error. Cost me a lot of hours to resolve this error. :(
            'wp_rest_nonce' => wp_create_nonce( 'wp_rest' ), 
            'site_url'      => get_site_url(),
            'plugin_url'    => plugins_url('wp-free-custom-fields')
          ]
        ]
      ]
    ]);

    //  Insert add/edit field modal in admin page
    add_action( 'admin_footer', function() {

      $current_screen = get_current_screen();
      if ( $current_screen->id !== 'wpfcf_field_groups' ) return;

      require_once WPFCF_PATH .'/includes/templates/add_edit_fields_modal.php';
    });
  }


  /**
   * Helper function to sanitize json
   */
  function sanitize_json_structure_string(string $raw_json): string {

      $data = json_decode(wp_unslash($raw_json), true);

      if (json_last_error() !== JSON_ERROR_NONE) return '[]'; // invalid JSON in, safe empty structure out

      $sanitize = function ($value) use (&$sanitize) {

        if (is_array($value)) {

          $result = [];
          foreach ($value as $key => $item) {
            $safe_key = is_string($key) ? sanitize_key($key) : $key;
            $result[$safe_key] = $sanitize($item);
          }
          return $result;
        }

        if (is_string($value)) {
          return sanitize_text_field($value);
        }

        // numbers, booleans, null pass through untouched
        return $value;
      };

      return wp_json_encode($sanitize($data));
  }
}