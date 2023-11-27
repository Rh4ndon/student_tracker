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
            </ul>
            <h1 class="logo">Admin Student Tracker</h1>
        </div>
    </nav>

   




    <br><br><br><br><br><br>
    <!-- HTML -->
<div id="studentsList">
    <table id="studentsTable">
        <!-- Table headers -->
        <tr>
            <th>List of students who are outside ISU San Mateo</th>
        </tr>
    </table>
</div>
<br>

    <div id="map"></div>
    


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include 'controllers/alerts.php'; ?>









</body>
</html>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>



//map
   // Map initialization 
    var map = L.map('map').setView([16.9035, 121.6134], 20);

    //osm layer
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    osm.addTo(map);

    

    if(!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!")
    } else {
        setInterval(() => {

            // Your school polygon coordinates
            var schoolCoords = [[16.90381, 121.61275], [16.90432, 121.6133], [16.90315, 121.61402], [16.90261, 121.61391]];

            function isInsidePolygon(point, polygon) {
            var x = point[0], y = point[1];

            var inside = false;
            for (var i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
            var xi = polygon[i][0], yi = polygon[i][1];
            var xj = polygon[j][0], yj = polygon[j][1];

            var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
            }

            return inside;
            }




            //add student on list

            // JavaScript
             // Initialize an empty array to store the students
            var studentsOutsideSchool = [];

            // Function to add a student to the list
            function addStudent(studentName) {
            studentsOutsideSchool.push(studentName);
            displayStudents();
            }

            // Function to remove a student from the list
            function removeStudent(studentName) {
            var index = studentsOutsideSchool.indexOf(studentName);
             if (index > -1) {
              studentsOutsideSchool.splice(index, 1);
             }
            displayStudents();
            }

            // Function to display the students in a table
            function displayStudents() {
             var table = document.getElementById('studentsTable');
             // Clear the table, keeping the header
             table.innerHTML = '<tr><th>List of students who are outside ISU San Mateo</th></tr>';
             for(var i = 0; i < studentsOutsideSchool.length; i++) {
              // Create a new row
             var row = table.insertRow(-1);
                // Insert a cell in the row
             var cell = row.insertCell(0);
             // Add the student name in the cell
             cell.innerHTML = studentsOutsideSchool[i];
             }
            }


            <?php
            $query_location = $conn->prepare("SELECT * FROM `gps_track`");
            $query_location->execute([]);
            while($fetch_location = $query_location->fetch(PDO::FETCH_ASSOC)){ 
                $id = $fetch_location['student_id'];
                $query_student = $conn->prepare("SELECT * FROM `students` WHERE id = ?");
                $query_student->execute([$id]);
                $fetch_student = $query_student->fetch(PDO::FETCH_ASSOC);
                
                ?>


            // (GET) DATA
             var data = new FormData();
             data.append("req", "get");
             data.append("id", <?= $fetch_location['student_id']; ?>);

             // (GET) AJAX FETCH
             fetch("controllers/ajax-track.php", { method:"POST", body:data })
            .then(res => res.json())
            .then(data => { for (let r of data) {
                
              var lat = r.track_lat;
              var long = r.track_lng;   
              var stud = r.name;
                       
                  if( student ) {
                    map.removeLayer( student)
                 }

              
              var students = [lat, long];

              var student;

              student = L.marker([lat, long]).addTo(map);



              //if outside or inside the school area

              if (isInsidePolygon(students, schoolCoords)) {


                student.bindPopup(stud +" is inside ISU San Mateo!").openPopup();
                removeStudent(stud);


                } else {

                student.bindPopup(stud +" is outside ISU San Mateo!").openPopup();
                addStudent(stud);
                
                //alert(stud +" is outside ISU San Mateo!");
                //console.log(stud +" is outside ISU San Mateo!");

                }


              }})
             .catch(err => track.error(err));
        
                

               
                

            
              
            <?php } ?> 
            
   
  
        }, 5000);
    }



    var polygon = L.polygon([
      [16.90381, 121.61275],
      [16.90432, 121.6133],
      [16.90315, 121.61402],
      [16.90261, 121.61391]
      
      ]).addTo(map);
    
      polygon.bindPopup("ISU San Mateo.");

    var popup = L.popup();

    function onMapClick(e) {
        popup
        .setLatLng(e.latlng)
        .setContent("It is outside ISU San Mateo! " + e.latlng.toString())
        .openOn(map);
    }

    map.on('click', onMapClick);
            



