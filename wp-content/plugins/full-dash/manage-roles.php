<?php 

require_once(ABSPATH . 'wp-config.php');

function manage_roles(){
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM wp_users");

    ob_start();?>

<style>
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
    </style>
    <table id ="data">
        <tbody>
        <tr>
            <th>UserID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th></th>
        </tr><?php
         foreach ($result as $row) {

            $id = $row->ID;
            $username = $row->user_login;
            $email = $row->user_email;
            $userdata = get_userdata($id);
            // echo"<pre>";
            // var_dump($userdata);
            // echo"</pre>";
            $role = $userdata->roles[0];
            //var_dump($role);
                $role = str_replace('_',' ',$role);
                $role = ucfirst($role);

            ?>
        <tr>
            <td><?php echo $id; ?></td>
            <td>
                <div style="display:flex;">
                    <img src="http://localhost/wordpress/wp-content/uploads/2023/08/avatarrrr.jpg" height=70px width=70px></img>
                    <p style="margin:5px;margin-top:20px;"><?php echo $username;?></p>
                </div>
            </td>
            <td><?php echo $email;?></td>
            <td><?php echo $role;?></td>
            <td><a href="#"><button><i class="fas fa-edit"></i></button></a></td></tr>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    <?php
         
        return ob_get_clean();
}
add_shortcode('manage_roles','manage_roles');

// $updated = wp_update_user(array(
//     'ID'   => $user->ID,
//     'role' => $new_role,