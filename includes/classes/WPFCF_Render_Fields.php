<?php
namespace WPFCF;


class WPFCF_Render_Fields {

  private $location_render_page;
  private $location_render_attachment_edit;
  private $location_render_post;
  private $location_render_taxonomy;
  private $location_render_comments;

  use WPFCF_Has_Screen_Context;
  use WPFCF_Has_Metaboxes;

  function __construct() {

    add_action('current_screen', function( $screen ) {

      $metaboxes_args = [];
      $context = $this->get_screen_context();
      
      // Pages
      if ( $context['is_page'] ) { 
        $this->location_render_page = new WPFCF_Location_Render_Page;
        $this->location_render_page->render();

      //  Attachment Edit
      }  else if ( $context['is_attachment_edit'] ) { 
        $this->location_render_attachment_edit = new WPFCF_Location_Render_Attachment_Edit;
        $this->location_render_attachment_edit->render();

      //  Post types
      } else if ( $context[ 'is_post_type_edit' ] ) { 
        $this->location_render_post = new WPFCF_Location_Render_Post( $screen->post_type );
        $this->location_render_post->render();

      //  Taxonomy
      } else if ( $context['is_taxonomy_screen'] ) { 
        $this->location_render_taxonomy = new WPFCF_Location_Render_Taxonomy( $context['taxonomy'] );
        $this->location_render_taxonomy->render();
        
      //  Comments
      } else if ( $context['is_comment_edit'] ) { 
        $this->location_render_comments = new WPFCF_Location_Render_Comments;
        $this->location_render_comments->render();

      } else {
        //  Meh. Do nothing.
      }
    });

    new WPFCF_Location_Render_Attachment_List;
    new WPFCF_Location_Render_User;
    new WPFCF_Location_Render_Menu;
    new WPFCF_Location_Render_Comment_Form;
  }

}