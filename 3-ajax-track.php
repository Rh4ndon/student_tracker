<?php
if (isset($_POST["req"])) {
  require "2-lib-track.php";
  switch ($_POST["req"]) {
    // (A) UPDATE RIDER LOCATION
    case "update":
      echo $_TRACK->update($_POST["id"], $_POST["lng"], $_POST["lat"])
        ? "OK" : $_TRACK->error ;
      break;

    // (B) GET RIDER(S) LAST KNOWN LOCATION
    case "get":
      echo json_encode($_TRACK->get(isset($_POST["id"]) ? $_POST["id"] : null));
      break;
  }
}