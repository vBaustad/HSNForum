<?php
require_once '/php/functions.php';
require_once '/php/db_connect.php';

// Fjerner alle elementer som ikke skal vises med en gang
echo <<<_END
  <script type='text/javascript'>
    $(document).ready(function() {
      $('.registrer-box-mail-sendt').hide();
    });
  </script>
_END;

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
          $action['result'] = 'error';
          array_push($text,'Could not send confirm email');
        }
      
      } else {
        $action['result'] = 'error';
        array_push($text,'Confirm row was not added to the database. Reason: ' . mysql_error());
      }

    } else {
      $action['result'] = 'error';
      array_push($text,'User could not be added to the database. Reason: ' . mysql_error());
    
    }
  }
  $action['text'] = $text;
}
?>



<!-- Mail sendt -->
<div class="registrer-box-mail-sendt">
  <div class="popup-header center">
    <h2 class="popup-header-text icon-mail"> Mail sendt!</h2>
  </div>

  <div class="popup-container center">
    <h1>Takk for din registrering!</h1>
    <p>Fullfør registreringen ved å sjekke eposten din.</p>
    <p>Du kan nå trykt lukke dette vinduet.</p>
    <button form="registrer" name="button-avbryt" type="submit" class="popup-registrer-button-lukk pull-right"><span class=""></span> Lukk</button>
  </div>
</div>

<!-- Laster... (sender mail) -->
<div class="registrer-box-loading">
  <img class="opptatt" src="img/opptatt.gif">
</div>

<!-- REGISTER BOX -->
<div id="registrer-box">
  <div class="popup-header center">
    <h2 class="popup-header-text icon-user"> Registrer deg!</h2>
  </div>

  <div class="popup-container center">
    <form name="registrer" class="popup-registrer-form" method="post" action="">
      <div class="popup-divider">
        <input name="brukernavn_reg" type="text" class="popup-input valid" placeholder="Brukernavn"><span></span>
      </div>

      <div class="popup-divider" >
        <input name="fornavn_reg" type="text" class="popup-input double input-pull-left" placeholder="Fornavn">
        <input name="etternavn_reg" type="text" class="popup-input double pull-right" placeholder="Etternavn">
      </div>

      <div class="popup-divider" style="clear: both;">
        <input name="epost_reg" type="text" class="popup-input" placeholder="Epost-adresse">
      </div>

      <div class="popup-divider">
        <input name="pass_reg" type="password" class="popup-input" placeholder="Passord">
      </div>

      <div class="popup-divider">
        <input name="pass_two_reg" type="password" class="popup-input" placeholder="Gjenta passord">
      </div>
      <input type="submit" value="Fullfør" name="registrer-btn" class="popup-registrer-button pull-left">
      <input form="registrer" value="Abvryt"name="button-avbryt" type="submit" class="popup-registrer-button-avbryt pull-right">
  </div>
</div>