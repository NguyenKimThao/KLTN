<?php
require_once( ABSPATH . 'wp-admin/admin.php' );

// Load all the nav menu interface functions
require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );


function register_mysettings() {
    register_setting( 'mfpd-settings-group', 'mfpd_option_name' );
}

function camunda_create_menu() {
    // add_menu_page('Camunda Process', 'Camunda', 'administrator', 'wordpress-camunda', 'camunda_settings_page',plugins_url('/images/icon.png', __FILE__), 1);
}

?>