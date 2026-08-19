<?php
namespace WPFCF;


class WPFCF_Location_Render_Comments {

  private $wpfcf_configs;
  private $comment;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct() {
    $this->wpfcf_configs = new WPFCF_Configs;
    $this->comment = get_comment( $_GET['c'] );
  }


  /**
   * Render the fields associated with the
   * current comment screen
   */
  function render() {

    $post = get_post( $this->comment->comment_post_ID );
    $post_type = $post->post_type;

    $field_groups_ids_all = $this->wpfcf_configs->get_filtered_fields_settings_config( 'comments', 'all' );

    if (!empty( $field_groups_ids_all )) {
      $metaboxes_args = $this->setup_metaboxes_args( $field_groups_ids_all, 'comment' );
      $this->create_metaboxes( $metaboxes_args );
    }

    $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'comments', $post_type );

    if (!empty( $field_groups_ids )) {
      $metaboxes_args = $this->setup_metaboxes_args( $field_groups_ids, 'comment' );
      $this->create_metaboxes( $metaboxes_args );
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
}