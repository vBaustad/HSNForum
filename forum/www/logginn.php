<?php
require_once 'includes/functions.php';
require_once 'includes/db_connect.php';

if (isset($_POST['logginn-btn'])) {
    // Forhindrer SQL injection med escape string
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passord = mysqli_real_escape_string($conn, $_POST["passord_logginn"]);

    $brukernavn = mysqli_real_escape_string($conn, $_POST["brukernavn_logginn"]);
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $finn_bruker = mysqli_query($conn, "SELECT bruker_id, bruker_navn, bruker_aktiv, bruker_level FROM bruker
                                       WHERE `bruker_navn` = '$brukernavn' AND `bruker_pass` = '$passordhash'");

    $aktiv_bruker = mysqli_query($conn, "SELECT bruker_aktiv FROM bruker 
                                        WHERE `bruker_navn` = '$brukernavn' AND `bruker_pass` = '$passordhash' AND `bruker_aktiv` = 1");
    $row_count = $aktiv_bruker->num_rows;

    // Bruker og passord matcher
    if ($finn_bruker) {
        // Hvis bruker_aktiv = 1
        if ($row_count = 1) {
            $_SESSION['innlogget'] = true;

            while ($row = $finn_bruker->fetch_assoc()) {
                $_SESSION["bruker_navn"] = $row["bruker_navn"];
                $_SESSION["bruker_id"] = $row["bruker_id"];
                $_SESSION["bruker_level"] = $row["bruker_level"];
            }
            header("Location: http://localhost/forum/www/");
            die();
        }
        // Bruker er ikke aktiv. Be bruker sjekke epost
        else {
            echo (" Bruker er IKKE aktiv   ");
        }
        echo (" bruker eksisterer!   ");
    }
    // Brukeren ble ikke funnet / Passordet er feil
    else {
        echo (" Noe gikk galt...   (");
    }
}