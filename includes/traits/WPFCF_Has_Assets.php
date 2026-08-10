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
   *   ],
   *   'localize'       => [
   *    [
   *      'handle'      => 'the script handle',
   *      'object_name' => 'desired object name',
   *      'data'        => 'named array example: ['wpfcf_nonce' => wp_create_nonce( 'wp_rest' )]'
   *    ]      
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
            
          } else if ( $asset['type'] == 'script' ) {
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

      if ( isset($args['localize']) and !empty($args['localize']) ) {

        foreach ($args['localize'] as $localize_key => $localize) {
          wp_localize_script( $localize['handle'], $localize['object_name'], $localize['data']);
        }
      }

    });
  }

  function enqueue_admin_page_scripts() {
    
  }

  function localize_enqueued_script() {
    
  }
}