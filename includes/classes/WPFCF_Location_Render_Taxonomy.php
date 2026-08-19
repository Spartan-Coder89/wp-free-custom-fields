<?php
namespace WPFCF;


class WPFCF_Location_Render_Taxonomy {

  private $wpfcf_configs;
  private $taxonomy;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct( $taxonomy ) {
    $this->taxonomy = $taxonomy; 
    $this->wpfcf_configs = new WPFCF_Configs;
  }

  function render() {

    $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'taxonomy', $this->taxonomy );

    foreach ( $field_groups_ids as $field_group_id ) {

      $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );
      $rendered_fields_html = $this->render_fields_html( $fields_config );

      add_action( "{$this->taxonomy}_add_form_fields", function() use ( $rendered_fields_html ) {
        echo $rendered_fields_html;
      });

      add_action( "{$this->taxonomy}_edit_form_fields", function() use ( $rendered_fields_html ) {
        echo '<tr class="form-field"><td colspan="2">' . $rendered_fields_html . '</td></tr>';
      });
    }
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
}