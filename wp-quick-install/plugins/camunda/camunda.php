<?php
/**
 * Plugin Name: camunda
 * Plugin URI: http://camunda.net
 * Description: Đây là plugin sử dụng camunda.
 * Version: 1.0 
 * Author: 
 * Author URI: http://camunda.com
 * License: GPLv2 or later 
 */
?>



<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
define( 'CAMUNDA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( CAMUNDA__PLUGIN_DIR . 'process.php' );
require_once( CAMUNDA__PLUGIN_DIR . 'camunda-setting.php' );
require_once( CAMUNDA__PLUGIN_DIR . 'class-wp-process-type.php' );
add_action( 'init', 'create_initial_process_types' );
add_action('admin_menu', 'camunda_create_menu'); 

?>