<?php
    $sender_date = date('l jS \of F Y h:i:s A');
    $ip = $_SERVER['REMOTE_ADDR']; 
    // The message
    $message = "Someone visited the Fonex WordPress admin login page on     " . $sender_date . "     IP address:    " . $ip;

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70);

   

  
    $headers[] = 'From: Fonex Visitor <no-reply@targetpointsinc.com>';
    $headers[] = 'To: Yuk Ho Cheung <ycheung@targetpointsinc.com>';
    // $headers[] = 'Cc: Tom Rogers <trogers@targetpointsinc.com>';
    //$headers[] = 'Bcc: Akeem Lewin <alewin@targetpointsinc.com>';
    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1 \r\n';
    $subject = 'Someone Visitied the login panel';
    $to = 'ycheung@targetpointsinc.com';
    $status = mail($to, $subject, $message, implode("\r\n", $headers));
    header('Location: http://www.fonexinc.com');
?>
