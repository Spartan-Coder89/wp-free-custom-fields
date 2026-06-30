<?php
namespace WPFCF;

trait WPFCF_Has_Assets {
  
  /**
   * Loads scripts and styles assets in wp-admin.
   * 
   * Format of accepted parameter:
   * [
   *  'post_type'       => 'define the cpt slug here',
   *  'allowed_screens' => ['post.php', 'post-new.php'] ..list of the screens to load the assets,
   *  'assets'          => [
   *    [
   *      'type'        => 'style' or 'script', 
   *      'handle'      => 'define handle here',
   *      'source'      => 'url for the asset here',
   *      'dependency'  => [],
   *      'version'     => WPFCF_VERSION,
   *    ],
   *    ....
   *   ]
   * ]
   */
  function enqueue_cpt_admin_scripts( Array $args ) {

    add_action('admin_enqueue_scripts', function( $hook_suffix ) use ( $args ) {

      global $post;

      if ( !in_array($hook_suffix, $args['allowed_screens'], true) ) return;
      if ( !$post or ($args['post_type'] !== $post->post_type) ) return;

      if ( isset($args['assets']) and !empty($args['assets']) ) {

        foreach ($args['assets'] as $asset_key => $asset) {
          
          if ( $asset['type'] == 'style' ) {
            wp_enqueue_style(
              $asset['handle'],
              $asset['source'],
              $asset['dependency'],
              $asset['version']
            );
          }

          if ( $asset['type'] == 'script' ) {
            wp_enqueue_script(
              $asset['handle'],
              $asset['source'],
              $asset['dependency'],
              $asset['version'],
              $asset['in_footer']
            );
          }
        }
      }

    });
  }

  function enqueue_admin_page_scripts() {
    
  }
}