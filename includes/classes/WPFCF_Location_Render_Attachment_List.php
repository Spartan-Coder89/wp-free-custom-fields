<?php
namespace WPFCF;


class WPFCF_Location_Render_Attachment_List {

  private $wpfcf_configs;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct() {

    $this->wpfcf_configs = new WPFCF_Configs;

    add_filter('attachment_fields_to_edit', function( $form_fields, $post ) {

      $context = $this->get_screen_context();

      if ( $context && $context['is_attachment_edit'] ) {
        return $form_fields; // skip if attachment classic edit screen
      }

      $mime_type = $this->get_attachment_category( $post->post_mime_type );
      $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'attachment', $mime_type );
      $field_groups_ids_all = $this->wpfcf_configs->get_filtered_fields_settings_config( 'attachment', 'all' );

      if (!empty( $field_groups_ids_all )) {

        foreach ( $field_groups_ids_all as $field_group_id ) {
          
          $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );

          foreach ( $fields_config as $field ) {
            $value = get_post_meta( $post->ID, $field->name, true );
  
            $form_fields[ 'wpfcf_'. $field->name ] = [ // VERY IMPORTANT NOTE: Always prefix the key to avoid collision with defaults
              'label' => $field->label,
              'input' => 'html',
              'html'  => $this->render_field_html( $field, $value, $post->ID )
            ];
          }
          
        }
      }

      if (!empty( $field_groups_ids )) {

        foreach ( $field_groups_ids as $field_group_id ) {
          
          $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );
  
          foreach ( $fields_config as $field ) {
            $value = get_post_meta( $post->ID, $field->name, true );
  
            $form_fields[ 'wpfcf_'. $field->name ] = [ // VERY IMPORTANT NOTE: Always prefix the key to avoid collision with defaults
              'label' => $field->label,
              'input' => 'html',
              'html'  => $this->render_field_html( $field, $value, $post->ID )
            ];
          }
        }
      }

      return $form_fields;

    }, 10, 2);
  }


  /**
   * Build the html of the field
   */
  function render_field_html( $config, $value = null, $post_id = null ) {

    $rendered_field = '';
    $type = $config->type;
    $name = 'attachments[' . $post_id . '][' . $config->name . ']';
    $id = $config->id;
    $display_value = $value ?? $config->default;

    if ($type == 'text' or $type == 'number' or
        $type == 'range' or $type == 'email' or
        $type == 'url' or $type == 'password' ) {
      $rendered_field = '<input type="'. esc_attr($type) .'" name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'" value="'. esc_attr($display_value) .'" />';

    } else if ($type == 'textarea') {
      $rendered_field = '<textarea name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'">'. esc_textarea($display_value) .'</textarea>';
    }

    return $rendered_field;
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