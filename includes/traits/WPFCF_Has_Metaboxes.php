<?php
namespace WPFCF;

trait WPFCF_Has_Metaboxes {

  function create_metaboxes( $screen ) {

    add_action( 'add_meta_boxes', function() use ( $screen ) {

      /**
       * List of arguments for the metaboxes to be registered
       * when create_metaboxes function is invoked. 
       * 
       * This is where you put the arguments of the metaboxes(s) 
       * you want to register.
       */
      $metaboxes_collection = [
        'wpfcf_field_groups' => [
          [
            'html_id'         => 'wpfcf_field_group',
            'title'           => 'Fields',
            'callback_render' => function() { echo 'Hellow'; },
            'screen'          => $screen,
            'context'         => 'normal',
            'priority'        => 'high'
          ],
          [
            'html_id'         => 'wpfcf_field_group_settings',
            'title'           => 'Settings',
            'callback_render' => function() { echo 'Hellow'; },
            'screen'          => $screen,
            'context'         => 'normal',
            'priority'        => 'default'
          ],
        ],
      ];

      //  Register metaboxes
      foreach ($metaboxes_collection[$screen] as $key => $args) {

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

  function save_metabox() {

  }
}