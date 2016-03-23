<?php
require_once(__DIR__ . '/../includes/db_connect.php');

$bruker_navn = $_SESSION['bruker_navn'];
$bruker_id = $_SESSION['bruker_id'];


if ($_GET['melding'] != "") {
    $msg_melding = mysqli_real_escape_string($conn, $_GET['melding']);
    $dato = date("Y-m-d G:i:s");

    $sendData = mysqli_query($conn, "INSERT INTO chat (`bruker_navn`, `bruker_id`, `msg_melding`, `msg_dato`)
                            VALUES ('$bruker_navn', '$bruker_id', '$msg_melding', '$dato')");
}

$hentData = mysqli_query($conn, "SELECT bruker_navn, msg_melding, msg_dato FROM chat ORDER BY msg_dato DESC LIMIT 30");


while ($row = mysqli_fetch_assoc($hentData)) {
    echo '<div class="melding_rad"><p class="chat_bnavn">' . $row['bruker_navn']  . '</p><p class="chat_msg">' . $row['msg_melding'] . '</p><p class="chat_dato">' . $row['msg_dato'] . '</p></div><br>';
}

