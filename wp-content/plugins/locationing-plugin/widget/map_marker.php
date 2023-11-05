<?php

require_once(ABSPATH . 'wp-config.php');

function view_fullMap_shortcode(){

    ob_start();

    global $wpdb;

    $result = $wpdb-> get_results("SELECT ul.id, ul.entry_time, ul.current_user_id, ul.latitude, ul.longitude, ul.location_address, ul.help_info
                                    FROM wp_user_location ul
                                    INNER JOIN (
                                        SELECT current_user_id, MAX(entry_time) AS max_entry_time
                                        FROM wp_user_location
                                        GROUP BY current_user_id
                                    ) ul_max ON ul.current_user_id = ul_max.current_user_id AND ul.entry_time = ul_max.max_entry_time
                                    ORDER BY ul.entry_time DESC;");
    
    load_google_maps_api('initMap');
    ?>

    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBL3tCKWE9hgJE50EvpFiAshvJeYJy7bfU&callback=initMap"></script> -->

    <div id="map"></div>
   
<style>
    #map {
        width: 100%;
        height: 400px;
    }
    .info_content{
        height:50px;
        width:auto;
        padding:none;
    }
</style>
<script>
function initMap() {
    // const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
    // "marker",
    // );

    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    map.setTilt(50);
        
    // Multiple markers location, latitude, and longitude
    var markers = [
        <?php 
        foreach ($result as $row) {
            $id = $row->current_user_id;
            $latitude = $row->latitude;
            $longitude = $row->longitude;
            $address = $row->location_address;
            $user_name = $wpdb->get_var("SELECT user_login FROM wp_users WHERE ID = '$id'");

            echo '["'.$user_name.'", '.$latitude.', '.$longitude.', "'.$address.'"],';
        }
        ?>
    ];
      
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Place each marker on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        console.log(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0] || 'SAVIOUR'
        });
        
        // Add info window to marker    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent('<div class="info_content"><b>' + markers[i][0] + '</b><br>' + markers[i][3] + '</div>');
                infoWindow.open(map, marker);
            }
        })(marker, i));

        
        marker.addListener("click", () => {
            map.setZoom(12);
            map.setCenter(marker.getPosition());
            console.log('i am clicked',marker.getPosition());
        });

        // Center the map to fit all markers on the screen
        map.fitBounds(bounds);
    }

    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(10);
        google.maps.event.removeListener(boundsListener);
    });

    //new MarkerClusterer({ markers, map });
    
}

// Load initialize function
window.addEventListener('load', initMap);
</script>

<?php
    return ob_get_clean();
}
add_shortcode('view_fullMap','view_fullMap_shortcode');
?>