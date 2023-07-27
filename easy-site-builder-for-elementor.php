<?php
/**
 * Plugin Name: Easy Site Builder For Elementor
 * Description: Easy Site Builder For Elementor is as powerful free WordPress plugin that allows you to create almost entire website with Elementor.
 * Version:     1.0
 * Author:      Sazzad
 * Author URI:  https://sazzadh.com/
 * Text Domain: easy-site-builder-for-elementor
 */

define( 'EASYSITEBUILDERFORELEMENTOR_URL', plugin_dir_url( __FILE__ ) );
define( 'EASYSITEBUILDERFORELEMENTOR_DRI', plugin_dir_path( __FILE__ ) );


function EasySiteBuilderForElementor_init() {

	// Load plugin file
	require_once( EASYSITEBUILDERFORELEMENTOR_DRI . 'plugin.php' );
	
}
add_action( 'plugins_loaded', 'EasySiteBuilderForElementor_init' );

