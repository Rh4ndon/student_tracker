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
                <li><a href="logout_student.php">Logout</a></li>
            </ul>
            <h1 class="logo">Student GPS Tracker</h1>
        </div>
    </nav>



    <br><br><br><br> <br>
      <div class="title">My Location</div>
      <div class="data" id="date"></div>
    </div>
    <div class="row">
      <div class="title">Latitude, Longitude</div>
      <div class="data">
        <span id="lat"></span>, <span id="lng"></span>
      </div>
    </div>
   

    <div id="map"></div>



  
    <script>
      var track = {
  
  // (A) INIT
  student : <?php echo htmlspecialchars($user_id); ?>, // student_id equals to session id
  stud : '<?php echo htmlspecialchars($student); ?>',  
  delay : 5000, // delay between gps update (ms)
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
      // SEND DATA
      var data = new FormData();
      data.append("req", "update");
      data.append("id", track.student);
      data.append("name", track.stud);
      data.append("lat", pos.coords.latitude);
      data.append("lng", pos.coords.longitude);

      // (B2) AJAX SEND TO SERVER
      fetch("controllers/ajax-track.php", { method:"POST", body:data })
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>



  //map
    // Map initialization 
      var map = L.map('map').setView([16.893456, 121.599361], 14);

      //osm layer
      var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      });
      osm.addTo(map);

      if(!navigator.geolocation) {
          console.log("Your browser doesn't support geolocation feature!")
      } else {
          setInterval(() => {
              navigator.geolocation.getCurrentPosition(getPosition)
          }, 50000);
      }

      var marker, circle;

      var polygon = L.polygon([
        [16.90381, 121.61275],
        [16.90432, 121.6133],
        [16.90315, 121.61402],
        [16.90261, 121.61391]
        
        ]).addTo(map);
      
        polygon.bindPopup("ISU San Mateo.");

        var polygon2 = L.polygon([
        [16.882039, 121.584991],
        [16.881832, 121.585692],
        [16.881448, 121.585582],
        [16.881718, 121.584839]
        
        ]).addTo(map);
      
        polygon2.bindPopup("LGU San Mateo.")

        var polygon3 = L.polygon([
        [16.874445, 121.595171],
        [16.874669, 121.595436],
        [16.874886, 121.595241],
        [16.874682, 121.594993]
        
        ]).addTo(map);
      
        polygon3.bindPopup("Philrice San Mateo.")

      function getPosition(position){
          // console.log(position)
          var lat = position.coords.latitude
          var long = position.coords.longitude
          var accuracy = position.coords.accuracy

          if(marker) {
              map.removeLayer(marker)
          }

          if(circle) {
              map.removeLayer(circle)
          }

          marker = L.marker([lat, long])
          circle = L.circle([lat, long], {radius: accuracy})
        

          var featureGroup = L.featureGroup([marker, circle]).addTo(map)


          map.fitBounds(featureGroup.getBounds())

          map.setZoom(20);

          console.log("Your coordinate is: Lat: "+ lat +" Long: "+ long+ " Accuracy: "+ accuracy)
      }

      var popup = L.popup();

      function onMapClick(e) {
          popup
          .setLatLng(e.latlng)
          .setContent("It is outside ISU San Mateo! " + e.latlng.toString())
          .openOn(map);
      }

      map.on('click', onMapClick);


      window.onbeforeunload = function() {
      var data = new FormData();
      data.append('del', '<?= $user_id ?>');

       navigator.sendBeacon('controllers/log.php', data);
      };


window.onbeforeunload = function() {
  $.ajax({
    url: 'controllers/log.php', // URL of the PHP file
    type: 'post', // The request method POST or GET
    data: {
        'del': '<?= $user_id ?>'
    }, // The data to send
    success: function(response) { // The function to execute upon a successful request
        console.log(response);
    },
    error: function(jqXHR, textStatus, errorThrown) { // The function to execute upon a failed request
        console.log(textStatus, errorThrown);
    }
});
};
  </script>

