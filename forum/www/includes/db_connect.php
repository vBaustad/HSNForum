<?php
session_start();

setlocale(LC_TIME, 'norwegian');
define("CHARSET", "iso-8859-1");

$conn = mysqli_connect("localhost", "root", "", "forum");
$conn->set_charset("utf8");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

