<?php
require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/db_connect.php');

if (isset($_POST['logginn-btn'])) {
    // Forhindrer SQL injection med escape string -- Bytt til prepared statement! - Trenger ikke prepared statement her, da queryen kun er SELECT
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passord = mysqli_real_escape_string($conn, $_POST["passord_logginn"]);

    $hentbrukernavn = mysqli_real_escape_string($conn, $_POST["brukernavn_logginn"]);
    $brukernavn = ucfirst($hentbrukernavn);
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $finn_bruker = "SELECT bruker_id, bruker_pass, bruker_navn, bruker_aktiv, bruker_level FROM bruker
                                       WHERE `bruker_navn` LIKE ?";
    $stmt = $conn->prepare($finn_bruker);
    $stmt->bind_param("s", $brukernavn);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    // Bruker finnes
    if ($res->num_rows == 1) {
        // Passord stemmer med bruker
        if ($row['bruker_pass'] == $passordhash && $row['bruker_navn'] == $brukernavn) {
            // Hvis bruker_aktiv = 1
            if ($row['bruker_aktiv'] == '1') {
                sistAktiv($conn, $row["bruker_id"]);

                $_SESSION['innlogget'] = true;
                $_SESSION["bruker_navn"] = $row["bruker_navn"];
                $_SESSION["bruker_id"] = $row["bruker_id"];
                $_SESSION["bruker_level"] = $row["bruker_level"];

                // Tilbake til index.php
                header("Location: index.php");
                die();
            }
        }
    }


    require_once(__DIR__ . '/index.php');
    require_once(__DIR__ . '/includes/header.php');

    // Bruker finnes ikke
    if ($finnnes = $res->num_rows == 0) {
        echo <<<_END
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#logginn-box").show();
                    document.getElementById('ikkeAktiv').style.display = "block";
                    document.getElementById('brukernavn_logginn').value = "$brukernavn";
                    document.getElementById('ikkeAktiv').innerHTML = "Kunne ikke finne bruker $brukernavn!";
                    document.getElementById('brukernavn_logginn').style.border = 'solid 3px #e35152';
                });
            </script>
_END;
    }

    // Feil passord
    if ($row['bruker_pass'] != $passordhash && $row['bruker_navn'] == $brukernavn) {
        echo <<<_END
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#logginn-box").show();
                    document.getElementById('feilPass').style.display = "block";
                    document.getElementById('brukernavn_logginn').value = "$brukernavn";
                    document.getElementById('feilPass').innerHTML = "Feil passord. Prøv igjen!";
                    document.getElementById('passord_logginn').style.border = 'solid 3px #e35152';
                });
            </script>
_END;
    }

    // Hvis bruker IKKE er aktiv
    if ($row['bruker_aktiv'] == '0') {
        echo <<<_END
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#logginn-box").show();
                    document.getElementById('ikkeAktiv').style.display = "block";
                    document.getElementById('brukernavn_logginn').value = "$brukernavn";
                    document.getElementById('ikkeAktiv').innerHTML = "Brukeren $brukernavn er ikke aktivert. Sjekk eposten din!";
                    document.getElementById('brukernavn_logginn').style.border = 'solid 3px #e35152';
                });
            </script>
_END;
    }
}