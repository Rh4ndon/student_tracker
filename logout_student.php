<?php  

include 'models/database_connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
}

?>
  <?php
          
          $query_student = $conn->prepare("SELECT * FROM `students` WHERE id = ?");
          $query_student->execute([$user_id]);
          $fetch_student = $query_student->fetch(PDO::FETCH_ASSOC);
          $student = $fetch_student['student_name'];
          
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
                <li><a href="controllers/logout_student.php">Logout</a></li>
            </ul>
            <h1 class="logo">Student GPS Tracker</h1>
        </div>
    </nav>



    <br><br><br><br> <br>
      <center>Are you sure you want to logout?</center>
      <form method="post" action="controllers/logout_stud.php">
        <center>
          <input type="hidden" value="<?php echo $user_id; ?>" name="out_id">
          <button type="submit" name="yes">Yes<button>
            <button type="submit" name="no">No<button>
        </center>
      </form>
      
  



  
  


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include 'controllers/alerts.php'; ?>









</body>
</html>
