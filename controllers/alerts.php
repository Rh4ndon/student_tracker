<?php

if(isset($success_alrt)){
   foreach($success_alrt as $success_alrt){
      echo '<script>Swal.fire({title: "'.$success_alrt.'",icon: "success"});</script>';
   }
}

if(isset($warning_alrt)){
   foreach($warning_alrt as $warning_alrt){
    echo '<script>Swal.fire({title: "'.$warning_alrt.'",icon: "warning"});</script>';
   }
}

if(isset($info_alrt)){
   foreach($info as $info_alrt){
    echo '<script>Swal.fire({title: "'.$info_alrt.'",icon: "info"});</script>';
   }
}

if(isset($error_alrt)){
   foreach($error_alrt as $error_alrt){
    echo '<script>Swal.fire({title: "'.$error_alrt.'",icon: "error"});</script>';
   }
}

?>