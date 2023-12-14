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
            <li><a href="supervisor_page_choice.php">Home</a></li>
                <li><a href="controllers/logout.php">Logout</a></li>
                <li><button style="background:#86b649;" onclick="window.location.reload()">Refresh</button</li>
            </ul>
           
            <h1 class="logo">Supervisor Page</h1>
        </div>
    </nav>

   




    <br><br><br><br><br><br>
    <!-- HTML -->
<div id="studentsOutside">
    <table id="studentsTable">
        <!-- Table headers -->
        <tr>
            <th>List of Students to Monitor</th>
        </tr>
        
        <?php

            if(isset($_POST['allow'])){
                $std_id = $_POST['std_id'];
                $update_name = $conn->prepare("UPDATE `color` SET status = ? WHERE student_id = ?");
                $update_name->execute(['1', $std_id]);
                $success_alrt[] = 'Student was allowed to go outside!';



            }
            if(isset($_POST['dont'])){
                $std_id = $_POST['std_id'];
                $update_name = $conn->prepare("UPDATE `color` SET status = ? WHERE student_id = ?");
                $update_name->execute(['0', $std_id]);
                $success_alrt[] = 'Student was cancel to allow going outside!';



            }





            $query_color = $conn->prepare("SELECT * FROM `color`");
            $query_color->execute([]);
            $num_rows_color = $query_color->rowCount();

            if($num_rows_color>0){
            while($color = $query_color->fetch(PDO::FETCH_ASSOC)){       
            ?>
                <tr>
                <form method="POST" >    
                <td><?= $color['name'];?> 
                <input type="hidden" value="<?= $color['student_id'];?>" name="std_id">
                <?php if($color['status'] == 0){
                    echo '<button type="submit" name="allow" style="background:#86b649;">Press to Allow</button>';
                }else{
                    echo '<button type="submit" name="dont" style="background:#ce2b0e;">Press to Cancel Approve</button>';
                }?>
             
                </form>
                </tr>
                <?php }} ?>

     

   
        
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

const myCustomColour = '#86b649'; // Replace with your desired color

const markerHtmlStyles = `
  background-color: ${myCustomColour};
  width: 1.5rem;
  height: 1.5rem;
  display: block;
  left: -1.5rem;
  top: -1.5rem;
  position: relative;
  border-radius: 3rem 3rem 0;
  transform: rotate(45deg);
  border: 1px solid #FFFFFF`;

const greenIcon = L.divIcon({
  className: 'my-custom-pin',
  iconAnchor: [0, 24],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -36],
  html: `<span style="${markerHtmlStyles}" />`
});

const myCustomColour2 = '#ce2b0e'; // Replace with your desired color

const markerHtmlStyles2 = `
  background-color: ${myCustomColour2};
  width: 1.5rem;
  height: 1.5rem;
  display: block;
  left: -1.5rem;
  top: -1.5rem;
  position: relative;
  border-radius: 3rem 3rem 0;
  transform: rotate(45deg);
  border: 1px solid #FFFFFF`;

