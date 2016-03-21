<?php // bekreftbruker.php
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'header.php';
require_once 'localhost/forum/www/index.php';

// Enkel skekk for tom GET
if (empty($_GET['epost']) || empty($_GET['nokkel'])) {
    // Mangler ?= informasjon
}

if (!empty($_GET['epost']) || !empty($_GET['nokkel'])) {

    $epost = mysqli_real_escape_string($conn, $_GET['epost']);
    $nokkel = mysqli_real_escape_string($conn, $_GET['nokkel']);

    // Spørring som leter etter nøkkelen og eposten
    $sjekk_entry = mysqli_query($conn, "SELECT * FROM bekreft WHERE `bruker_mail` = '$epost' AND `nokkel` = '$nokkel' LIMIT 1");

    $row_count = $sjekk_entry->num_rows;

    // Hvis vi fikk et treff på nokkel og email
    if ($row_count == 1) {

        $bekreft_info = $sjekk_entry->fetch_assoc();

        $update_users = mysqli_query($conn, "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1");

        $delete = mysqli_query($conn, "DELETE FROM bekreft WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1");

        if ($update_users) {
            echo <<<_END
			<script type='text/javascript'>
				$(document).ready(function() {
					$('.registrer-box-success').show();
				});
			</script>
_END;
        } // Feil. Bruker kunne ikke oppdateres
        else {
            echo <<<_END
			<script type='text/javascript'>
			  $(document).ready(function() {
			    $('.registrer-box-fail').show();
			  });
			</script>
_END;
        }

    } // Nokkelen og/eller eposten stemmer ikke 
    else {
        echo "INGEN INFO!";
    }
}
?>
