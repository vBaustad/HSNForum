<?php
require_once '/php/functions.php';
require_once '/php/db_connect.php';

//setup some variables/arrays
$action = array();
$action['result'] = null;
$text = array();

if(isset($_POST['registrer-btn'])) {

  // Forhindrer SQL injection med escape string
  $brukernavn = mysqli_real_escape_string($conn, $_POST["brukernavn_reg"]);
  $fornavn = mysqli_real_escape_string($conn, $_POST["fornavn_reg"]);
  $etternavn = mysqli_real_escape_string($conn, $_POST["etternavn_reg"]);
  $passord = mysqli_real_escape_string($conn, $_POST["pass_reg"]);
  $epost = mysqli_real_escape_string($conn, $_POST["epost_reg"]);
  $dato = date("Y-m-d G:i:s");
  $level = 1;
  $aktiv = 0;
  
  if($action['result'] != 'error') {
    // Genererer passord
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");
      
    // Setter riktig charset for
    $conn->set_charset("utf8");
    // Legg til bruker i databasen
    $leggtil_bruker = mysqli_query($conn, "INSERT INTO bruker (bruker_id, bruker_navn, bruker_pass, bruker_mail, bruker_dato, bruker_level, bruker_aktiv, bruker_fornavn, bruker_etternavn)
          VALUES(NULL, '$brukernavn', '$passordhash', '$epost', '$dato', '$level', '$aktiv', '$fornavn', '$etternavn')");
    
    if($leggtil_bruker) {
      // Henter den nye IDen som nettopp ble laget i databasen
      $bruker_id = mysqli_insert_id($conn);
      
      // Genererer en random nøkkel
      $nokkel = $brukernavn . $epost . date('mYG');
      $nokkel = md5($nokkel);
      
      // Legger brukeren til i "bekreft" tabellen
      $bekreft = mysqli_query($conn, "INSERT INTO bekreft (nokkel, bruker_mail, bruker_id)
                              VALUES('$nokkel','$epost', '$bruker_id')");
      
      if($bekreft) {
        //Sender informasjon til send_mail
        $info = array(
          'brukernavn' => $brukernavn,
          'epost' => $epost,
          'nokkel' => $nokkel,
          'fornavn' => $fornavn);
      
        //Prøver å sende eposten
        if(send_email($info)) {
          echo <<<_END
              <script type='text/javascript'>
                $(document).ready(function() {
                  $('.registrer-box-mail-sendt').show();
                });
              </script>
_END;
        
        } else {
          echo "Mail ikke sendt!";
        }
      
      } else {

      }

    } else {

    }
  }
  $action['text'] = $text;
}
?>



