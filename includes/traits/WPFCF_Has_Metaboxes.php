<?php
namespace WPFCF;

trait WPFCF_Has_Metaboxes {

  /**
   * Creates metaboxes
   * 
   * Format of accepted parameter:
   * [
   *  [
   *    'html_id'         => 'The html id of the metabox',
   *    'title'           => 'The title of the metabox',
   *    'callback_render' => function() {},
   *    'screen'          => 'The post or admin page slug',
   *    'context'         => 'high' or 'normal' or 'default',
   *    'priority'        => 'high' or 'normal' or 'default'
   *  ],
   *  ...
   * ]
   */
  function create_metaboxes( Array $metaboxes_args ) {

    add_action( 'add_meta_boxes', function() use ( $metaboxes_args ) {

      foreach ($metaboxes_args as $key => $args) {

        add_meta_box(
          $args['html_id'],
          $args['title'],
          $args['callback_render'],
          $args['screen'],
          $args['context'],
          $args['priority'],
        );
      }
    });

  }

  function save_metabox( String $post_type, String $nonce_action, callable $save_callback ) {

    add_action( "save_post_{$post_type}", function( $post_id, $post ) use ( $nonce_action, $save_callback ) {
      
      if ( !isset($_POST[$nonce_action]) ) return;
      if ( !wp_verify_nonce($_POST[$nonce_action], $nonce_action) ) return;
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( !current_user_can('edit_post', $post_id) ) return;

      call_user_func($save_callback, $post_id, $post);
      
    }, 10, 2); // Important note: accepted argument parameter must match the number of arguments passed on call_user_func
  }
}