<?php

require_once(ABSPATH . 'wp-config.php');
require_once (plugin_dir_path(__FILE__) . '/main.php');

function get_location_shortcode(){

    ob_start();
    
    $get_id = get_current_user_id();
    
    if($get_id != '0'){?>
        <style>
            input[type=text]{
                width: 100%;
                padding: 15px;
                margin: 5px 0 22px 0;
                display: inline-block;
                border: none;
                background: #f1f1f1;
            }

            input[type=text]:focus{
                background-color: #ddd;
                outline: none;
            }
        </style>
    	<button id = "my_button">REQUEST HELP</button>
        <form id="input">
            <div id="helpInfo"></div>
        </form>
    <?php
    }
    return ob_get_clean();
}
add_shortcode('live_location','get_location_shortcode');

function allowed(){
    ob_start();
    $get_id = get_current_user_id();
    if($get_id != '0'){
	 $get = wp_get_current_user();
	 $role = $get->roles[0]; 
	 //var_dump($get);
	    
	 if($role == 'administrator'){
	       echo"<a href='http://localhost/wordpress/new-login'>Locate</a>";
	    }
    }
    return ob_get_clean();
}
add_shortcode('go_to_button','allowed');

function map_for_user_location(){
    ob_start();
    ?>
    <style>
        #map-for-users{
            width:100%;
            height:500px;
        }
        .custom-map-control-button{
            background-color: #60b58d;
            color: #363837;
            height : 30px;
            border: 2px solid #4CAF50;
            /* box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19); */
        }
        .custom-map-control-button:hover {
            box-shadow: 0 3px 3px 0 rgba(0,0,0,0.24), 0 3px 3px 0 rgba(0,0,0,0.19);
        }
    </style>
    <div id="map-for-users"></div>
    <?php
    load_google_maps_api('initMap2');
    return ob_get_clean();
}
add_shortcode('map-for-user-location','map_for_user_location');




 ?>

