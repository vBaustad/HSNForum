<?php // bekreftbruker.php
require_once 'php/db_connect.php';
require_once 'header.php';

//setup some variables
$action = array();
$action['result'] = null;
 
//quick/simple validation
if(empty($_GET['epost']) || empty($_GET['nokkel'])){
	echo "Vi mangler informasjon!";
    $action['result'] = 'error';
    $action['text'] = 'We are missing variables. Please double check your email.';          
}
         
if($action['result'] != 'error'){

	$epost = mysqli_real_escape_string($conn, $_GET['epost']);
	$nokkel = mysqli_real_escape_string($conn, $_GET['nokkel']);
     
	// Spørring som leter etter nøkkelen og eposten
	$sjekk_entry = mysqli_query($conn, "SELECT * FROM bekreft WHERE `bruker_mail` = '$epost' AND `nokkel` = '$nokkel' LIMIT 1") or die(mysql_error());

	$row_count = $sjekk_entry->num_rows;
     
	if($row_count > 0){
		echo "resultatet har ike null rader";
                 
		//get the confirm info
		// $bekreft_info = mysqli_fetch_assoc($sjekk_entry);

		$bekreft_info = $sjekk_entry->fetch_assoc();
         
		//confirm the email and update the users database
		$update_users = mysqli_query($conn, "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1") or die(mysql_error());
		//delete the confirm row
		$delete = mysqli_query($conn, "DELETE FROM bekreft WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1") or die(mysql_error());
         
        if($update_users) {
			echo "Vi klarte det!";
			$action['result'] = 'success';
			$action['text'] = 'User has been confirmed. Thank You!';
         
        }else{
			$action['result'] = 'error';
			$action['text'] = 'The user could not be updated Reason: '.mysql_error();;
        }
     
    }else{
     
        $action['result'] = 'error';
        $action['text'] = 'The key and email is not in our database.';
     
    }
}
?>