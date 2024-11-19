<?php
function sendEventConfirmation($userEmail, $eventDetails) {
    $to = $userEmail;
    $subject = "Event Registration Confirmation";
    
    $message = "
    <html>
    <head>
        <title>Registration Confirmation</title>
    </head>
    <body>
        <h2>Event Registration Confirmation</h2>
        <p>You have successfully registered for {$eventDetails['title']}</p>
        <p>Event Details:</p>
        <ul>
            <li>Date: {$eventDetails['event_date']}</li>
            <li>Location: {$eventDetails['location']}</li>
        </ul>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    mail($to, $subject, $message, $headers);
}