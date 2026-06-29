<?php
namespace WPFCF;

trait WPFCF_Has_PostTypes {

  function create_post_type( String $post_type_slug, $post_types_args ) {

    add_action('init', function() use ( $post_type_slug, $post_types_args ) {
      register_post_type( $post_type_slug, $post_types_args );
    });
  } 
}