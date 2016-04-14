<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$pagedata_innlegg_id = 3;
$traad_id = 2;

echo getLikes($conn, null, $traad_id);

echo "<br>";

if (harLikt($conn, "traad", null, $traad_id, $_SESSION['bruker_id']) == false) {
    echo 'its false';
} else {
    echo 'its true';
}
