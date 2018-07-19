<?php

/*
Plugin Name: Toggle Admin Bar
Plugin URI: https://github.com/DanielDrabik/Toggle-Admin-Bar/
Description: Allow users to toggle (hide and show) admin menu bar.
Version: 1.0
Author: Daniel Drabik
Author URI: https://github.com/DanielDrabik/
*/

/**
 * Copyright (c) 2018 . All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
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
