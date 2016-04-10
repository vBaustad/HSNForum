<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';


$kat_id = 1;

$finnkatnavn = $conn->prepare("SELECT kat_navn FROM kategori WHERE kat_id = ?");
$finnkatnavn->bind_param("i", $kat_id);
$finnkatnavn->execute();
$finnkatnavn->store_result();
$finnkatnavn->bind_result($sql_kat_navn);
$finnkatnavn->fetch();

echo $sql_kat_navn;