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

  function save_metabox( String $hook, Array $nonce, String $data ) {

    add_action( 'save_post_'. $hook, function( $post_id ) use ( $nonce, $data ) {

      if ( !wp_verify_nonce($nonce['value'], $nonce['action']) ) return;
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( !current_user_can('edit_post', $post_id) ) return;

      
    });
  }
}