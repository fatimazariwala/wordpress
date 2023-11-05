<?php
/**
 * Plugin Name: Live Location Plugin
 * Description: Captures live location from users and displays it on a map.
 * Version: 1.0
 * Author: Your Name
 */

require_once(ABSPATH . 'wp-config.php');

global $wpdb;

function include_page(){
    include_once plugin_dir_path(__FILE__) . 'location.php';
    include_once plugin_dir_path(__FILE__) . 'widget/map_marker.php';
    include_once plugin_dir_path(__FILE__) . 'widget/view_users.php';
    include_once plugin_dir_path(__FILE__) . 'widget/individual_user_map.php';
    
}

add_action('init','include_page'); 

if ( ! function_exists( 'locationing_geo_enqueue_scripts' ) ) {

function locationing_geo_enqueue_scripts() {
    
    if ( is_front_page() || is_single() || is_page() ) {
        wp_enqueue_script( 'main_js', plugins_url( 'locationing-plugin' ) . '/js/location_detect.js', array( 'jquery' ), '', true );
        wp_enqueue_script('map_js', plugins_url('/js/map.js', __FILE__), array('jquery'), '', true);
        wp_localize_script( 'map_js', 'geo',
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
        wp_localize_script( 'map_js', 'update_geo',
            array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
        //     wp_localize_script('map_js', 'userDataForMap', array(
        //         'send_to_map' => $send_to_map,
        // ));
    }
}
}
add_action( 'wp_enqueue_scripts', 'locationing_geo_enqueue_scripts' );

function load_google_maps_api($callBackFunction) {

    $api_key = 'AIzaSyBL3tCKWE9hgJE50EvpFiAshvJeYJy7bfU';
    $url = "https://maps.googleapis.com/maps/api/js?key=$api_key&libraries=places&callback=$callBackFunction";

    wp_enqueue_script('google-maps-api', $url, array(), null, true);
}
add_action('wp_enqueue_scripts', 'load_google_maps_api');

function location_table() {

    global $wpdb;

    $table_name = $wpdb->prefix . 'user_location';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        entry_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        current_user_id INT(255) NOT NULL,
        latitude FLOAT NOT NULL,
        longitude FLOAT NOT NULL,
        location_address TEXT NOT NULL,
        location_address_array TEXT NOT NULL,
        help_info TEXT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'location_table');

function post_currentData() {

    ob_start();

        global $wpdb;

        $locate = $_POST['data'];
        $user = get_current_user_id();

        $latitude = sanitize_text_field($locate['latitude']);
        $longitude = sanitize_text_field($locate['longitude']);
        $address = sanitize_text_field($locate['address']);
        $address_array = json_encode($locate['addressArray']);
        $help_info = sanitize_text_field($locate['helpInfo']);

        $check_user = $wpdb->get_var("SELECT current_user_id FROM `wp_user_location` WHERE current_user_id = $user");
        //echo "chop" . $check_user;
        if($check_user != $user){
            //wp_send_json($check_user);
            $table_name = $wpdb->prefix . 'user_location';

            $result = $wpdb->insert(
                $table_name,
                [
                    'latitude' => $latitude,
                    'location_address' => $address,
                    'current_user_id' => $user,
                    'longitude' => $longitude,
                    'location_address_array' => $address_array,
                    'help_info' => $help_info
                ]
            );

            if ($result !== false) {
                wp_send_json('sent');
                exit;
            } else {
                wp_send_json('notSent');
                exit;
            }
        }else{
            wp_send_json('alreadyExists');
            exit;
        }

       return ob_get_clean();
}
add_action('wp_ajax_currentData_action', 'post_currentData');
add_action('wp_ajax_nopriv_currentData_action', 'post_currentData');

function update_location(){

    ob_start();

    global $wpdb;

    $updateLocate = $_POST['data'];
    $user = get_current_user_id();

    $latitude = sanitize_text_field($updateLocate['latitude']);
    $longitude = sanitize_text_field($updateLocate['longitude']);
    $address = sanitize_text_field($updateLocate['address']);
    $address_array = json_encode($updateLocate['addressArray']);
    $help_info = sanitize_text_field($updateLocate['helpInfo']);

    $table_name = $wpdb->prefix . 'user_location';

    $sql = $wpdb->insert(
        $table_name,
        [
            'latitude' => $latitude,
            'location_address' => $address,
            'current_user_id' => $user,
            'longitude' => $longitude,
            'location_address_array' => $address_array,
            'help_info' => $help_info
        ]
    );

    wp_send_json($updateLocate);

    return ob_get_clean();
}
add_action('wp_ajax_updateData_action', 'update_location');
add_action('wp_ajax_nopriv_updateData_action', 'update_location');


function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
add_shortcode('get_ip','getUserIpAddr');

