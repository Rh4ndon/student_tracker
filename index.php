<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student GPS Tracker</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <form method="POST" action="">
        <h1>OJT MONITORING</h1>
        
        <div class="input-container">
            <input type="text" name="username" placeholder="Username" required>
            <div class="fake-cursor"></div>
        </div>
        <div class="input-container">
            <input type="password" name="password" placeholder="Password" required>
            <div class="fake-cursor"></div>
        </div>
        <button name="submit"type="submit">Login</button>
        <br>
        <a href="register.php">Register</a>
    </form>
    <script src="js/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

<?php 

include 'models/database_connect.php';
if(isset($_POST['submit'])){
  $username = $_POST['username'];
  $username = filter_var($username, FILTER_SANITIZE_STRING); 
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING); 

  //student query
  $select_student = $conn->prepare("SELECT * FROM `students` WHERE username = ? AND password = ? LIMIT 1");
  $select_student->execute([$username, $password]);
  $row = $select_student->fetch(PDO::FETCH_ASSOC);
 //admin query
  $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE username = ? AND password = ? LIMIT 1");
  $select_admin->execute([$username, $password]);
  $row_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
  //supervisor query
  $select_supervisor = $conn->prepare("SELECT * FROM `supervisor` WHERE username = ? AND password = ? LIMIT 1");
  $select_supervisor->execute([$username, $password]);
  $row_supervisor = $select_supervisor->fetch(PDO::FETCH_ASSOC);

  if($select_student->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:student_home.php');
     
  }else if ($select_admin->rowCount() > 0){
     setcookie('user_id', $row_admin['id'], time() + 60*60*24*30, '/');
     header('location:admin_page.php');
  }else if ($select_supervisor->rowCount() > 0){
    setcookie('user_id', $row_supervisor['id'], time() + 60*60*24*30, '/');
    header('location:supervisor_page.php');
 }else{
     $warning_alrt[] = 'Incorrect username or password!';
  }



}

?>
<?php include 'controllers/alerts.php'; ?>