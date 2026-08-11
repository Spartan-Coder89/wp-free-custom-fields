<?php
namespace WPFCF;

use WP_REST_Request; // ✅ imports the global class into this namespace
use WP_Error; // ✅ imports the global class into this namespace

class WPFCF_Rest {

  function __construct() {

    add_action( 'rest_api_init', function() {

      register_rest_route( 'wpfcf/v1', '/get-location-screens/', [
        'methods'             => 'GET',
        'callback'            => [$this, 'get_location_screens_data'],
        'permission_callback' => function( WP_REST_Request $request ) {
          
          $rest_nonce = $request->get_header( 'X-WP-Nonce' );

          if (is_null( $rest_nonce ) or empty( $rest_nonce )) {
            return new WP_Error( 'rest_forbidden', 'Missing nonce.', [ 'status' => 403 ] );
          }

          return is_user_logged_in() and current_user_can( 'read' );
        }
      ]);
    });

    add_action( 'rest_api_init', function() {

      register_rest_route( 'wpfcf/v1', '/config/', [
        'methods'             => 'GET',
        'callback'            => [$this, 'get_config_data'],
        'permission_callback' => function( WP_REST_Request $request ) {
          
          $rest_nonce = $request->get_header( 'X-WP-Nonce' );

          if (is_null( $rest_nonce ) or empty( $rest_nonce )) {
            return new WP_Error( 'rest_forbidden', 'Missing nonce.', [ 'status' => 403 ] );
          }

          return is_user_logged_in() and current_user_can( 'read' );
        }
      ]);
    });
  }

  function get_location_screens_data( WP_REST_Request $request ) {
    
    $return_value = [];  
    global $wpdb;  

    switch( $request['screen'] ) {

      case 'post-type':
      case 'comments':

        $post_types = get_post_types([ 'show_ui' => true ], 'objects');
        foreach( $post_types as $key => $post_type ) {

          $return_value[] = [
            'name' => $post_type->name,
            'label' => $post_type->label
          ];
        }

        if ($request['screen'] == 'comments') {
          array_unshift($return_value, [
            'name' => 'all',
            'label' => 'All'
          ]);
        }

        break;

      case 'page':

        $pages = $wpdb->get_results(
          "SELECT ID, post_title FROM {$wpdb->posts} 
          WHERE post_type = 'page' AND post_status = 'publish'
          ORDER BY post_title ASC"
        );

        foreach( $pages as $key => $page ) {
          $return_value[] = [
            'name' => $page->ID,
            'label' => $page->post_title
          ];
        }

        break;

      case 'taxonomy':

        $taxonomies = get_taxonomies([ 'show_ui' => true ], 'objects');
        foreach( $taxonomies as $key => $taxonomy ) {

          $return_value[] = [
            'name' => $taxonomy->name,
            'label' => $taxonomy->label
          ];
        }
        break;

      case 'user-role':

        $return_value = [
          [
            'name' => 'all',
            'label' => 'All'
          ],
          [
            'name' => 'administrator',
            'label' => 'Administrator'
          ],
          [
            'name' => 'editor',
            'label' => 'Editor'
          ],
          [
            'name' => 'author',
            'label' => 'Author'
          ],
          [
            'name' => 'contributor',
            'label' => 'Contributor'
          ],
          [
            'name' => 'subscriber',
            'label' => 'Subscriber'
          ]
        ];
        break;

      case 'user-form':

        $return_value = [
          [
            'name' => 'all',
            'label' => 'All'
          ],
          [
            'name' => 'add',
            'label' => 'Add'
          ],
          [
            'name' => 'add-edit',
            'label' => 'Add/Edit'
          ],
          [
            'name' => 'register',
            'label' => 'Register'
          ]
        ];
        break;
        
      case 'menu':
      case 'menu-items':

        $menus = wp_get_nav_menus();
        foreach( $menus as $key => $menu ) {
          $return_value[] = [
            'name' => $menu->slug,
            'label' => $menu->name
          ];
        }

        array_unshift($return_value, [
          'name' => 'all',
          'label' => 'All'
        ]);
        break;

      case 'attachment':

        $return_value = [
          [
            'name' => 'all',
            'label' => 'All'
          ],
          [
            'name' => 'image',
            'label' => 'Image'
          ],
          [
            'name' => 'video',
            'label' => 'Video'
          ],
          [
            'name' => 'audio',
            'label' => 'Audio'
          ],
          [
            'name' => 'text',
            'label' => 'Text'
          ]
        ];
        break;
      
      default:
        break;
    }

    return rest_ensure_response($return_value);
  }


  function get_config_data( WP_REST_Request $request ) {
    
    $return_value = '';

    if ($request['type'] == 'fields_config') {
      $return_value = get_post_meta( $request['post_id'], 'wpfcf_fields_config', true );
    } else {
      $return_value = get_post_meta( $request['post_id'], 'wpfcf_fields_settings_config', true );
    }

    return rest_ensure_response($return_value);
  }
}