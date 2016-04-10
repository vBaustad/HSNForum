<?php
require_once('includes/db_connect.php');
require_once('includes/functions.php');
require_once('includes/header.php');
require_once('index.php');

// Enkel sjekk for tom GET
if (empty($_GET['epost']) || empty($_GET['nokkel'])) {
    // Mangler ?= informasjon
}

if (isset($_GET['epost']) && isset($_GET['nokkel'])) {
    $nokkel = $_GET['nokkel'];
    $epost = $_GET['epost'];

    $sql = "SELECT nokkel, bruker_mail, bruker_id, dato FROM bekreft WHERE `bruker_mail` = ? AND `nokkel` = ? ORDER BY dato DESC LIMIT 1";
    $stmt_hent_bekreft = $conn->prepare($sql);
    $stmt_hent_bekreft->bind_param("ss", $epost, $nokkel);
    $stmt_hent_bekreft->execute();
    $stmt_hent_bekreft->store_result();
    $stmt_hent_bekreft->bind_result($sql_nokkel, $sql_bruker_mail, $sql_bruker_id, $sql_bruker_dato);
    $stmt_hent_bekreft->fetch();

    // Hvis vi fikk et treff på nokkel og email
    if ($stmt_hent_bekreft->num_rows == 1) {
        $bruker_id = $sql_bruker_id;

        // Setter bruker_aktiv til 1
        $sql = "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = ?";
        $stmt_bruker = $conn->prepare($sql);
        $stmt_bruker->bind_param("i", $bruker_id);
        $stmt_bruker->execute();

        $stmt_sjekk_aktiv = $conn->prepare("SELECT bruker_aktiv FROM bruker WHERE bruker_id = ?");
        $stmt_sjekk_aktiv->bind_param("i", $sql_bruker_id);
        $stmt_sjekk_aktiv->execute();
        $stmt_sjekk_aktiv->store_result();
        $stmt_sjekk_aktiv->bind_result($stmt_hent_bekreft_aktiv);
        $stmt_sjekk_aktiv->fetch();

        // Bruker er aktivert, vi kan trygt slette bruker i bekref tabellen
        if ($stmt_hent_bekreft_aktiv == '1') {

            /* TODO: DELETE AFTER TRIGGER i 'forum.bruker' kan ta seg av dette!? */
            if ($stmt_delete = $conn->prepare("DELETE FROM bekreft WHERE bruker_id = ?")) {
                $stmt_delete->bind_param("i", $sql_bruker_id);
                $stmt_delete = $conn->prepare($sql);
                $stmt_delete->execute();
                $stmt_delete->close();

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
            $stmt_hent_bekreft->close();
        }
        $stmt_bruker->close();
    } // Nokkelen og/eller eposten stemmer ikke
    else {
        echo "INGEN INFO!";
    }
    $stmt_hent_bekreft->close();
}
require_once ('includes/footer.php');