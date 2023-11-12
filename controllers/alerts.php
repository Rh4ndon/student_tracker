<?php

if(isset($success)){
   foreach($success as $success){
      echo '<script>Swal.fire({title: "'.$success.'",icon: "success"});';
   }
}


if(isset($warning)){
   foreach($warning as $warning){
    echo '<script>Swal.fire({title: "'.$warning.'",icon: "warning"});';
   }
}

if(isset($info)){
   foreach($info as $info){
    echo '<script>Swal.fire({title: "'.$info.'",icon: "info"});';
   }
}

if(isset($error)){
   foreach($error as $error){
    echo '<script>Swal.fire({title: "'.$error.'",icon: "error"});';
   }
}

?>