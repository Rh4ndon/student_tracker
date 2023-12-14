<?php  

include 'models/database_connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Student GPS Tracker</title>
    <link rel="stylesheet" href="css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
  </head>
<body>
    <nav class="navbar">
        <div class="navbar-container container">
            <input type="checkbox" name="" id="">
            <div class="hamburger-lines">
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <ul class="menu-items">
                <li><a href="#">Home</a></li>
                <li><a href="controllers/logout.php">Logout</a></li>
                <li><button style="background:#86b649;" onclick="window.location.reload()">Refresh</button</li>
            </ul>
            <h1 class="logo">Admin Student Tracker</h1>
        </div>
    </nav>

   


    

    <br><br><br><br><br><br>
    <center>
    <button><a class="button"href="admin_ISU.php">ISU San Mateo</a></button><br><br>
    <button><a class="button"href="admin_LGU.php">LGU San Mateo</a></button><br><br>
    <button><a class="button"href="admin_Philrice.php">Philrice San Mateo</a></button>
    </center>
    


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include 'controllers/alerts.php'; ?>









</body>
</html>
