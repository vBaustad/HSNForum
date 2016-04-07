<?php
require_once(__DIR__ . '/../includes/db_connect.php');

if (isset($_GET['bnavn']) && $_GET['bnavn'] != "") {
    $bnavn = $_GET['bnavn'];

    $sql = "CALL finn_bruker (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bnavn);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row['@bruker_finnes'] == '1') {
        echo 'Brukernavnet <b>' . $bnavn . '</b> er tatt!';
        exit();
    }
    else {
        echo 'Brukernavnet <b>' . $bnavn . '</b> er ledig';
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
        echo 'Eposten <b>' . $epost . '</b> er ledig';
        exit();
    }

}

