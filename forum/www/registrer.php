<?php
require_once 'includes/functions.php';
require_once 'includes/db_connect.php';
require_once 'index.php';
require_once 'includes/header.php';

function valBNavn($verdi) {
    return preg_match("/^[A-Za-z0-9]+$/", $verdi);
}

function valNavn($verdi) {
    return preg_match("/^[a-zA-ZøæåØÆÅ]+$/", $verdi);
}

function valEpost($verdi) {
    return preg_match("/^(([0-9]{6})+@+(student)+.|([a-zA-Z]+.+[a-zA-Z]+@))+(hit|usn|hbv)+.(no)$/", $verdi);
}

function valPass($verdi) {
    return ((strlen($verdi) >= 6 || strlen($verdi) <= 30)
        && preg_match("/[a-z]+/", $verdi)
        && preg_match("/[A-Z]+/", $verdi)
        && preg_match("/[0-9]+/", $verdi));
}

if (isset($_POST['registrer-btn'])) {

    $brukernavn = $_POST["brukernavn_reg"];
    $fornavn = $_POST["fornavn_reg"];
    $etternavn = $_POST["etternavn_reg"];
    $epost = $_POST["epost_reg"];
    $passord = $_POST["pass_reg"];
    $passordTo = $_POST["pass_two_reg"];

    $err = 0;

    if (!valBNavn($brukernavn) || !valNavn($fornavn) || !valNavn($etternavn) || !valEpost($epost) || !valPass($passord) || $passord !== $passordTo ) {
        echo <<<_END
        <script type='text/javascript'>
            $(document).ready(function() {
                $("#registrer-box").show();
                $(".feilkode").css("color", "red");
            });
        </script>
_END;
    }

    if (!valBNavn($brukernavn)) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('bnavnErr').style.display = "block";
            document.getElementById('brukernavn_reg').style.border = 'solid 3px #e35152';
        </script>
_END;
    }

    if (!valNavn($fornavn)) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('fnavnErr').style.display = "block";
            document.getElementById('fornavn_reg').style.border = 'solid 3px #e35152'; 
        </script>
_END;
    }

    if (!valNavn($etternavn)) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('enavnErr').style.display = "block";
            document.getElementById('etternavn_reg').style.border = 'solid 3px #e35152';
        </script>
_END;
    }

    if (!valEpost($epost)) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('epostErr').style.display = "block";
            document.getElementById('epost_reg').style.border = 'solid 3px #e35152';
        </script>
_END;
    }

    if (!valPass($passord)) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('passErr').style.display = "block";
            document.getElementById('pass_reg').style.border = 'solid 3px #e35152';
        </script>
_END;
    }

    if ($passord !== $passordTo) {
        $err .= 1;
        echo <<<_END
        <script type='text/javascript'>
            document.getElementById('passTwoErr').style.display = "block";
            document.getElementById('pass_two_reg').style.border = 'solid 3px #e35152';
        </script>
_END;
    }

    $stmt_epost = $conn->prepare("SELECT bruker_mail FROM bekreft WHERE bruker_mail = ?");
    $stmt_epost->bind_param("s", $epost);
    $stmt_epost->execute();
    $stmt_epost->bind_result($stmt_epost_res);
    $stmt_epost->store_result();

    /* Sjekker om alt er OK og deretter legger til ny bruker */
    if ($err == 0 && $stmt_epost->num_rows == 0) {
        // Genererer passord
        $salt1 = 'dkn?';
        $salt2 = '$l3*!';
        $passordhash = hash('ripemd160', "$salt1$passord$salt2");

        // Legg til bruker i databasen
        if ($stmt = $conn->prepare("INSERT INTO bruker (bruker_navn, bruker_pass, bruker_mail, bruker_dato, bruker_level, bruker_aktiv, bruker_fornavn, bruker_etternavn)
                                    VALUES(?, ?, ?, NOW(), '1', '0', ?, ?)")) {
            $stmt->bind_param("sssss", $brukernavn, $passordhash, $epost, $fornavn, $etternavn);
            $stmt->execute();

            // Henter den nye IDen som nettopp ble laget i databasen
            $bruker_id = mysqli_insert_id($conn);

            // Genererer en random nøkkel
            $nokkel = $brukernavn . $epost . date('mYG');
            $nokkel = md5($nokkel);

            // Legger brukeren til i "bekreft" tabellen
            if ($bekreft = $conn->prepare("INSERT INTO bekreft (nokkel, bruker_mail, bruker_id, dato)
                                           VALUES(?, ?, ?, NOW())")) {
                $bekreft->bind_param("ssi", $nokkel, $epost, $bruker_id);
                $bekreft->execute();

                //Forbreder informasjon som skal sendes til send_mail
                $info = array(
                    'brukernavn' => $brukernavn,
                    'epost' => $epost,
                    'nokkel' => $nokkel,
                    'fornavn' => $fornavn);

                // Hvis alt er OK -> Send epost
                if (send_email($info)) {
                    echo "<script type='text/javascript'>";
                    echo    "$(document).ready(function() {";
                    echo        "$('#registrer-mail-sendt').show();";
                    echo    "})";
                    echo "</script>";

                } // Feilkode 1
                else {
                    echo "<script type='text/javascript'>";
                    echo    "feilmelding('#registrer-feil', 'Feilkode: 1');";
                    echo "</script>";
                }
            } // Feilkode 2
            else {
                echo "<script type='text/javascript'>";
                echo    "feilmelding('#registrer-feil', 'Feilkode: 2');";
                echo "</script>";

            }
        } // Feilkode 3
        else {
            echo "<script type='text/javascript'>";
            echo    "feilmelding('#registrer-feil', 'Feilkode: 3');";
            echo "</script>";

        }
    }
}

/*
    -- Feilkoder --
    1: Eposten ble ikke sendt - Mest sansynlig feil på klient side.
    2: Klarte ikke legge til bruker i tabellen "bekreft"
    3: Klarte ikke legge til bruker i tabellen "bruker"
*/
