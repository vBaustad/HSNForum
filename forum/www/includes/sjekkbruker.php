<?php
require_once(__DIR__ . '/../includes/db_connect.php');

if (isset($_GET['bnavn']) && $_GET['bnavn'] != "") {

    $bnavn = $_GET['bnavn'];

    $sql = mysqli_query($conn, "CALL finn_bruker ('$bnavn')");
    $row = mysqli_fetch_assoc($sql);

    if ($row['@bruker_finnes'] == '1') {
        echo $bnavn . '</strong> er tatt!';
        exit();
    }
    else {
        echo $bnavn . ' er ledig!';
        exit();
    }

}


?>

