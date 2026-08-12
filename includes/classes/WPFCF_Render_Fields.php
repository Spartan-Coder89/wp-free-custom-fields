<?php
namespace WPFCF;


class WPFCF_Render_Fields {

  use WPFCF_Has_Metaboxes;

  function __construct() {

    add_action('current_screen', function( $screen ) {

      if ( $screen->post_type == 'wpfcf_field_groups' ) {
        return;
      }

      $context = $this->get_screen_context();
      
      if ( $context[ 'is_post_type_edit' ] ) {  //  Post types
        
        $collected_group_fields = []; 
        $locations = $this->get_fields_locations();

        foreach ($locations as $key => $location) {
          foreach ($location as $key => $location_screen) {

            if ( $location_screen->location === 'post-type' and $location_screen->selected_screen === $screen->post_type ) {
              $collected_group_fields[] = $location_screen->post_id;
            }
          }
        }

        $collected_group_fields = array_values(array_unique($collected_group_fields));
        $this->render_metabox( $collected_group_fields, $screen->post_type );
      
      } else if ( $context['is_page'] ) { //  Pages                 <-------------------  CONTINUE HERE . . KONTI NA LANG MVP NATO
        echo 'This is a page admin screen';
        exit;

      } else if ( $context['is_taxonomy_screen'] ) { //  Taxonomy
        echo 'This is a taxonomy admin screen';
        exit;

      } else if ( $context['is_user_edit'] ) { //  User
        echo 'This is a user admin screen';
        exit;

      } else if ( $context['is_attachment_edit'] ) { //  Attachment
        echo 'This is a attachment admin screen';
        exit;

      } else if ( $context['is_menu_screen'] ) { //  Menu
        echo 'This is a menu admin screen';
        exit;

      } else if ( $context['is_comments_screen'] ) { //  Comments
        echo 'This is a comment admin screen';
        exit;

      } else {
        //  Meh. Do nothing.
      }

    });
  }

  /**
   * Helper function to check admin screen context
   * Note: 
   * Usable only after current_screen hook is fired
   * so it should only be used inside current_screen hook
   */
  function get_screen_context() {

    $screen = get_current_screen();

    if ( !$screen ) {
      return null;
    }

    return [
      'base'                => $screen->base,
      'post_type'           => $screen->post_type ?? null,
      'taxonomy'            => $screen->taxonomy ?? null,
      'is_post_type_list'   => $screen->base === 'edit',
      'is_post_type_edit'   => $screen->base === 'post',
      'is_page'             => in_array($screen->base, ['edit', 'post'], true) && $screen->post_type === 'page',
      'is_taxonomy_screen'  => in_array($screen->base, ['edit-tags', 'term'], true),
      'is_user_list'        => $screen->base === 'users',
      'is_user_edit'        => in_array($screen->base, ['user-edit', 'profile'], true),
      'is_user_add'         => $screen->base === 'user',
      'is_attachment_list'  => $screen->base === 'upload',
      'is_attachment_edit'  => $screen->base === 'post' && $screen->post_type === 'attachment',
      'is_menu_screen'      => $screen->base === 'nav-menus',
      'is_comments_screen'  => $screen->base === 'edit-comments'
    ];
  }

  /**
   * Helper function to retrieve all 
   * group field settings config
   */
  function get_fields_locations() {

    $locations = [];

    $field_group_ids = get_posts([
      'post_type'      => 'wpfcf_field_groups',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'fields'         => 'ids'
    ]);

    foreach ($field_group_ids as $key => $field_group_id) {
      $locations[$field_group_id] = json_decode(get_post_meta( $field_group_id, 'wpfcf_fields_settings_config', true ));
    }

    return $locations;
  }
  
  /**
   * Renders the metaboxes and fields on
   * matched locations
   */
  function render_metabox( $group_field_ids, $screen_name ) {

    $context = $this->get_screen_context();

    foreach ($group_field_ids as $key => $group_field_id) {

      $wpfcf_fields_config = json_decode(get_post_meta( $group_field_id, 'wpfcf_fields_config', true ));
      $metabox_args = [];

      if ( $context[ 'is_post_type_edit' ] ) { // post-type
        
        $wpfcf_group_fields = get_post( $group_field_id );
        $rendered_fields_html = $this->render_fields_html( $wpfcf_fields_config );

        $metabox_args[] = [
          'html_id'         => str_replace('-', '_', $wpfcf_group_fields->post_name),
          'title'           => $wpfcf_group_fields->post_title,
          'callback_render' => function() use ( $rendered_fields_html ) { 
            require WPFCF_PATH .'/includes/templates/render_fields.php';
          }, 
          'screen'          => $screen_name,
          'context'         => 'normal',
          'priority'        => 'high'
        ];
        
      }

      //  Implemented from WPFCF_Has_Metaboxes trait
      $this->create_metaboxes($metabox_args);
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