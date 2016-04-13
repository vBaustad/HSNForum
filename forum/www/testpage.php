<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$bnavn = "admin";

$sql = "CALL finn_bruker (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bnavn);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($finnes);
$stmt->fetch();
$stmt->close();

echo $finnes;

if ($finnes == '1') {
    echo 'Brukernavnet <b>' . $bnavn . '</b> er tatt!';
    exit();
}

?>


