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