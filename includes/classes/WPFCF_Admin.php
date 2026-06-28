<?php
namespace WPFCF;


class WPFCF_Admin {

  use WPFCF_Has_PostTypes;
  use WPFCF_Has_Admin_Page;
  
  function __construct() {
    $this->create_post_type( 'wpfcf_field_groups' );   //  Implemented from WPFCF_Has_PostTypes
    $this->create_admin_page( 'wpfcf_admin_page' );    //  Implemented from WPFCF_Has_Admin_Page
  }
}