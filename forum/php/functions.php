<?php // Login.php
require_once 'db_connect.php';

//send the welcome letter
function send_email($info){
    //setup the mailer
    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername("jogex123@gmail.com")
        ->setpassword("Spionspmia6258");

    // Creating the instance
    $swift = \Swift_Mailer::newInstance($transport);

    // Creating content
    $bruker = $info['brukernavn'];
    $fornavn = $info['fornavn'];
    $epost = $info['epost'];
    $nokkel = $info['nokkel'];
    $root = 'http://home/120400/public_html/html/2016/eksamen/forum_v2/';

    // Creating the message
    $message = \Swift_Message::newInstance("Velkommen til HSN forum!")
        ->setFrom(["post@test.no" => "Admin"])
        ->SetTO(["jogex123@gmail.com" => "Jørgen"])
        ->setBody(
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
'<html xmlns="http://www.w3.org/1999/xhtml">' .
'<head>' .
'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
'<title>Bekreft bruker</title>' .
'<style>' .
'p { font-family: Geneva, sans-serif; color: #666666 }' .
'h1 { font-family: Geneva, sans-serif; font-size: 2em; text-align: center;}' .
'h2 { font-size: 3em;  }' .
'h2 a { text-decoration: none; color: gray }' .
'h2 a:hover { color: black }' .
'</style>' .
'</head>' .

'<body style="width: 605px; margin-right: auto; margin-left: auto;">' .
    '<table cellpadding="0" cellspacing="0">' .
        '<tr>' .
            '<td width="605px" height="245px" valign="top">' .
                '<center>' .
                '<table width="560px" align="center">' .
                    '<tr>' .
                        '<td>' .
                            '<h1>Velkommen, ' . $fornavn . '!</h1>' .
                            '<p>Takk for at du registrerte deg</p>' .
                            '<p>Venligst bekreft eposten din ved å trykke på lenken under</p>' .
                            '<center>' .
                                '<h2>' .
                                    '<a href="' . $root . 'bekreft.php?email=' . $epost . '&nokkel=' . $nokkel . '">BEKREFT BRUKER</a>' .
                                '</h2>' .
                            '</center>' .
                        '</td>' .
                    '</tr>' .
                '</table>' .
                '</center>' .
            '</td>' .
        '</tr>' .
    '</table>' .
    '<footer>' .
        '<p style="clear: both;">Du har mottatt denne eposten fordi du registrerte deg på HSN forum. Hvis du mener du ikke skulle ha mottatt denne eposten, <a href="mailto:jorgen@solli.graphics">kontakt jorgen@solli.graphics</a></p>' .
    '</footer>' .
'</body>' .
'</html>',

'text/html' // Mark the content-type as HTML
);

    // Send mail
    $swift->send($message);
    
    // Grab the result      
    $result = $swift->send($message);
    return $result;
}

function show_errors($action){
 
    $error = false;
 
    if(!empty($action['result'])){
     
        $error = "<ul class=\"alert $action[result]\">"."\n";
 
        if(is_array($action['text'])){
     
            //loop out each error
            foreach($action['text'] as $text){
             
                $error .= "<li><p>$text</p></li>"."\n";
             
            }   
         
        }else{
         
            //single error
            $error .= "<li><p>$action[text]</p></li>";
         
        }
         
        $error .= "</ul>"."\n";
         
    }
 
    return $error;
 
}

?>