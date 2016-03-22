<?php
require_once 'includes/functions.php';
require_once 'includes/db_connect.php';
require_once 'index.php';
require_once 'includes/header.php';

function valBNavn($verdi)
{
    return preg_match("/^[A-Za-z0-9]+$/", $verdi);
}

function valNavn($verdi)
{
    return preg_match("/^[a-zA-Z]+$/", $verdi);
}

function valEpost($verdi)
{
    return preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $verdi);
}

function valPass($verdi)
{
    return ((strlen($verdi) >= 6 || strlen($verdi) <= 30)
        && preg_match("/[a-z]+/", $verdi)
        && preg_match("/[A-Z]+/", $verdi)
        && preg_match("/[0-9]+/", $verdi));
}

if (isset($_POST['registrer-btn'])) {

    // Forhindrer SQL injection med escape string
    $brukernavn = mysqli_real_escape_string($conn, $_POST["brukernavn_reg"]);
    $fornavn = mysqli_real_escape_string($conn, $_POST["fornavn_reg"]);
    $etternavn = mysqli_real_escape_string($conn, $_POST["etternavn_reg"]);
    $epost = mysqli_real_escape_string($conn, $_POST["epost_reg"]);
    $passord = mysqli_real_escape_string($conn, $_POST["pass_reg"]);
    $passordTo = mysqli_real_escape_string($conn, $_POST["pass_two_reg"]);
    $dato = date("Y-m-d G:i:s");
    $level = 1;
    $aktiv = 0;

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

    /* Sjekker om alt er OK og deretter legger til ny bruker */
    if ($err == 0) {
        // Genererer passord
        $salt1 = 'dkn?';
        $salt2 = '$l3*!';
        $passordhash = hash('ripemd160', "$salt1$passord$salt2");

        // Legg til bruker i databasen
        $sql = mysqli_query($conn, "INSERT INTO bruker (bruker_id, bruker_navn, bruker_pass, bruker_mail, bruker_dato, bruker_level, bruker_aktiv, bruker_fornavn, bruker_etternavn)
            VALUES(NULL, '$brukernavn', '$passordhash', '$epost', '$dato', '$level', '$aktiv', '$fornavn', '$etternavn')");

        if ($sql) {
            // Henter den nye IDen som nettopp ble laget i databasen
            $bruker_id = mysqli_insert_id($conn);

            // Genererer en random nøkkel
            $nokkel = $brukernavn . $epost . date('mYG');
            $nokkel = md5($nokkel);

            // Legger brukeren til i "bekreft" tabellen
            $bekreft = mysqli_query($conn, "INSERT INTO bekreft (nokkel, bruker_mail, bruker_id)
                                VALUES('$nokkel','$epost', '$bruker_id')");

            if ($bekreft) {
                //Sender informasjon til send_mail
                $info = array(
                    'brukernavn' => $brukernavn,
                    'epost' => $epost,
                    'nokkel' => $nokkel,
                    'fornavn' => $fornavn);

                // Hvis alt er OK -> Send epost
                if (send_email($info)) {
                    echo <<<_END
                        <script type='text/javascript'>
                          $(document).ready(function() {
                            $('.registrer-box-mail-sendt').show();
                          });
                        </script>
_END;

                } // Feilkode 1
                else {
                    echo <<<_END
                        <script type='text/javascript'>
                            $(document).ready(function() {
                                $("#registrer-feil").show();
                                $("#feilkode1").css("display", "block");
                            })
                        </script>
_END;
                }
            } // Feilkode 2
            else {
                echo <<<_END
                    <script type='text/javascript'>
                        $(document).ready(function() {
                            $("#registrer-feil").show();
                            $("#feilkode2").css("display", "block");
                        })
                    </script>
_END;

            }
        } // Feilkode 3
        else {
            echo <<<_END
                <script type='text/javascript'>
                    $(document).ready(function() {
                        $("#registrer-feil").show();
                        $("#feilkode3").css("display", "block");
                    })
                </script>
_END;

        }
    }
}

/*
    -- Feilkoder -- STEMMER IKKE??!!
    1: Eposten ble ikke sendt - Mest sansynlig feil på klient side.
    2: Klarte ikke legge til bruker i tabellen "bekreft"
    3: Klarte ikke legge til bruker i tabellen "bruker"
*/
?>

