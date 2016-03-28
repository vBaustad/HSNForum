<?php
require_once 'db_connect.php';

$sql = "INSERT INTO bruker (bruker_id, bruker_navn, bruker_pass, bruker_mail, bruker_dato, bruker_level, bruker_aktiv, bruker_fornavn, bruker_etternavn)
            VALUES(NULL, ?, ?, ?, NOW(), ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiiss", $navn, $pass, $mail, $level, $aktiv, $fnavn, $enanv);

$id = 'NULL';
$navn = 'okaka';
$pass = 'lol';
$mail = 'lol@lol.no';
$dato = 'NOW';
$level = '1';
$aktiv = '0';
$fnavn = 'ole';
$enanv = $_GET['enavn'];

$stmt->execute();


