<?php

/*
Plugin Name: Toggle Admin Bar
Plugin URI: https://github.com/DanielDrabik/Toggle-Admin-Bar/
Description: Allow users to toggle (hide and show) admin menu bar.
Version: 1.0
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Author: Daniel Drabik
Author URI: https://github.com/DanielDrabik/
*/

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'plugins_loaded', 'toggle_admin_bar_init' );
function toggle_admin_bar_init() {

	if(is_user_logged_in()) {
		add_action( 'admin_bar_menu', 'toggle_admin_bar_add_node', 0 );
		add_action( 'wp_enqueue_scripts', 'toggle_admin_bar_scripts');
		add_action( 'admin_enqueue_scripts', 'toggle_admin_bar_scripts');
	}
}

function toggle_admin_bar_add_node( $wp_admin_bar ) {
	$args = array(
		'id'    => 'toggle-admin-bar',
		'title' => '<span class="ab-icon"></span>',
		'meta'  => array(
            'target'   => '_self',
            'title'    => __( 'Toggle Admin Bar', 'toggle-admin-bar' ),
        )
	);
	$wp_admin_bar->add_node( $args );
}

function toggle_admin_bar_scripts() {
    wp_enqueue_style( 'toggle-admin-bar-style', plugins_url( 'css/styles.css', __FILE__ ), array( 'admin-bar' ) );
	wp_register_script('ajax-toggle-admin-bar-script', plugins_url( 'js/script.js', __FILE__ ), array('jquery'), '1.0.0', true );
	wp_enqueue_script('ajax-toggle-admin-bar-script');

	wp_localize_script( 'ajax-toggle-admin-bar-script', 'ajax_toggle_admin_bar_object', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'is_hidden' => get_user_meta(get_current_user_id(), 'toggle_admin_bar', 1),
	));
}

add_action( 'wp_ajax_toggleadminbar', 'ajax_toggle_admin_bar' );
function ajax_toggle_admin_bar(){
	if(isset($_POST['is_hidden']))
		update_user_meta(get_current_user_id(), 'toggle_admin_bar', $_POST['is_hidden']);
}
