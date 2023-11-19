<?php 
 include 'models/database_connect.php';
 if(isset($_POST['submit'])){

 $name = $_POST['fullname'];
 $name = filter_var($name, FILTER_SANITIZE_STRING); 
 $username = $_POST['username'];
 $username = filter_var($username, FILTER_SANITIZE_STRING);
 $pass = sha1($_POST['password']);
 $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
 $confirm_pass = sha1($_POST['confirm_pass']);
 $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);
 

 $select_student = $conn->prepare("SELECT * FROM `students` WHERE student_name = ?");
 $select_student->execute([$name]);

 if($select_student->rowCount() > 0){
    $warning_alrt[] = 'Student is already registered!';
 }else{
    if($pass != $confirm_pass){
       $warning_alrt[] = 'Password do not matched!';
    }else{
        //record student data
       $insert_student = $conn->prepare("INSERT INTO `students`(student_name, username, password) VALUES(?,?,?)");
       $insert_student->execute([$name, $username, $pass]);
       //verify if student was recorded in the database
       if($insert_student){
          $verify_student = $conn->prepare("SELECT * FROM `students` WHERE username = ? AND password = ? LIMIT 1");
          $verify_student->execute([$username, $pass]);
          $row = $verify_student->fetch(PDO::FETCH_ASSOC);
       
          if($verify_student->rowCount() > 0){
             setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
             header('location:student_home.php');
          }else{
             $error_alrt[] = 'Error encountered please try again!';
             
          }
       }

    }
 }
}
 ?>
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
    <form method="POST">
        <h1>Student Tracker Registartion</h1>
        <p>Please enter your credentials to register for the student tracker.</p>
        <div class="input-container">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <div class="fake-cursor"></div>
        </div>
        <div class="input-container">
            <input type="text" name="username" placeholder="Username" required>
            <div class="fake-cursor"></div>
        </div>
        <div class="input-container">
            <input type="password" name="password" placeholder="Password" required>
            <div class="fake-cursor"></div>
        </div>
        <div class="input-container">
            <input type="password" name="confirm_pass" placeholder="Confirm Password" required>
            <div class="fake-cursor"></div>
        </div>
        <button name="submit"type="submit">Submit</button>
        <a href="index.php">Go Back</a>
    </form>
    <script src="js/index.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>

    
</body>
</html>


<?php include 'controllers/alerts.php'; ?>
    

