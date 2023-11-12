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
            </ul>
            <h1 class="logo">Student GPS Tracker</h1>
        </div>
    </nav>



    <br><br><br><br>
    <div class="row">
      <div class="title">Updated</div>
      <div class="data" id="date"></div>
    </div>
    <div class="row">
      <div class="title">Latitude, Longitude</div>
      <div class="data">
        <span id="lat"></span>, <span id="lng"></span>
      </div>
    </div>
    <script>
      var track = {
  
  // (A) INIT
  student : <?php echo htmlspecialchars($user_id); ?>,   // student_id equals to session id
  delay : 10000, // delay between gps update (ms)
  timer : null,  // interval timer
  hDate : null,  // html date
  hLat : null,   // html latitude
  hLng : null,   // html longitude
  init : () => {
    // (A1) GET HTML
    track.hDate = document.getElementById("date");
    track.hLat = document.getElementById("lat");
    track.hLng = document.getElementById("lng");

    // (A2) START TRACKING
    track.update();
    track.timer = setInterval(track.update, track.delay);
  },

  // (B) SEND CURRENT LOCATION TO SERVER
  update : () => navigator.geolocation.getCurrentPosition(
    pos => {
      // (B1) LOCATION DATA
      var data = new FormData();
      data.append("req", "update");
      data.append("id", track.student);
      data.append("lat", pos.coords.latitude);
      data.append("lng", pos.coords.longitude);

      // (B2) AJAX SEND TO SERVER
      fetch("3-ajax-track.php", { method:"POST", body:data })
      .then(res => res.text())
      .then(txt => { if (txt=="OK") {
        let now = new Date();
        track.hDate.innerHTML = now.toString();
        track.hLat.innerHTML = pos.coords.latitude;
        track.hLng.innerHTML = pos.coords.longitude;
      } else { track.error(txt); }})
      .catch(err => track.error(err));
    },
    err => track.error(err)
  ),

  // (C) HELPER - ERROR HANDLER
  error : err => {
    console.error(err);
    alert("An error has occured, or location is not on or lost.");
    clearInterval(track.timer);
  }
};
window.onload = track.init;
    </script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include 'controllers/alerts.php'; ?>



</body>
</html>