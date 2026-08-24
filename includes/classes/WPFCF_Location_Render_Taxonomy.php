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

    if (!empty( $field_groups_ids )) {

      foreach ( $field_groups_ids as $field_group_id ) {
  
        $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );
  
        add_action( "{$this->taxonomy}_add_form_fields", function( $term ) use ( $fields_config, $field_group_id ) {
          echo '<input type="hidden" name="wpfcf_rendered_fields[]" value="'. $field_group_id .'">';
          echo $this->render_fields_html( $fields_config, 'add', $term->term_id );
        });
  
        add_action( "{$this->taxonomy}_edit_form_fields", function( $term ) use ( $fields_config, $field_group_id ) {
          echo '<input type="hidden" name="wpfcf_rendered_fields[]" value="'. $field_group_id .'">';
          echo $this->render_fields_html( $fields_config, 'edit', $term->term_id );
        });
      }
    }
  }


  /**
   * Renders the html markup of the fields
   */
  function render_fields_html( $fields_config, $screen_type, $term_id = 0 ) {

    $rendered_fields = '';

    foreach ($fields_config as $key => $config) {
      
      $rendered_field = '';

      $type = $config->type;
      $label = $config->label;
      $name = $config->name;
      $default_value = $config->default;
      $id  = $config->id;

      $term_meta = get_term_meta( $term_id, $name, true );
      $value = $term_meta ? $term_meta : $default_value;

      if ($screen_type == 'edit') {

        $rendered_fields .= '<tr class="form-field">';
          $rendered_fields .= '<th scope="row"><label>'. $label .'</label></th>';
          $rendered_fields .= '<td>';
  
            if ($type == 'textarea') {
              $rendered_field .= '<textarea id="field_'. $id .'">'. $value .'</textarea>';
            } else {
              $rendered_field .= '<input type="'. $type .'" name="'. $name .'" id="field_'. $id .'" value="'. $value .'" />';
            }
  
            $rendered_fields .= '<div id="wpfcf_field_wrap_'. $id .'" class="wpfcf_field_wrap">'.
              $rendered_field
            .'</div>';
          $rendered_fields .= '</td>';
        $rendered_fields .= '</tr>';

      } else {

        $rendered_fields .= '<div class="form-field">';
          if ($type == 'textarea') {
            $rendered_field .= '<textarea id="field_'. $id .'">'. $value .'</textarea>';
          } else {
            $rendered_field .= '<input type="'. $type .'" name="'. $name .'" id="field_'. $id .'" value="'. $value .'" />';
          }

          $rendered_fields .= '<div id="wpfcf_field_wrap_'. $id .'" class="wpfcf_field_wrap">'.
            '<label>'. $label .'</label>'.
            $rendered_field
          .'</div>';
        $rendered_fields .= '</div>';
      }
    }

    return $rendered_fields;
  }
}