<?php
require_once 'includes/db_connect.php';


$bruker = "Admin";

$sql = mysqli_query($conn, "SELECT bruker_navn, bruker_dato FROM bruker WHERE bruker_navn = '$bruker'");
$row = mysqli_fetch_assoc($sql);

echo $row['bruker_dato'];