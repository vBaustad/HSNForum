<?php
require_once 'db_connect.php';

// TODO: Flytt likTraad og likInnlegg sammen, Bruk paramenter "$type" som i "harLikt" funksjonen!
function likTraad($conn, $tråd_id, $bruker_id, $bruker_navn) {
    // Sjekk først om han/hun har likt tråden.
    $sql = "SELECT tråd_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bruker_id, $tråd_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    // Brukeren har ikke likt tråden. Den kan likes
    if ($res->num_rows < 1) {
        $sql = "INSERT INTO likes (tråd_id, bruker_id, bruker_navn) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $tråd_id, $bruker_id, $bruker_navn);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

function likInnlegg($conn, $tråd_id, $innlegg_id, $bruker_id, $bruker_navn) {
    // Sjekk først om han/hun har likt innlegget.
    $sql = "SELECT tråd_id, innlegg_id, bruker_id FROM likes WHERE bruker_id = ? AND tråd_id = ? AND innlegg_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $bruker_id, $tråd_id, $innlegg_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    // Brukeren har ikke likt tråden. Den kan likes
    if ($res->num_rows < 1) {
        $sql = "INSERT INTO likes (tråd_id, innlegg_id, bruker_id, bruker_navn) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $tråd_id, $innlegg_id, $bruker_id, $bruker_navn);
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
    $dagensdato = date("d-m-Y");

    $sql ="SELECT COUNT(innlegg_id) AS innleggIdag FROM innlegg WHERE bruker_id = '$bruker_id' AND innlegg_dato = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dagensdato);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    return $row['innleggIdag'];
}

function aktiveBrukere($conn, $sistAktiv){

    $formatDato = date("Y-m-d G", strtotime($sistAktiv));
    $dagensDato = date("Y-m-d G");

    if ($formatDato == $dagensDato) {

        $sql = mysqli_query($conn, "SELECT COUNT(bruker_id) AS aktiveBrukere FROM bruker WHERE bruker_sist_aktiv = '$sistAktiv'");
        $row = mysqli_fetch_assoc($sql);
    }
    return $row['aktiveBrukere'];
}

function setAktiv($conn, $bruker_id) {
    $sql = "UPDATE bruker SET bruker_sist_aktiv = NOW() WHERE bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bruker_id);
    $stmt->execute();
    $stmt->close();
}

function tellInnlegg($conn, $type, $id) {
    if ($type == "bruker") {
        $sql = "SELECT COUNT(innlegg_id) AS antInnlegg FROM innlegg WHERE bruker_id = ?";
        $sql = $conn->prepare($sql);
        $sql->bind_param("i", $id);
        $sql->execute();
        $sql->bind_result($sql_antInnlegg);
        $sql->fetch();
        return $sql_antInnlegg;
        $stmt->close();
    } 
    
    elseif ($type == "ukat") {
        $sql = $conn->prepare("SELECT COUNT(innlegg_id) as antInnlegg FROM innlegg WHERE ukat_id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($sql_antInnlegg);
        $sql->fetch();
        $sql->close();
        return $sql_antInnlegg;
    }

    elseif ($type == "traad") {
        $sql = $conn->prepare("SELECT COUNT(innlegg_id) as antInnlegg FROM innlegg WHERE tråd_id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($sql_antInnlegg);
        $sql->fetch();
        return $sql_antInnlegg;
        $antinnlegg->close();
    }
    
    else {
        return false;
    }
}

function tellTraader($conn, $type, $id) {
    if ($type = "bruker") {
        $sql = "SELECT COUNT(tråd_id) AS antTråder FROM tråd WHERE bruker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($sq_antTråder);
        $stmt->fetch();
        $stmt->close();
        return $sq_antTråder;
    }
    elseif ($type = "ukat") {
        $anttråd = $conn->prepare("SELECT COUNT(tråd_id) as antPosts FROM tråd WHERE ukat_id = ?");
        $anttråd->bind_param("i", id);
        $anttråd->execute();
        $anttråd->store_result();
        $anttråd->bind_result($sql_antTråder);
        $anttråd->fetch();
        $anttråd->close();
        return $sql_antTråder;
    }
    else {
        return false;
    }
}

function sistAktivUkat ($conn, $type, $id) {
    if ($type == "tråd") {
        $siste_traad = $conn->prepare("SELECT tråd_dato, bruker_navn, bruker_id FROM tråd WHERE ukat_id = ? ORDER BY tråd_dato DESC LIMIT 1");
        $siste_traad->bind_param("i", $id);
        $siste_traad->execute();
        $siste_traad->store_result();
        $siste_traad->bind_result($sql_traad_tråd_dato, $sql_traad_bruker_navn, $sql_traad_bruker_id);
        $siste_traad->fetch();

        return array ($sql_traad_tråd_dato, $sql_traad_bruker_navn, $sql_traad_bruker_id);
    }
    elseif ($type == "innlegg") {
        $siste_innlegg = $conn->prepare("SELECT innlegg_dato, bruker_navn, bruker_id FROM innlegg WHERE ukat_id = ? ORDER BY innlegg_dato DESC LIMIT 1");
        $siste_innlegg->bind_param("i", $id);
        $siste_innlegg->execute();
        $siste_innlegg->store_result();
        $siste_innlegg->bind_result($sql_innlegg_dato, $sql_innlegg_bruker_navn, $sql_innlegg_bruker_id);
        $siste_innlegg->fetch();

        return array ($sql_innlegg_dato, $sql_innlegg_bruker_navn, $sql_innlegg_bruker_id);
    }
    else {
        return false;
    }
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

/* Sender epost til ny bruker */
function send_email($info) {
    // Henter infor fra array
    $fornavn = $info['fornavn'];
    $epost = $info['epost'];
    $nokkel = $info['nokkel'];
    $curpath = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $bekreftbruker = str_replace("registrer", "bekreftbruker", $curpath);

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