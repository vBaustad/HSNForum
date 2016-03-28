<?php
require_once(__DIR__ . '/db_connect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/../index.php');
require_once(__DIR__ . '/header.php');

// Enkel sjekk for tom GET
if (empty($_GET['epost']) || empty($_GET['nokkel'])) {
    // Mangler ?= informasjon
}

if (!empty($_GET['epost']) || !empty($_GET['nokkel'])) {

    $sql = "SELECT * FROM bekreft WHERE `bruker_mail` = ? AND `nokkel` = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $epost, $nokkel);

    $nokkel = $_GET['nokkel'];
    $epost = $_GET['epost'];
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


    // Hvis vi fikk et treff på nokkel og email
    if ($result->num_rows == 1) {

        $sql = "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bruker_id);

        $bruker_id = $row['bruker_id'];
        $stmt->execute();
        $stmt->close();

        $sql = mysqli_query($conn, "SELECT bruker_aktiv FROM bruker WHERE bruker_id = '$bruker_id'");
        $row = $sql->fetch_assoc();

        // Bruker er aktivert, vi kan trygt slette bruker i bekref tabellen
        if ($row['bruker_aktiv'] == '1') {
            $sql = mysqli_query($conn, "DELETE FROM bekreft WHERE bruker_id = '$bruker_id'");

            if ($sql) {
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
        
    } // Nokkelen og/eller eposten stemmer ikke
    else {
        echo "INGEN INFO!";
    }
}































/*if (!empty($_GET['epost']) || !empty($_GET['nokkel'])) {

    $epost = $_GET['epost'];
    $nokkel = $_GET['nokkel'];

    $sql = "SELECT * FROM bekreft WHERE `bruker_mail` = ? AND `nokkel` = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $epost, $nokkel);
    $stmt->execute();

    // Hvis vi fikk et treff på nokkel og email
    if ($stmt->num_rows == 1) {

        while ($stmt->fetch()) {
            $update_users = mysqli_query($conn, "UPDATE bruker SET bruker_aktiv = 1 WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1");

            $delete = mysqli_query($conn, "DELETE FROM bekreft WHERE bruker_id = '$bekreft_info[bruker_id]' LIMIT 1");
        }

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
            echo "feil!";
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
}*/

require_once(__DIR__ . '/footer.php');