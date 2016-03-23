<?php
require_once(__DIR__ . '/../includes/db_connect.php');

if (isset($_GET['bnavn']) && $_GET['bnavn'] != "") {

    $bnavn = mysqli_real_escape_string($conn, $_GET['bnavn']);

    $sql = mysqli_query($conn, "CALL finn_bruker ('$bnavn')");
    $row = mysqli_fetch_assoc($sql);

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

    $epost = mysqli_real_escape_string($conn, $_GET['epost']);

    $sql = mysqli_query($conn, "CALL finn_epost ('$epost')");
    $row = mysqli_fetch_assoc($sql);

    if ($row['@epost_finnes'] == '1') {
        echo 'Eposten <b>' . $epost . '</b> er tatt! Log inn?';
        exit();
    }
    else {
        echo 'Eposten <b>' . $epost . '</b> er ledig';
        exit();
    }

}


?>

