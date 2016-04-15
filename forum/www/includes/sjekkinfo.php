<?php
require_once ('db_connect.php');

if (isset($_GET['bnavn']) && $_GET['bnavn'] != "") {
    $bnavn = $_GET['bnavn'];

    $stmt = $conn->prepare("CALL finn_bruker (?)");
    $stmt->bind_param("s", $bnavn);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($finnes);
    $stmt->fetch();
    $stmt->close();

    if ($finnes == '1') {
        echo 'Brukernavnet <b>' . $bnavn . '</b> er tatt!';
        exit();
    }
    else {
        exit();
    }
}

if (isset($_GET['epost']) && $_GET['epost'] != "") {
    $epost = $_GET['epost'];

    $sql = "CALL finn_epost (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $epost);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row['@epost_finnes'] == '1') {
        echo 'Eposten <b>' . $epost . '</b> er tatt!';
        exit();
    }
    else {
        exit();
    }

}

