<?php

include '../models/database_connect.php';

setcookie('user_id', '', time() - 1, '/');

header('location:../index.php');

?>