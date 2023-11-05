<?php

require_once(ABSPATH . 'wp-config.php');

function view_details_of_user(){

    ob_start();
    
    load_google_maps_api('init');

    global $wpdb;

    $id = $_GET['id'];

    if($id != 0){
?>
    <style>
            #mapContainer{
                height : 500px;
                width : 100%;
            }
            .info{
                background-color:#71bf94;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            .text-to-info{
                background-color:#91e6ba;
                font-size:13px;
                font-style:normal;
                font-weight:500;
                height:1.5;
                text-transform:uppercase;
                text-align:center;
                /* margin-bottom:0%;
                border-bottom:0%; */
            }
        </style>
        <?php $user_name = $wpdb->get_var("SELECT user_nicename FROM wp_users WHERE ID = '$id'"); ?>
        <div style="display:block;">
            <div class="text-to-info">Update on <?php echo $user_name; ?></div>
            <div id="mapContainer"></div>
        </div>
        <nav>
        <a href="http://localhost/wordpress/index.php/view-patient?id=<?php echo $id;?>"><button>VIEW USER'S MEDICAL DATA</button></a>&nbsp;&nbsp;&nbsp;
        </nav>
        <table>
            <tr>
                <th>Date-Time</th>
                <th>Location</th>
                <th>Info</th>
                <th></th>
            </tr>
<?php
    $data = $wpdb-> get_results("SELECT * FROM wp_user_location WHERE current_user_id = '$id' ORDER BY entry_time DESC");
    
    foreach($data as $row){
        $id = $row->current_user_id;
        $address = $row->location_address;
        $helpInfo = $row->help_info;
        $location_address_array = json_decode($row->location_address_array);
        $address_title = $location_address_array[1]->long_name;

        $date_time = $row->entry_time;
        $dateTime = new DateTime($date_time);
        $formattedDateTime = $dateTime->format('g:i A, j-F');

        $user_name = $wpdb->get_var("SELECT display_name FROM wp_users WHERE ID = '$id'");

         ?>    
            <tr>
                <td><?php echo $formattedDateTime; ?></td>
                <td><?php echo $address; ?></td>
                <td><?php echo $helpInfo; ?></td>
            </tr>
        <?php } ?>
        
        </table>
        <!-- <button id="getDirection">GET DIRECTIONS</button> -->
        <script>
            let map;
<?php   $data_for_map = $wpdb->get_row("SELECT latitude,longitude,location_address,location_address_array FROM wp_user_location WHERE current_user_id = '$id' ORDER BY entry_time DESC");
    
        $latitude = $data_for_map->latitude;
        $longitude = $data_for_map->longitude;
        $address = $data_for_map->location_address;
        $location_address_array = json_decode($data_for_map->location_address_array);
        $address_title = $location_address_array[1]->long_name;
?>
            async function init() {
                var post = new google.maps.LatLng(<?php echo $latitude; ?>,<?php echo $longitude; ?>);
                var address = '<?php echo $address; ?>';
                var address_title = '<?php echo $address_title; ?>';

                console.log(address,address_title);

                const { Map } = await google.maps.importLibrary("maps");

                map = new Map(document.getElementById("mapContainer"), {
                        center : post,
                        zoom : 15,
                        mayTypeId : "terrain"
                    });
                    
                const marker = new google.maps.Marker({
                    position : post,
                    map : map,
                    draggable : false,
                });

                const infowindow = new google.maps.InfoWindow({
                    content: "<span style='color:red;'><b>MOST RECENT UPDATE</b></span><br><b>" + address_title + "</b><br>" + address,
                    ariaLabel: address_title,
                });

                infowindow.open({
                    anchor: marker,
                    map,
                });

                const circle = new google.maps.Circle({
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",    
                    fillOpacity: 0.35,
                    map,
                    center: post,
                    radius: (1/2**(map.getZoom()))*10**(7),
                });

                console.log("zoom before " + map.getZoom(),circle.getRadius());

                map.addListener("zoom_changed", () => {
                        circle.setRadius((1/2**(map.getZoom()))*10**(7));
                        map.panTo(post); 
                        console.log(circle.getRadius(),map.getZoom());
                    });

                marker.addListener('click',()=>{
                    infowindow.open({
                        anchor: marker,
                        map,
                    });
                });
            
                console.log(map.panTo(post));
            }

            window.addEventListener('load', init);
        </script>
        <?php
    
}
    else{
        echo"USER NOT LOGGED IN --- CAN'T GET FURTHER DETAILS...";
    }
    return ob_get_clean();
}
add_shortcode("view_data","view_details_of_user");

// function view_self_map(){


// }



