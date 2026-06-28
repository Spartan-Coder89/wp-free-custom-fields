<?php

spl_autoload_register( function ( $class ) {

  if (strpos( $class, 'WPFCF\\') !== 0) {
    return;
  }

  $directory_path = plugin_dir_path(__FILE__) .'includes/';
  $relative_class = substr( $class, strlen('WPFCF\\') );

  if (file_exists($directory_path .'classes/'. $relative_class . '.php')) {
    require_once $directory_path .'classes/'. $relative_class . '.php';

  } else if (file_exists($directory_path .'traits/'. $relative_class . '.php')) {
    require_once $directory_path .'traits/'. $relative_class . '.php';
  }
});