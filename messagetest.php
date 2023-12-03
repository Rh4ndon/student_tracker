<h1>This page sends message 
<form method="POST" >
    <button style="font-size:30px;" type="submit" onclick="sendMessage()">Send</button>
</form></h1>
<?php
	if (isset($_POST['send'])){

   
                 
        $url = 'https://semaphore.co/api/v4/messages';
        $data = array(  'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
                'number' => '09363639838',
                'message' => 'Hey',
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




        $ch = curl_init();
        $parameters = array(
            'apikey' => '7026c9e6d4b3eddee2202da4f6f9b141', //Your API KEY
            'number' => '09460548335',
            'message' => 'I just sent my first message with Semaphore',
            'sendername' => 'SEMAPHORE'
        );
        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages' );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        
        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );
        
        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);
        
        //Show the server response
        echo $output;
                            

    
    
               
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