<?php
require_once 'db_connect.php';
//send the welcome letter
function send_email($info)
{
    // Henter infor fra array
    $bruker = $info['brukernavn'];
    $fornavn = $info['fornavn'];
    $epost = $info['epost'];
    $nokkel = $info['nokkel'];
    $root = 'localhost/forum/www/';

    // Creating the message
    $melding = '<!DOCTYPE html PUBLIC>';
    $melding .= '<html xmlns="http://www.w3.org/1999/xhtml">';
    $melding .= '<head>';
    $melding .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    $melding .= '<title>Bekreft bruker</title>';
    $melding .= '<style>';
    $melding .= '  p { font-family: Geneva, sans-serif; color: #666666 }';
    $melding .= '  h1 { font-family: Geneva, sans-serif; font-size: 2em; text-align: center;}';
    $melding .= '  h2 { font-size: 3em;  }';
    $melding .= '  h2 a { text-decoration: none; color: gray }';
    $melding .= '  h2 a:hover { color: black }';
    $melding .= '</style>';
    $melding .= '</head>';
    $melding .= '<body style="width: 605px; margin-right: auto; margin-left: auto;">';
    $melding .= '    <table cellpadding="0" cellspacing="0">';
    $melding .= '        <tr>';
    $melding .= '            <td width="605px" height="245px" valign="top">';
    $melding .= '                <center>';
    $melding .= '                <table width="560px" align="center">';
    $melding .= '                    <tr>';
    $melding .= '                        <td>';
    $melding .= '                            <h1>Velkommen, ' . $fornavn . '!</h1>';
    $melding .= '                            <p>Takk for at du registrerte deg</p>';
    $melding .= '                            <p>Venligst bekreft eposten din ved å trykke på lenken under</p>';
    $melding .= '                            <center>';
    $melding .= '                                <h2>';
    $melding .= '                                    <a href="' . $root . 'bekreftbruker.php?epost=' . $epost . '&nokkel=' . $nokkel . '">BEKREFT BRUKER</a>';
    $melding .= '                                </h2>';
    $melding .= '                            </center>';
    $melding .= '                        </td>';
    $melding .= '                    </tr>';
    $melding .= '                </table>';
    $melding .= '                </center>';
    $melding .= '            </td>';
    $melding .= '        </tr>';
    $melding .= '    </table>';
    $melding .= '    <footer>';
    $melding .= '        <p style="clear: both;">Du har mottatt denne eposten fordi du registrerte deg på HSN forum. Hvis du mener du ikke skulle ha mottatt denne eposten, <a href="mailto:jorgen@solli.graphics">kontakt jorgen@solli.graphics</a></p>';
    $melding .= '    </footer>';
    $melding .= '</body>';
    $melding .= '</html>';
    $melding .= '</html>';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <post@hsn.no>' . "\r\n";

    mail($epost, "Velkommen til HSN forum, " . $fornavn . "!", $melding, $headers);
    $result = "Mail sendt!";
    return $result;
}

/* IKKE I BRUK! */
function show_errors($action)
{

    $error = false;

    if (!empty($action['result'])) {

        $error = "<ul class=\"alert $action[result]\">" . "\n";

        if (is_array($action['text'])) {

            //loop out each error
            foreach ($action['text'] as $text) {

                $error .= "<li><p>$text</p></li>" . "\n";

            }

        } else {

            //single error
            $error .= "<li><p>$action[text]</p></li>";

        }

        $error .= "</ul>" . "\n";
    }

    return $error;

}

function lesParam($param)
{
    $input = "";
    if (isset($_POST[$param])) {
        $input = htmlentities(stripslashes($_POST[$param]));
        return $input;
    }
}

?>