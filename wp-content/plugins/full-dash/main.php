<?php
/**
 * Plugin Name: Profiles display
 * Description: This plugin will create a profile of different users
 * Version: 1.0
 * Author: TEAM SAVIOUR
 */

require_once(ABSPATH . 'wp-config.php');

global $wpdb;

function include_profile(){
    include_once plugin_dir_path(__FILE__) . 'manage-roles.php';
}
add_action('init','include_profile'); 
