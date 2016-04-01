<?php
require_once(__DIR__ . '/../includes/db_connect.php');
require_once(__DIR__ . '/../includes/functions.php');

if (innlogget() == true) {
    $bruker_navn = $_SESSION['bruker_navn'];
    $bruker_id = $_SESSION['bruker_id'];

    if (isset($_GET['melding']) != "" && !empty($_GET['melding'])) {
        // Fjerener bruk av SQL kode
        $msg_melding_get = mysqli_real_escape_string($conn, $_GET['melding']);
        // Bruk av smileyface
        $msg_melding = str_replace(":)", "<i class=\"fa fa-smile-o\"></i>", $msg_melding_get);
        // Fjerner bruk av HTML tags
        $msg_melding_stripped = strip_tags($msg_melding, '<i><b><u>');

        $bruker_level = bruker_level();

        $sendData = mysqli_query($conn, "INSERT INTO chat (`bruker_navn`, `bruker_status`, `bruker_id`, `msg_melding`, `msg_dato`)
                            VALUES ('$bruker_navn', '$bruker_level', '$bruker_id', '$msg_melding_stripped', NOW())");
    }

    // $hentData = mysqli_query($conn, "SELECT bruker_navn, bruker_status, msg_melding, msg_dato FROM chat ORDER BY msg_dato DESC LIMIT 30");
    $hentData = mysqli_query($conn, "SELECT * FROM (SELECT * FROM chat ORDER BY msg_dato DESC LIMIT 30) AS resultat ORDER BY msg_dato ASC");

    while ($row = mysqli_fetch_assoc($hentData)) {
        $dagensdato = date("y-d/m");
        $meldingdato = date("y-d/m", strtotime($row['msg_dato']));

        //Deler opp datoen i to, korter den også ned (dropper år)
        $postdm = date("d/m ", strtotime($row['msg_dato']));
        $postgis = date("G:i:s ", strtotime($row['msg_dato']));

        if ($meldingdato == $dagensdato) {
            $postdm = "I dag ";
        }

        echo '  <div class="melding_rad">
                <p class="chat_bnavn ' . $row['bruker_status'] . '">' . $row['bruker_navn']  . ' </p>
                <p class="chat_dato">' . $postdm . $postgis . '</p> <br>
                <p class="chat_msg">' . $row['msg_melding'] . '</p>
            </div><br><hr class="hr-chat">';
    }
}

