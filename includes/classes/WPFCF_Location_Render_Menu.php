<?php
namespace WPFCF;


class WPFCF_Location_Render_Menu {

  private $wpfcf_configs;

  function __construct() {

    $this->wpfcf_configs = new WPFCF_Configs;

    add_action('admin_footer', function() {

      global $nav_menu_selected_id;

      $menu = wp_get_nav_menu_object( (int) $nav_menu_selected_id );
      $menu_slug = $menu ? $menu->slug : null;

      $field_groups_ids_all = $this->wpfcf_configs->get_filtered_fields_settings_config( 'menu', 'all' );

      echo '<div id="wpfcf_fields" style="display: none;">';

        if (!empty( $field_groups_ids_all )) {
          $this->render_the_fields_for_menu( $field_groups_ids_all );
        }

        $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'menu', $menu_slug );
        $this->render_the_fields_for_menu( $field_groups_ids );

      echo '</div>';
    });


    add_action('wp_nav_menu_item_custom_fields', function( $item_id, $item, $depth, $args, $id ) {

      global $nav_menu_selected_id;

      $menu = wp_get_nav_menu_object( (int) $nav_menu_selected_id );
      $menu_slug = $menu ? $menu->slug : null;

      $field_groups_ids_all = $this->wpfcf_configs->get_filtered_fields_settings_config( 'menu-items', 'all' );

      if (!empty( $field_groups_ids_all )) {
        $this->render_the_fields_for_menu_items( $field_groups_ids_all );
      }

      $field_groups_ids = $this->wpfcf_configs->get_filtered_fields_settings_config( 'menu-items', $menu_slug );

      if (!empty( $field_groups_ids )) {
        $this->render_the_fields_for_menu_items( $field_groups_ids );
      }

    }, 10, 5);
  }


  /**
   * Render the markup for the fields
   * associated with the group fields
   */
  function render_the_fields_for_menu( $field_groups_ids ) {

    if (!empty( $field_groups_ids )) {

      foreach ($field_groups_ids as $key => $field_groups_id) {

        $fields_config = $this->wpfcf_configs->get_fields_config( $field_groups_id );
        $post = get_post( $field_groups_id );

        echo '<div id="wpfcf_menu_field_group_'. esc_attr( $field_groups_id ) .'" class="wpfcf_menu_field_group_wrap">';

          echo '<div><h3>'. $post->post_title .'</h3></div>';

          foreach ($fields_config as $key => $field) {

            echo '<div class="wpfcf_menu_field_wrap">';
              echo '<label for="field_'. esc_attr($field->id) .'">'. $field->label .'</label>';
              echo $this->render_field_html( $field );
            echo '</div>';
          }

        echo '</div>';
      }

      /**
       * Javscript snippet to transfer the rendered 
       * group fields inside the form 
       * */ 
      ?>
      <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
          const source = document.querySelector('#wpfcf_fields');
          const target = document.querySelector('#nav-menu-footer');

          if (source && target) {
            target.parentNode.insertBefore(source, target);
            source.style.display = '';
          }
        });
      </script>
      <?php
    }
  }


  function render_the_fields_for_menu_items( $field_groups_ids ) {

    foreach ($field_groups_ids as $field_group_id) {

      $post = get_post($field_group_id);

      echo '<div>';
        echo '<div><h4>'. $post->post_title .'</h4></div>';

        $fields_config = $this->wpfcf_configs->get_fields_config( $field_group_id );

        foreach ($fields_config as $field) {

          $value = get_post_meta( $item_id, 'wpfcf_' . $field->name, true );

          echo '<p class="field-'. esc_attr( $field->name ) .' description description-wide">';
            echo '<label for="edit-menu-item-'. esc_attr( $field->name ) .'-'. esc_attr( $item_id ) .'">';
              echo esc_html( $field->label );
              echo $this->render_field_html( $field, $value );
            echo '</label>';
          echo '</p>';
        }

      echo '</div>';
    }
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