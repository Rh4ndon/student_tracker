<?php
            include '../models/database_connect.php';
			if (isset($_POST['yes'])){
			$del_id = $_POST['out_id'];
			$remove_location = $conn->prepare("DELETE FROM `gps_track` WHERE student_id = ?");
            $remove_location ->execute([$del_id]);
            $remove_color = $conn->prepare("DELETE FROM `color` WHERE student_id = ?");
            $remove_color ->execute([$del_id]);
        
            setcookie('user_id', '', time() - 1, '/');
            
            header('location:../index.php');
			}
            
            
            if (isset($_POST['no'])){

            header('location:../student_home.php');
            }
            
            
            ?>