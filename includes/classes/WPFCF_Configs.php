<?php
namespace WPFCF;

class WPFCF_Configs {

  function __construct() {

    // echo '<pre>';
    // echo var_dump( $this->get_fields_config(667) );
    // echo '</pre>';

    // echo '<br>';

    // echo '<pre>';
    // echo var_dump( $this->get_fields_settings_config() );
    // echo '</pre>';

    // echo '<br>';

    // echo '<pre>';
    // echo var_dump( $this->get_filtered_fields_settings_config('menu-items', 'all') );
    // echo '</pre>';

    // exit;
  }

  /**
   * Get and return the saved group fields config
   */
  function get_fields_config( $group_fields_id ) {
    return json_decode(get_post_meta( $group_fields_id, 'wpfcf_fields_config', true ));
  }

  /**
   * Get and return all group fields settings config
   */
  function get_fields_settings_config() {

    $locations = [];

    $field_group_ids = get_posts([
      'post_type'      => 'wpfcf_field_groups',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'fields'         => 'ids'
    ]);

    foreach ($field_group_ids as $key => $field_group_id) {
      $locations[$field_group_id] = json_decode(get_post_meta( $field_group_id, 'wpfcf_fields_settings_config', true ), true);
    }

    return $locations;
  }

  /**
   * Get and return the group field post ids according to the
   * location and selected screen
   */
  function get_filtered_fields_settings_config( $location, $selected_screen ) {
    
    $filtered_fields_settings = [];
    $fields_settings_config = $this->get_fields_settings_config();

    foreach ($fields_settings_config as $key => $fields_settings) {
      foreach ($fields_settings as $key => $settings) {

        if ($settings['location'] == $location and 
            $settings['selected_screen'] == $selected_screen) {
          $filtered_fields_settings[] = $settings['post_id'];
        }
      }
    }
    
    return array_values(array_unique($filtered_fields_settings));
  }
}