<?php
	// db_connect.php
	$conn = mysql_connect('localhost', 'root', '') or die("Kunne ikke koble til databassen!");
	mysql_select_db('forum') or die("Kunne ikke finne databasen. Pass på du har skrevet riktig!!");
?>