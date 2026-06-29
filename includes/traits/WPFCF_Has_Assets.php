<?php
namespace WPFCF;

trait WPFCF_Has_Assets {
  
  function enqueue_cpt_admin_scripts( Array $args ) {

    add_action('admin_enqueue_scripts', function( $hook_suffix ) use ( $args ) {

      global $post;

      if ( !in_array($hook_suffix, $args['allowed_screens'], true) ) return;
      if ( !$post or ($args['post_type'] !== $post->post_type) ) return;

      if ( isset($args['enqueue_style']) and !empty($args['enqueue_style']) ) {

        wp_enqueue_style(
          $args['enqueue_style']['handle'],
          $args['enqueue_style']['source'],
          $args['enqueue_style']['dependency'],
          $args['enqueue_style']['version']
        );
      }

      if ( isset($args['enqueue_script']) and !empty($args['enqueue_script']) ) {

        wp_enqueue_script(
          $args['enqueue_script']['handle'],
          $args['enqueue_script']['source'],
          $args['enqueue_script']['dependency'],
          $args['enqueue_script']['version'],
          $args['enqueue_script']['in_footer']
        );
      }
    });
  }

  function enqueue_admin_page_scripts() {

  }
}