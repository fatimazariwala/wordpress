<?php

require_once(ABSPATH . 'wp-config.php');

function view_users_shortcode(){

    ob_start();

    global $wpdb;
    $num = 1;
    ?>
    <style>
        body{
            margin : 0px;
            border : 0px;
            padding : 0px;
        }
        #data {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        padding : 10px;
        font-size : 18px;
        }

        #data td, #data th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #data tr:nth-child(even){background-color: #f2f2f2;}
        #data tr:nth-child(odd){background-color: white;}

        #data tr:hover {background-color: #ddd;}

        #data th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
            font-size:15px
        }
        #precise-map{
            display : none;
            width : 100vh;
            height : 100vw;
            padding-top: 250px;
            margin: 0px;
        }
        .displayData span{
            color:green;
            font-size:18px;
            font-weight:3px;
        }
        .displayData{
            display : block;
        }
        .displayData div{
            display : flex;
        }
    </style>
    <table id ="data">
        <tbody>
        <tr>
            <th></th>
            <th>NAME</th>
            <th>MOST RECENT UPDATE OF USER'S LOCATION</th>
            <th></th>
        </tr>
    <?php

    $result = $wpdb-> get_results("SELECT ul.id, ul.entry_time, ul.current_user_id, ul.latitude, ul.longitude, ul.location_address, ul.help_info
                                    FROM wp_user_location ul
                                    INNER JOIN (
                                        SELECT current_user_id, MAX(entry_time) AS max_entry_time
                                        FROM wp_user_location
                                        GROUP BY current_user_id
                                    ) ul_max ON ul.current_user_id = ul_max.current_user_id AND ul.entry_time = ul_max.max_entry_time
                                    ORDER BY ul.entry_time DESC;
                                    ");
    
    foreach ($result as $row) {
         $id = $row->current_user_id;
         $latitude = $row->latitude;
         $longitude = $row->longitude;
         $address = $row->location_address;
         $helpInfo = $row->help_info;
         $user_name = $wpdb->get_var("SELECT user_login FROM wp_users WHERE ID = '$id'");
         $date_time = $row->entry_time;
         $dateTime = new DateTime($date_time);
         $formattedDateTime = $dateTime->format('F j, Y g:i A');
         ?>
        <tr>
            <td><?php echo $num;?></td>
            <td><?php echo $user_name;?></td>
            <td>
                <div class="displayData">
                    <div><span><b>Address:</b></span><?php echo '&nbsp;&nbsp;' . $address;?></div>
                    <div><span><b>Request For:</b></span><?php echo '&nbsp;&nbsp;' . $helpInfo;?></div>
                    <div style="float:right;color:#30343b;font-size:13px;background-color:#d5f0e1">
                        Date: <?php echo esc_html( $formattedDateTime ); ?></div>
                </div>
            </td>
            <td>
            <a href="http://localhost/wordpress/index.php/complete-data?id=<?php echo $id;?>"><button class="view-btn">VIEW MORE DETAILS</button></a>
            </td>
        </tr>        
    <?php
    $num = $num + 1;
    }?>
    </tbody>
    </table> 
     <?php
    return ob_get_clean();
}
add_shortcode('view_users','view_users_shortcode');