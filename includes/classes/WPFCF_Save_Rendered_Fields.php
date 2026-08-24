<?php
namespace WPFCF;

class WPFCF_Save_Rendered_Fields {

  private $wpfcf_configs;

  function __construct() {
    
    $this->wpfcf_configs = new WPFCF_Configs;

    //  Save pages metadata
    add_action('save_post_page', function( $post_id ) {

      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( !current_user_can('edit_post', $post_id) ) return;

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


    //  Save attachment metadata on the edit screen
    add_action('edit_attachment', function( $post_id ) {

      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( !current_user_can('edit_post', $post_id) ) return;

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


    //  Save attachment metadata on the list screen
    add_filter('attachment_fields_to_save', function( $post, $attachment ) {

      $mime_type = $this->get_attachment_category( $post['post_mime_type'] );

      $field_groups_ids = array_values( array_unique( array_merge(
        $this->wpfcf_configs->get_filtered_fields_settings_config( 'attachment', $mime_type ),
        $this->wpfcf_configs->get_filtered_fields_settings_config( 'attachment', 'all' )
      )));

      foreach ( $field_groups_ids as $field_group_id ) {
        $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );

        foreach ( $fields_config as $field ) {
          $key = 'wpfcf_' . $field->name;

          if ( isset( $attachment[ $key ] ) ) {
            $clean_value = $this->sanitize_field_value( $field->type, $attachment[ $key ] );
            update_post_meta( $post['ID'], $field->name, $clean_value );
          }
        }
      }

      return $post;

    }, 10, 2);


    //  Save post metadata
    add_action('save_post', function( $post_id ) {
      
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
      if ( wp_is_post_revision($post_id) ) return;
      if ( !current_user_can('edit_post', $post_id) ) return;

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


  /**
   * Sanitizes raw data for database saving
   */
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


  /**
   * Helper function to identify the 
   * category of an attachment
   */
  function get_attachment_category( $post_mime_type ): string {

    [$type, $subtype] = explode('/', $post_mime_type) + [null, null];

    if ( in_array($type, ['image', 'video', 'audio'], true) ) {
      return $type;
    }

    return 'document'; // everything else (pdf, zip, docx, etc.)
  }
}