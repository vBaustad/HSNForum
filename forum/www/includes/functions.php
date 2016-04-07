<?php
require_once 'db_connect.php';

function sistAktiv($conn, $bruker_id) {
    $sql = "UPDATE bruker SET bruker_sist_aktiv = NOW() WHERE bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bruker_id);
    $stmt->execute();
    $stmt->close();
}

function tellInnlegg($conn, $bruker_id) {
    $sql = "SELECT COUNT(innlegg_id) AS antInnlegg FROM innlegg WHERE bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bruker_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    
    return $row['antInnlegg'];
}

function tellTraader($conn, $bruker_id) {
    $sql = "SELECT COUNT(tråd_id) AS antTråder FROM tråd WHERE bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bruker_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    return $row['antTråder'];
}

function hentBilde($conn, $bruker_id) {
    $sql = "SELECT bruker_bilde FROM bruker WHERE bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bruker_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    return $row['bruker_bilde'];
}

function datoSjekk ($dato) {
    $formatDato = date("Y-m-d G:i:s", strtotime($dato));

    $dagensdato = date("d-m-Y");
    $meldingsdato = date("d-m-Y", strtotime($formatDato));

    if ($dagensdato == $meldingsdato) {
        // Finner time
        $posttime = date("G", strtotime($dato));
        $currtime = date("G");

        $postMin = date("i" , strtotime($dato));
        $currMin = date("i");

        $diffH = $currtime - $posttime;
        $diffM = $currMin - $postMin;

        if ($diffH > 0 ) {
            return $diffH . " timer siden";
        } else  {
            return $diffM . " minutter siden";
        }

    } else {
        $kortdato = utf8_encode(strftime("%d %B %Y %H:%M", strtotime($formatDato)));
        return $kortdato;
    }
}

function innlogget() {
    if (isset($_SESSION['innlogget'])) {
        return true;
    }
    else return false;
}

function bruker_level() {
    if (isset($_SESSION['innlogget']) && $_SESSION['bruker_level'] == '2') {
        return "admin";
    }
    else return "regular";
}

//send the welcome letter
function send_email($info) {
    // Henter infor fra array
    $fornavn = $info['fornavn'];
    $epost = $info['epost'];
    $nokkel = $info['nokkel'];
    $curpath = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $bekreftbruker = str_replace("registrer", "includes/bekreftbruker", $curpath);

    // Creating the message
    $melding = '<!DOCTYPE html PUBLIC>';
    $melding .= '<html xmlns="http://www.w3.org/1999/xhtml">';
    $melding .= '<head>';
    $melding .= '    <link href=\'https://fonts.googleapis.com/css?family=Open+Sans:400,700,800,400italic\' rel=\'stylesheet\' type=\'text/css\'>';
    $melding .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    $melding .= '    <title>Bekreft bruker</title>';
    $melding .= '    <style>';
    $melding .= '      html {font-family: \'Open Sans\', sans-serif;}';
    $melding .= '      p { font-family: Geneva, sans-serif; color: #666666 }';
    $melding .= '      h1 { font-family: Geneva, sans-serif; font-size: 2em; text-align: center;}';
    $melding .= '      h2 { font-size: 3em;  }';
    $melding .= '      h2 a { text-decoration: none; color: #2e2e2e }';
    $melding .= '      h2 a:hover { color: #121212 }';
    $melding .= '      .center { margin-right: auto; margin-left: auto; text-align: center}';
    $melding .= '      footer { clear: both; }';
    $melding .= '   </style>';
    $melding .= '</head>';
    $melding .= '<body style="width: 605px; margin-right: auto; margin-left: auto;">';
    $melding .= '    <div class="center">';
    $melding .= '        <h1>Velkommen, ' . $fornavn . '!</h1>';
    $melding .= '        <p>Takk for at du registrerte deg</p>';
    $melding .= '        <p>Venligst bekreft eposten din ved å trykke på lenken under</p>';
    $melding .= '        <h2 class="center">';
    $melding .= '           <a href="' . $bekreftbruker . '?epost=' . $epost . '&nokkel=' . $nokkel . '">BEKREFT BRUKER</a>';
    $melding .= '        </h2>';
    $melding .= '    </div>';
    $melding .= '    <footer>';
    $melding .= '        <p>Du har mottatt denne eposten fordi du registrerte deg på HSN forum. Hvis du mener du ikke skulle ha mottatt denne eposten, <a href="mailto:jorgen@solli.graphics">kontakt jorgen@solli.graphics</a></p>';
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