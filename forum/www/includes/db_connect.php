<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once 'functions.php';
session_start();

setlocale(LC_TIME, 'norwegian');
define("CHARSET", "iso-8859-1");

$conn = mysqli_connect("localhost", "root", "", "forum");
$conn->set_charset("utf8");

// Brukeren blir aktiv
if (isset($_SESSION['bruker_id'])) {
    setAktiv($conn, $_SESSION['bruker_id']);
}

// Check connection
if (mysqli_connect_errno()) {
    echo "Kunne ikke koble opp til databasen: " . mysqli_connect_error();
}