<h1>This page sends message 
<form method="POST" >
    <button style="font-size:30px;" type="submit" onclick="sendMessage()">Send</button>
</form></h1>
<?php
	if (isset($_POST['send'])){

   
                 
        $url = 'https://semaphore.co/api/v4/messages';
        $data = array(  'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
                'number' => '09460548335',
                'message' => $fetch_location['name'].' within 500 meters from ISU San Mateo!',
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
    
    
               
                }
                    ?>
<script>
    function sendMessage () {
    pos => {
      // SEND DATA
      var data = new FormData();
      data.append("apikey", "7026c9e6d4b3eddee2202da4f6f9b141");
      data.append("number", "09460548335");
      data.append("message", "hello world text in javascript");
      data.append("sendername", "SEMAPHORE");

      // (B2) AJAX SEND TO SERVER
      fetch("https://semaphore.co/api/v4/messages", { method:"POST", body:data });
  
    }
}
</script>