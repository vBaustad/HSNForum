<?php
require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/db_connect.php');
require_once ('index.php');
require_once ('includes/header.php');

if (isset($_POST['logginn-btn'])) {
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passord = $_POST["passord_logginn"];

    $hentbrukernavn = $_POST["brukernavn_logginn"];
    $brukernavn = ucfirst($hentbrukernavn);
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $finn_bruker = "SELECT bruker_id, bruker_pass, bruker_navn, bruker_aktiv, bruker_level FROM bruker
                                       WHERE `bruker_navn` LIKE ?";
    $stmt = $conn->prepare($finn_bruker);
    $stmt->bind_param("s", $brukernavn);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($sql_bruker_id, $sql_bruker_pass, $sql_bruker_navn, $sql_bruker_aktiv, $sql_bruker_level);
    $stmt->fetch();
    $numrows = $stmt->num_rows;

    // Bruker finnes
    if ($numrows == 1) {
        // Passord stemmer med bruker
        if ($sql_bruker_pass == $passordhash && $sql_bruker_navn == $brukernavn) {
            // Hvis bruker_aktiv = 1. Sett sessions og logg inn!
            if ($sql_bruker_aktiv == '1') {
                setAktiv($conn, $sql_bruker_id);

                $_SESSION['innlogget'] = true;
                $_SESSION["bruker_navn"] = $sql_bruker_navn;
                $_SESSION["bruker_id"] = $sql_bruker_id;
                $_SESSION["bruker_level"] = $sql_bruker_level;

                // Tilbake til index.php
                die("<script>location.href = 'index.php'</script>");
            }
            // Bruker er ikke aktiv
            else {
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
        // Feil passord
        else {
            echo "passord matcher ikke";
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
    }
    // Bruker finnes ikke
    else {
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
}