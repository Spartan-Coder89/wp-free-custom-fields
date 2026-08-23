<?php
namespace WPFCF;

class WPFCF_Save_Rendered_Fields {

  private $wpfcf_configs;

  function __construct() {
    
    add_action('save_post_page', function( $post_id ) {

      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( ! current_user_can('edit_post', $post_id) ) return;

      if (isset( $_POST['wpfcf_rendered_fields'] ) and !empty( $_POST['wpfcf_rendered_fields'] )) {

        $wpfcf_rendered_fields = $_POST['wpfcf_rendered_fields'];
        foreach ($wpfcf_rendered_fields as $key => $field_group_id) {

          $field_group = json_decode( get_post_meta( $field_group_id, 'wpfcf_fields_config', true ) );
          foreach ($field_group as $key => $field) {

            $post_meta_value = $this->sanitize_field_value( $field->type, $_POST[$field->name] );
            update_post_meta( $post_id, $field->name, $post_meta_value );
          }
        }
      }
    });


    add_action('save_post', function( $post_id ) {
      
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( ! current_user_can('edit_post', $post_id) ) return;

      if (isset( $_POST['wpfcf_rendered_fields'] ) and !empty( $_POST['wpfcf_rendered_fields'] )) {

        $wpfcf_rendered_fields = $_POST['wpfcf_rendered_fields'];
        foreach ($wpfcf_rendered_fields as $key => $field_group_id) {

          $field_group = json_decode( get_post_meta( $field_group_id, 'wpfcf_fields_config', true ) );
          foreach ($field_group as $key => $field) {

            $post_meta_value = $this->sanitize_field_value( $field->type, $_POST[$field->name] );
            update_post_meta( $post_id, $field->name, $post_meta_value );
          }
        }
      }
    });

  }

  function sanitize_field_value( $type, $raw_value ) {

    switch( $type ) {

      case 'email':
        return sanitize_email( $raw_value );

      case 'url':
        return esc_url_raw( $raw_value );

      case 'number':
      case 'range':
        return is_numeric( $raw_value ) ? $raw_value + 0 : 0; // preserves int vs float

      case 'textarea':
        return sanitize_textarea_field( $raw_value ); // preserves line breaks, unlike sanitize_text_field

      case 'password':
      case 'text':
      default:
        return sanitize_text_field( $raw_value );
    }
  }
}