<?php
namespace WPFCF;


class WPFCF_Location_Render_Page {

  protected $page_id;
  private $wpfcf_configs;

  use WPFCF_Has_Metaboxes;

  function __construct() {
    $this->page_id = isset($_GET['post']) ? absint($_GET['post']) : 0;
    $this->wpfcf_configs = new WPFCF_Configs;
  }

  
  /**
   * Render the fields associated with the
   * current page edit screen
   */
  function render() {
    $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'page', $this->page_id );
    $metaboxes_args = $this->setup_metaboxes_args( $field_groups_ids, 'page' );

    $this->create_metaboxes( $metaboxes_args );
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

      $metabox_args[] = [
        'html_id'         => str_replace('-', '_', $wpfcf_group_fields->post_name),
        'title'           => $wpfcf_group_fields->post_title,
        'callback_render' => function( $post ) use ( $field_groups_id, $wpfcf_fields_config ) {

          $rendered_fields_html = $this->render_fields_html( $wpfcf_fields_config, $post->ID );
          echo '<input type="hidden" name="wpfcf_rendered_fields[]" value="'. $field_groups_id .'">';
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
  function render_fields_html( $fields_config, $post_id ) {

    $rendered_fields = '';

    foreach ($fields_config as $key => $config) {
      
      $rendered_field = '';

      $type = $config->type;
      $label = $config->label;
      $name = $config->name;
      $default_value = $config->default;
      $id  = $config->id;

      $post_meta = get_post_meta( $post_id, $name, true );
      $value = $post_meta ?? $default_value;

      if ($type == 'textarea') {
        $rendered_field .= '<textarea id="field_'. $id .'">'. $value .'</textarea>';
      } else {
        $rendered_field .= '<input type="'. $type .'" name="'. $name .'" id="field_'. $id .'" value="'. $value .'" />';
      }

      $rendered_fields .= '<div id="wpfcf_field_wrap_'. $id .'" class="wpfcf_field_wrap">'.
        '<label for="field_'. $id .'">'. $label .'</label>'.
        $rendered_field
      .'</div>';
    }

    return $rendered_fields;
  }
}