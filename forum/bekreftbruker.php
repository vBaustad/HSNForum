<?php // bekreftbruker.php
require_once 'php/db_connect.php';
require_once 'php/functions.php';
require_once 'forum.php';
require_once 'header.php';

// Skjul alle elementer vi ikke trenger
echo <<<_END
<script type='text/javascript'>
$(document).ready(function() {
	$('.registrer-box-success').hide();
	$('.registrer-box-fail').hide();
	$('.registrer-box-mail-sendt').hide();
	$('.registrer-box-loading').hide();
	$('#registrer-box').hide();
});
</script>
_END;

//setup some variables
$action = array();
$action['result'] = null;
 
// Enkel skekk for tom GET
if(empty($_GET['epost']) || empty($_GET['nokkel'])) {
	// Mangler ?= informasjon
}
         
if(!empty($_GET['epost']) || !empty($_GET['nokkel'])) {

	$epost = mysqli_real_escape_string($conn, $_GET['epost']);
	$nokkel = mysqli_real_escape_string($conn, $_GET['nokkel']);
     
	// Spørring som leter etter nøkkelen og eposten
	$sjekk_entry = mysqli_query($conn, "SELECT * FROM bekreft WHERE `bruker_mail` = '$epost' AND `nokkel` = '$nokkel' LIMIT 1") or die(mysql_error());

	$row_count = $sjekk_entry->num_rows;
     
	// Hvis vi fikk ett treff på nokkel og email
	if($row_count > 0 && $row_count < 2){
		//get the confirm info
		// $bekreft_info = mysqli_fetch_assoc($sjekk_entry);

		$bekreft_info = $sjekk_entry->fetch_assoc();

		//confirm the email and update the users database
		$update_users = mysqli_query($conn, "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1") or die(mysql_error());
		//delete the confirm row
		$delete = mysqli_query($conn, "DELETE FROM bekreft WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1") or die(mysql_error());

		// Bruker lagt till aktiv. Blir TRUE av en eller annen grunn?!
		if($update_users) {
			echo "HEY!";
			echo <<<_END
			<script type='text/javascript'>
				$(document).ready(function() {
					$('.registrer-box-success').show();
				});
			</script>
_END;
        }

        // Feil. Bruker kunne ikke oppdateres
        else {
        	echo <<<_END
			<script type='text/javascript'>
			  $(document).ready(function() {
			    $('.registrer-box-fail').hide();
			  });
			</script>
_END;
		}

	}

    // Nokkelen og/eller eposten stemmer ikke 
	else{
		echo "INGEN INFO!";
		$action['result'] = 'error';
		$action['text'] = 'The key and email is not in our database.';
	}
} 
else {
}
?>
<!-- Bruker laget -->
<div class="registrer-box-success">
  <div class="popup-header">
    <h2 class="popup-header-text icon-success"> Registrering fullført!</h2>
  </div>

  <div class="popup-container center">
    <h1>Bruker aktivert</h1>
    <p>Velkommen til HSN forum!</p>
    <p>Du kan nå logge deg inn.</p>
    <button form="registrer" name="button-avbryt" type="submit" class="popup-registrer-button-lukk pull-right"><span class=""></span> Lukk</button>
  </div>
</div>