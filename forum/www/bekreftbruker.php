<?php
require_once ('includes/db_connect.php');
require_once ('includes/functions.php');
require_once ('index.php');
require_once ('includes/header.php');

if (isset($_GET['epost']) || isset($_GET['nokkel'])) {
    $nokkel = $_GET['nokkel'];
    $epost = $_GET['epost'];

    $stmt_bekreft = $conn->prepare("SELECT nokkel, bruker_mail, bruker_id, dato FROM bekreft WHERE `bruker_mail` = ? AND `nokkel` = ? LIMIT 1");
    $stmt_bekreft->bind_param("ss", $epost, $nokkel);
    $stmt_bekreft->execute();
    $stmt_bekreft->bind_result($nokkel, $bruker_mail, $bruker_id, $dato);
    $stmt_bekreft->store_result();
    $stmt_bekreft->fetch();

    // Hvis vi fikk et treff på nokkel og email
    if ($stmt_bekreft->num_rows == 1) {

        $sql = "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bruker_id);
        $stmt->execute();
        $stmt->close();
        $stmt->close();

        $stmt_bruker = $conn->prepare("SELECT bruker_aktiv FROM bruker WHERE bruker_id = ?");
        $stmt_bruker->bind_param("i", $bruker_id);
        $stmt_bruker->execute();
        $stmt_bruker->bind_result($bruker_aktiv);
        $stmt_bruker->store_result();
        $stmt_bruker->fetch();

        // Bruker er aktivert, vi kan trygt slette bruker i bekref tabellen
        if ($bruker_aktiv == '1') {
            if ($stmt_slett = $conn->prepare("DELETE FROM bekreft WHERE bruker_id = ?")) {
                $stmt_slett->bind_param("i", $bruker_id);
                $stmt_slett->execute();
                $stmt_slett->close();
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
        }
        $stmt_bruker->close();
    } // Nokkelen og/eller eposten stemmer ikke
    else {
        exit();
    }

}

require_once ('includes/footer.php');