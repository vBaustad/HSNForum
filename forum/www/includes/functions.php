<?php
require_once 'db_connect.php';

// TODO: Flytt likTraad og likInnlegg sammen, Bruk paramenter "$type" som i "harLikt" funksjonen!
function likTraad($conn, $trå_id, $bruker_id, $bruker_navn) {
    // Sjekk først om han/hun har likt tråden.
    $sql = "SELECT tråd_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bruker_id, $trå_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    // Brukeren har ikke likt tråden. Den kan likes
    if ($res->num_rows < 1) {
        $sql = "INSERT INTO likes (tråd_id, bruker_id, bruker_navn) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $trå_id, $bruker_id, $bruker_navn);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}
function likInnlegg($conn, $trå_id, $innlegg_id, $bruker_id, $bruker_navn) {
    // Sjekk først om han/hun har likt innlegget.
    $sql = "SELECT tråd_id, innlegg_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ? AND innlegg_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $bruker_id, $trå_id, $innlegg_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    // Brukeren har ikke likt tråden. Den kan likes
    if ($res->num_rows < 1) {
        $sql = "INSERT INTO likes (tråd_id, innlegg_id, bruker_id, bruker_navn) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $trå_id, $innlegg_id, $bruker_id, $bruker_navn);
        $stmt->execute();
        $stmt->close();
        return "Du har nå likt dette innlegget";
    } else {
        return "Du kan kun like 1 gang";
    }
}
function getLikes($conn, $innlegg_id, $tråd_id) {
    // Hvis innlegg er null, da teller vi likes i en tråd
    if ($innlegg_id == null) {
        $sql = "SELECT COUNT(bruker_id) as antLikes FROM likes WHERE tråd_id = ? AND innlegg_id IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tråd_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        $antLikes = $row['antLikes'];
        if (harLikt($conn, "traad", $innlegg_id, $tråd_id, $_SESSION['bruker_id']) == true) {
            $antLikes -= 1;
            if ($antLikes > 0) {
                return $antLikes . " andre liker dette";
            } else {
                return "0 andre liker dette";
            }
        } else {
            if ($antLikes > 0) {
                return $antLikes . " andre liker dette";
            } else {
                return "ingen har likt dette enda";
            }
        }
    }
    // Teller likes i innlegg
    else {
        $sql = "SELECT COUNT(bruker_id) as antLikes FROM likes WHERE tråd_id = ? AND innlegg_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $tråd_id, $innlegg_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $antLikes = $row['antLikes'];
        if (harLikt($conn, "innlegg", $innlegg_id, $tråd_id, $_SESSION['bruker_id']) == true) {
            $antLikes -= 1;
        }
        if ($antLikes > 0) {
            return $antLikes . " liker dette";
        } else {
            return "0 likes";
        }
    }
}
function harLikt ($conn, $type, $innlegg_id, $tråd_id, $bruker_id) {
    if ($type = "traad") {
        $sql = "SELECT innlegg_id, tråd_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $bruker_id, $tråd_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        if ($res->num_rows >= 1) {
            return true;
        } else {
            return false;
        }
    } elseif ($type = "innlegg") {
        $sql = "SELECT innlegg_id, tråd_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ? AND innlegg_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $bruker_id, $tråd_id, $innlegg_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        if ($res->num_rows >= 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function innleggIdag($conn, $bruker_id){

    $dato = DATE('Y-m-d') . '%';

    if($stmt = $conn->prepare("SELECT COUNT(*) AS innleggIdag FROM innlegg WHERE bruker_id = ? AND innlegg_dato LIKE ?")) {

        $stmt->bind_param("ss", $bruker_id, $dato);
        $stmt->execute();
        $stmt->store_result();

        //Bind results
        $stmt->bind_result($sql_innleggIdag);

        //fetch value
        $stmt->fetch();

        //close statement
        $stmt->close();


        return $sql_innleggIdag;

    }
}

function aktiveBrukere($conn, $sistAktiv){
    $dagensDato = DATE("Y-m-d");
    $formatDato = DATE("Y-m-d", strtotime($sistAktiv));


    if ($formatDato == $dagensDato) {

        $sistAktiv = DATE("Y-m-d H" . '%');


        if($stmt = $conn->prepare("SELECT COUNT(*) AS aktiveBrukere FROM bruker WHERE bruker_sist_aktiv LIKE ?")){
            //Bind parameters
            $stmt->bind_param("s", $sistAktiv);

            $stmt->execute();
            $stmt->store_result();

            //Bind results
            $stmt->bind_result($sql_aktiveBrukere);

            //fetch value
            $stmt->fetch();

            //close statement
            $stmt->close();

            return $sql_aktiveBrukere;
        }

    }

}

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
    $formatDato = date("Y-m-d H:i:s", strtotime($dato));

    $dagensdato = date("d-m-Y");
    $meldingsdato = date("d-m-Y", strtotime($formatDato));

    if ($dagensdato == $meldingsdato) {
        // Finner time
        $posttime = date("H", strtotime($dato));
        $currtime = date("H");

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

/* Sender epost til ny bruker */
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