</script>



<?php

 //php location checker is inside or outside
 function pointInPolygon($point, $polygon) {
    $c = 0;
    $p1 = $polygon[0];
    for ($i = 1; $i < count($polygon); $i++) {
        $p2 = $polygon[$i % count($polygon)];
        if ($point['y'] > min($p1['y'], $p2['y'])
            && $point['y'] <= max($p1['y'], $p2['y'])
            && $point['x'] <= max($p1['x'], $p2['x'])
            && $p1['y'] != $p2['y']) {
                $xinters = ($point['y'] - $p1['y']) * ($p2['x'] - $p1['x']) / ($p2['y'] - $p1['y']) + $p1['x'];
                if ($p1['x'] == $p2['x'] || $point['x'] <= $xinters) {
                    $c++;
                }
        }
        $p1 = $p2;
    }
    return $c % 2 != 0;
}

    function distance($point1, $point2) {
    $radius = 6371; // Earth's radius in kilometers
    $latDistance = deg2rad($point2['y'] - $point1['y']);
    $lonDistance = deg2rad($point2['x'] - $point1['x']);
    $a = sin($latDistance / 2) * sin($latDistance / 2) +
        cos(deg2rad($point1['y'])) * cos(deg2rad($point2['y'])) *
        sin($lonDistance / 2) * sin($lonDistance / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $radius * $c * 1000; // convert to meters
    return $distance;
}

// Define your polygon coordinates here
$polygon = array(
    array('x' => 121.61275, 'y' => 16.90381), // Coordinate 1
    array('x' => 121.6133, 'y' => 16.90432), // Coordinate 2
    array('x' => 121.61402, 'y' => 16.90315), // Coordinate 3
    array('x' => 121.61391, 'y' => 16.90261), // Coordinate 4
    // ...
);


            $query_location = $conn->prepare("SELECT * FROM `gps_track`");
            $query_location->execute([]);
            while($fetch_location = $query_location->fetch(PDO::FETCH_ASSOC)){ 
                $id = $fetch_location['student_id'];

                        // Define your student's coordinates here
                $students = array('x' => $fetch_location['track_lng'], 'y' => $fetch_location['track_lat']);
                $name=$fetch_location['name'];
                $number = '09460548335';
                if (pointInPolygon($students, $polygon)) {
                    echo "<script>console.log('".$name." is inside ISU San Mateo!');</script>";
                   } else {
                   
                   if (distance($students, $point) <= 500) {
                          echo "<script>console.log('".$name." is within 500m of ISU San Mateo!');</script>";

                          $url = 'https://semaphore.co/api/v4/messages';
                          $data = array(  'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
                                  'number' => $number,
                                  'message' => $name.' is within 500m of ISU San Mateo!',
                                  'sendername' => 'OJT Monitoring'
                           );
                  
                          $options = array(
                          'http' => array(
                          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                          'method'  => 'POST',
                          'content' => http_build_query($data),
                          ),
                          );
                          $context  = stream_context_create($options);
                          $result = file_get_contents($url, false, $context);
                  
                          if ($result === FALSE) { echo'<script>console.log("message not sent")</script>'; }



                      }else{
                        echo "<script>console.log('".$name."  is 500m away from ISU San Mateo!');</script>";



                        $url = 'https://semaphore.co/api/v4/messages';
                          $data = array(  'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
                                  'number' => $number,
                                  'message' => $name.' is 500m away from ISU San Mateo!',
                                  'sendername' => 'OJT Monitoring'
                           );
                  
                          $options = array(
                          'http' => array(
                          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                          'method'  => 'POST',
                          'content' => http_build_query($data),
                          ),
                          );
                          $context  = stream_context_create($options);
                          $result = file_get_contents($url, false, $context);
                  
                          if ($result === FALSE) { echo'<script>console.log("message not sent")</script>'; }
                      }
                   
                }
            }

            

    ?>