<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
$sql = "SELECT COUNT(innlegg_id), max(innlegg_dato) AS sisteInnlegg,
                        ( SELECT bruker_id WHERE innlegg_dato = max(innlegg_dato) ) as Bruker_id
                        FROM innlegg";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res_antinnlegg = $stmt->get_result();
$row_antinnlegg = $res_antinnlegg->fetch_assoc();
$stmt->close();

?>