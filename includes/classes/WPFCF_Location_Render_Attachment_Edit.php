<?php
namespace WPFCF;


class WPFCF_Location_Render_Attachment_Edit {

  private $wpfcf_configs;

  use WPFCF_Has_Metaboxes;

  function __construct() {
    $this->wpfcf_configs = new WPFCF_Configs;
  }

  
  /**
   * Render the fields associated with the
   * current page edit screen
   */
  function render() {

    $expected_values = ['all', 'image', 'video', 'audio', 'text'];

    foreach ($expected_values as $key => $value) {

      $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'attachment', $value );

      if ($value == 'all' and !empty( $field_groups_ids )) {
        $metaboxes_args = $this->setup_metaboxes_args( $field_groups_ids, 'attachment' );
        $this->create_metaboxes( $metaboxes_args );
      }

      if (!empty( $field_groups_ids ) and wp_attachment_is( $value, $_GET['post'] )) {
        $metaboxes_args = $this->setup_metaboxes_args( $field_groups_ids, 'attachment' );
        $this->create_metaboxes( $metaboxes_args );
        break;
      }
    }
  }


  /**
   * Helper function to fill in the 
   * metabox arguments
   */
  function setup_metaboxes_args( $field_groups_ids, $selected_screen ) {

    $metabox_args = [];

    foreach ($field_groups_ids as $key => $field_groups_id) {

      $wpfcf_group_fields = get_post( $field_groups_id );
      $wpfcf_fields_config = $this->wpfcf_configs->get_fields_config( $field_groups_id );
      $rendered_fields_html = $this->render_fields_html( $wpfcf_fields_config );

      $metabox_args[] = [
        'html_id'         => str_replace('-', '_', $wpfcf_group_fields->post_name),
        'title'           => $wpfcf_group_fields->post_title,
        'callback_render' => function() use ( $rendered_fields_html ) { 
          require WPFCF_PATH .'/includes/templates/render_fields.php';
        }, 
        'screen'          => $selected_screen,
        'context'         => 'normal',
        'priority'        => 'high'
      ];
    }

    return $metabox_args;
  }


  /**
   * Renders the html markup of the fields
   */
  function render_fields_html( $fields_config ) {

    $rendered_fields = '';

    foreach ($fields_config as $key => $config) {
      
      $rendered_field = '';

      $type = $config->type;
      $label = $config->label;
      $name = $config->name;
      $default_value = $config->default;
      $id  = $config->id;

      if ($type == 'textarea') {
        $rendered_field .= '<textarea id="field_'. $id .'">'. $default_value .'</textarea>';
      } else {
        $rendered_field .= '<input type="'. $type .'" name="'. $name .'" id="field_'. $id .'" value="'. $default_value .'" />';
      }

      $rendered_fields .= '<div id="wpfcf_field_wrap_'. $id .'" class="wpfcf_field_wrap">'.
        '<label for="field_'. $id .'">'. $label .'</label>'.
        $rendered_field
      .'</div>';
    }

    return $rendered_fields;
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


  /**
   * Helper function to identify the category of an attachment
   */
  function get_attachment_category( $post_mime_type ): string {

    [$type, $subtype] = explode('/', $post_mime_type) + [null, null];

    if ( in_array($type, ['image', 'video', 'audio'], true) ) {
      return $type;
    }

    return 'document'; // everything else (pdf, zip, docx, etc.)
  }
}