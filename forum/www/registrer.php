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

    if (!valBNavn($brukernavn)) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#bnavnErr').show();
        });
      </script>
_END;
    }

    if (!valNavn($fornavn)) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#fnavnErr').show();
        });
      </script>
_END;
    }

    if (!valNavn($etternavn)) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#enavnErr').show();
        });
      </script>
_END;
    }

    if (!valEpost($epost)) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#epostErr').show();
        });
      </script>
_END;
    }

    if (!valPass($passord)) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#passErr').show();
        });
      </script>
_END;
    }

    if ($passord !== $passordTo) {
        $err .= 1;
        echo <<<_END
      <script type='text/javascript'>
        $(document).ready(function() {
          $('#passTwoErr').show();
        });
      </script>
_END;
    }

    /* Sjekekr om alt er OK og deretter legger til ny bruker */
    if ($err === 0) {
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
                    document.getElementById("feilkode1").style.display = "block";
                </script>
_END;
                }
            } // Feilkode 2
            else {
                echo <<<_END
                <script type='text/javascript'>
                    document.getElementById("feilkode2").style.display = "block";
                </script>
_END;

            }
        } // Feilkode 3
        else {
            echo <<<_END
                <script type='text/javascript'>
                    document.getElementById("feilkode2").style.display = "block";
                </script>
_END;

        }
    } // Feilkode 4
    else {
        echo <<<_END
                <script type='text/javascript'>
                    document.getElementById("feilkode2").style.display = "block";
                </script>
_END;

    }
}

/*
    -- Feilkoder --
    1: Eposten ble ikke sendt - Mest sansynlig feil på klient side.
    2: Informasjon ble ikke sendt ordentlig til funksjonen send_email
    3: Klarte ikke legge til bruker i tabellen "bekreft"
    4: Klarte ikke legge til bruker i tabellen "bruker"
*/
?>

