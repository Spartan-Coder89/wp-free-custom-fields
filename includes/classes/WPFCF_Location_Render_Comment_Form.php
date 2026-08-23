<?php
namespace WPFCF;


class WPFCF_Location_Render_Comment_Form {

  private $wpfcf_configs;
  private $comment;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct() {

    $comment_id = isset($_GET['c']) ? $_GET['c'] :  0;

    $this->wpfcf_configs = new WPFCF_Configs;
    $this->comment = get_comment( $comment_id );

    add_action('comment_form_after_fields', function( $comment ) {
      $this->render_comment_form_fields();
    });

    add_action('comment_form_logged_in_after', function( $comment ) {
      $this->render_comment_form_fields();
    });    
  }


  /**
   * Render the fields associated with the
   * current comment form on the frontend
   */
  function render_comment_form_fields() {

    global $post;

    $field_groups_ids_all = $this->wpfcf_configs->get_filtered_fields_settings_config( 'comments', 'all' );

    if (!empty( $field_groups_ids_all )) {

      foreach ( $field_groups_ids_all as $field_group_id ) {
        
        $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );

        foreach ( $fields_config as $field ) {
          echo '<p class="comment-form-'. esc_attr( $field->name ) .'">';
            echo '<label for="wpfcf_'. esc_attr( $field->name ) .'">'. esc_html( $field->label ) .'</label>';
            echo $this->render_field_html( $field );
          echo '</p>';
        }
      } 
    }

    $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'comments', $post->post_type );

    if (!empty( $field_groups_ids )) {

      foreach ( $field_groups_ids as $field_group_id ) {
        
        $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );

        foreach ( $fields_config as $field ) {
          echo '<p class="comment-form-'. esc_attr( $field->name ) .'">';
            echo '<label for="wpfcf_'. esc_attr( $field->name ) .'">'. esc_html( $field->label ) .'</label>';
            echo $this->render_field_html( $field );
          echo '</p>';
        }
      } 
    }

    wp_nonce_field( 'wpfcf_save_comment_frontend', 'wpfcf_comment_frontend_nonce' );
  }


  /**
   * Build the html of the field
   */
  function render_field_html( $config, $value = null ) {

    $rendered_field = '';
    $type = $config->type;
    $name = $config->name;
    $id = $config->id;
    $display_value = $value ?? $config->default; // saved value wins, fallback to default

    if ($type == 'text' or $type == 'number' or
        $type == 'range' or $type == 'email' or
        $type == 'url' or $type == 'password' ) {
      $rendered_field = '<input type="'. esc_attr($type) .'" name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'" value="'. esc_attr($display_value) .'" />';

    } else if ($type == 'textarea') {
      $rendered_field = '<textarea name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'">'. esc_textarea($display_value) .'</textarea>';

    } else {
      //  Others
    }

    return $rendered_field;
  }
}