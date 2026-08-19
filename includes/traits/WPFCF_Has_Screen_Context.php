<?php
namespace WPFCF;

trait WPFCF_Has_Screen_Context {

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
      'is_comments_list'    => $screen->base === 'edit-comments',
      'is_comment_edit'     => $screen->base === 'comment'
    ];
  }
}