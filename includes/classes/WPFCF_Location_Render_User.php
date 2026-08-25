<?php
namespace WPFCF;


class WPFCF_Location_Render_User {

  private $wpfcf_configs;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct() {

    $this->wpfcf_configs = new WPFCF_Configs;

    // Add User Form
    add_action('user_new_form', function() {
      
      $expected_values = ['all', 'add', 'add-edit'];
      foreach ($expected_values as $key => $value) {

        $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'user-form', $value );
        $this->render_fields_html_add_user_screen( $field_groups_ids, 0 );
        
        if (!empty( $field_groups_ids )) break;
      }
    });


    //  User Edit Form
    add_action('edit_user_profile', function( $user ) {

      //  Check for user role all value first
      $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'user-role', 'all' );
      if (!empty( $field_groups_ids )) {
        $this->render_fields_html_add_user_screen( $field_groups_ids, $user->ID );
      }

      $expected_values = ['all', 'add-edit'];
      foreach ($expected_values as $key => $value) {

        $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'user-form', $value );
        $this->render_fields_html_add_user_screen( $field_groups_ids, $user->ID );
        
        if (!empty( $field_groups_ids )) break;
      }
    });


    //  User Profile 
    add_action('show_user_profile', function( $user ) {

      //  Check for user form all value first
      $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'user-form', 'all' );
      
      if (!empty( $field_groups_ids )) {
        $this->render_fields_html_add_user_screen( $field_groups_ids, $user->ID );
        return;
      } 

      //  Proceed to role checking if user-form all is empty
      $expected_values = [
        'all',
        'administrator',
        'editor',
        'author',
        'contributor',
        'subscriber'
      ];

      foreach ($expected_values as $key => $value) {

        $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'user-role', $value );

        if ($value == 'all' and !empty( $field_groups_ids )) {
          $this->render_fields_html_add_user_screen( $field_groups_ids, $user->ID );
        }

        if (current_user_can( $value ) and !empty( $field_groups_ids )) {
          $this->render_fields_html_add_user_screen( $field_groups_ids, $user->ID );
        }
      }
    });
  }


  /**
   * Renders the html for the user
   */
  function render_fields_html_add_user_screen( $field_groups_ids, $user_id ) {

    echo '<table class="form-table">';

    foreach ($field_groups_ids as $key => $field_groups_id) {

      echo '<input type="hidden" name="wpfcf_rendered_fields[]" value="'. $field_groups_id .'">';

      $fields_config = $this->wpfcf_configs->get_fields_config( $field_groups_id );
      foreach ($fields_config as $key => $config) {

        echo '<tr>'.
          '<th><label for="wpfcf_field_example">'. $config->label .'</label></th>'.
          '<td>'. $this->render_field_html( $config, $user_id ) .'</td>'.
        '</tr>';
      }
    }

    echo '</table>';
  }


  /**
   * Build the html of the field
   */
  function render_field_html( $config, $user_id ) {

    $rendered_field = '';
    $type = $config->type;
    $name = $config->name;
    $id = $config->id;
    $default_value = $config->default;

    $user_meta_value = get_user_meta( $user_id, $name, true );
    $value = $user_meta_value ? $user_meta_value : $default_value;

    if ($type == 'text' or $type == 'number' or
        $type == 'range' or $type == 'email' or
        $type == 'url' or $type == 'password' ) {
      $rendered_field = '<input type="'. esc_attr($type) .'" name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'" value="'. esc_attr($value) .'" />';

    } else if ($type == 'textarea') {
      $rendered_field = '<textarea name="'. esc_attr($name) .'" id="field_'. esc_attr($id) .'">'. esc_textarea($value) .'</textarea>';

    } else {
      //  Others
    }

    return $rendered_field;
  }
}