const redIcon = L.divIcon({
  className: 'my-custom-pin',
  iconAnchor: [0, 24],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -36],
  html: `<span style="${markerHtmlStyles2}" />`
});


    

    if(!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!")
    } else {
        setInterval(() => {

             // Your school polygon coordinates
            let polygon = [[121.61275, 16.90381], [121.6133, 16.90432], [121.61402, 16.90315], [121.61391, 16.90261]];

          // Function to check if a point is inside a polygon
            function isPointInPolygon(point, polygon) {
            let x = point[0], y = point[1];
  
            let inside = false;
            for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
            let xi = polygon[i][0], yi = polygon[i][1];
            let xj = polygon[j][0], yj = polygon[j][1];
    
            let intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
            }
  
            return inside;
            };


            // Function to calculate the distance between two points
            function calculateDistance(point1, point2) {
            let dx = point2[0] - point1[0];
            let dy = point2[1] - point1[1];
  
            return Math.sqrt(dx * dx + dy * dy);
            };




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
              var stud_id =r.student_id;
                       
                  if( student ) {
                    map.removeLayer( student)
                 }

              
              var students = [lat, long];

              var student;
                 

            
                
                // Define the student's location
                let studentss = [long, lat];


        
                // Check if the student is inside the polygon
                if (isPointInPolygon(studentss, polygon)) {
                student = L.marker([lat, long]).addTo(map);
                student.bindPopup(stud +" is inside ISU San Mateo!").openPopup();
              
           

                } else {
                // Calculate the distance from the student to the first point of the polygon
                let distance = calculateDistance(studentss, polygon[0]);
  
                // Check if the student is within 500 meters of the polygon
                // Note: This is a simplification and might not be accurate for large polygons or long distances
                if (distance <= 0.005) {
                student = L.marker([lat, long]).addTo(map);
                student.bindPopup(stud +" is within 500 meters from ISU San Mateo!").openPopup();
              

                }else{


                    

                    var data2 = new FormData();
                    data2.append("req", "colors");
                    data2.append("id", stud_id);
                    
                    // (GET) AJAX FETCH
             fetch("controllers/ajax-track.php", { method:"POST", body:data2 })
            .then(res => res.json())
            .then(data2 => { for (let c of data2) {
                
              
              var stat = c.status;


                    if (stat != 0) {
                    student = L.marker([lat, long],{ icon: greenIcon }).addTo(map);
                    }else{
                        student = L.marker([lat, long],{ icon: redIcon }).addTo(map);
                    }

                    student.bindPopup(stud + " is 500 meters away from ISU San Mateo!").openPopup();
                   
            }})

              
                }
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
            


setTimeout(function(){
   location.reload();
}, 600000);
</script>

<?php


function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
    $i = $j = $c = 0;
    for ($i = 0, $j = $points_polygon-1; $i < $points_polygon; $j = $i++) {
        if ((($vertices_y[$i] > $latitude_y) != ($vertices_y[$j] > $latitude_y)) &&
            ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
            $c = !$c;
        }
    }
    return $c;
}

function haversineGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}


$longitude1 = 121.61275;
$longitude2 = 121.6133;
$longitude3 = 121.61402;
$longitude4 = 121.61391;

$latitude1 = 16.90381;
$latitude2 = 16.90432;
$latitude3 = 16.90315;
$latitude4 = 16.90261;




            $query_location = $conn->prepare("SELECT * FROM `gps_track`");
            $query_location->execute([]);
            while($fetch_location = $query_location->fetch(PDO::FETCH_ASSOC)){ 
                $id = $fetch_location['student_id'];

                        // Define your student's coordinates here
                $students = array('x' => $fetch_location['track_lng'], 'y' => $fetch_location['track_lat']);
                $name=$fetch_location['name'];
                $number = '09532832291';


                
// Define the vertices of the school polygon (replace with actual coordinates)
$vertices_x = array($longitude1, $longitude2, $longitude3, $longitude4);
$vertices_y = array($latitude1, $latitude2, $latitude3, $latitude4);

// Student's location (replace with actual coordinates)
$student_longitude = $fetch_location['track_lng'];
$student_latitude = $fetch_location['track_lat'];

// School's location (for distance calculation, replace with actual coordinates)
$school_longitude = 121.6134;
$school_latitude = 16.9035;


// Check if inside the polygon
if (is_in_polygon(4, $vertices_x, $vertices_y, $student_longitude, $student_latitude)) {
    echo "<script>console.log('".$name." is inside ISU San Mateo!');</script>";
} else {
    // Calculate the distance from the school
    $distance = haversineGreatCircleDistance($school_latitude, $school_longitude, $student_latitude, $student_longitude);
    if ($distance <= 500) {
        // Send alert
        echo "<script>console.log('".$name." is within 500m of ISU San Mateo!');</script>";

    }else{
        //student query
        $select_student = $conn->prepare("SELECT * FROM `color` WHERE student_id = ?");
        $select_student->execute([$id]);
        $row = $select_student->fetch(PDO::FETCH_ASSOC);

        if($select_student->rowCount() > 0){
            echo "<script>console.log('".$name." is already recorded on the list of student outside ISU San Mateo!');</script>";
        }else{
        //record student data
       $insert_student = $conn->prepare("INSERT INTO `color`(student_id, name, status) VALUES(?,?,?)");
       $insert_student->execute([$id, $name, '0']);
        }

        //student query
        $select_student = $conn->prepare("SELECT * FROM `color` WHERE student_id = ?");
        $select_student->execute([$id]);
        $row = $select_student->fetch(PDO::FETCH_ASSOC);

        if($row['status']==0){

            echo "<script>console.log('".$name."  is 500m away from ISU San Mateo!');</script>";
            $url = 'https://semaphore.co/api/v4/messages';
            $data = array(  'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
                    'number' => $number,
                    'message' => $name.' is 500m away from ISU San Mateo!',
                    'sendername' => 'SEMAPHORE'
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
            echo "<script>console.log('".$name."  is allowed to go 500m away from ISU San Mateo!');</script>";
        }

       
    }
}
               
                   
                }
            

            

    ?>

<?php include 'controllers/alerts.php'; ?>