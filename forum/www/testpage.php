<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$pagedata_innlegg_id = 29;
$traad_id = 3;


echo harLikt($conn, "innlegg", $pagedata_innlegg_id, $traad_id, $_SESSION['bruker_id']);


?>


