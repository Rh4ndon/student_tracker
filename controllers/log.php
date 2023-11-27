<?php

            include '../models/database_connect.php';
            

            $del_id = $_POST['del'];

            $remove_location2 = $conn->prepare("DELETE FROM `gps_track` WHERE student_id = ?");
            $remove_location2 ->execute([$del_id]);
        
     
            setcookie('user_id', '', time() - 1, '/');
            

       
            
            
            
            ?>