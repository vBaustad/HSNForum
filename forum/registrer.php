<?php
require_once '/php/functions.php';
require_once '/php/db_connect.php';

//setup some variables/arrays
$action = array();
$action['result'] = null;
$text = array();

if(isset($_POST['registrer-btn'])) {
  // Forhindrer SQL injection med escape string
  // NB! Prepared statements er en bedre løsning enn mysqli_real_escape_string
  $brukernavn = mysql_real_escape_string($_POST["brukernavn_reg"]);
  $fornavn = mysql_real_escape_string($_POST["fornavn_reg"]);
  $etternavn = mysql_real_escape_string($_POST["etternavn_reg"]);
  $passord = mysql_real_escape_string($_POST["pass_reg"]);
  $epost = mysql_real_escape_string($_POST["epost_reg"]);
  $dato = date("Y-m-d G:i:s");
  $level = 1;
  $aktiv = 0;
  
  if($action['result'] != 'error') {
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");
      
    // Legg til bruker i databasen
    $leggtil_bruker = mysql_query("INSERT INTO bruker (bruker_id, bruker_navn, bruker_pass, bruker_mail, bruker_dato, bruker_level, bruker_aktiv, bruker_fornavn, bruker_etternavn)
            VALUES(NULL, '$brukernavn', '$passordhash', '$epost', '$dato', '$level', '$aktiv', '$fornavn', '$etternavn')");
    
    if($leggtil_bruker) {
      echo "We added shit  ";
      // Henter den nye IDen som nettopp ble laget i databasen
      $bruker_id = mysql_insert_id();
      
      // Genererer en random nøkkel
      $nokkel = $brukernavn . $epost . date('mYG');
      $nokkel = md5($nokkel);
      
      // Legger brukeren til i "bekreft" tabellen
      $bekreft = mysql_query("INSERT INTO bekreft (nokkel, bruker_mail, bruker_id)
                              VALUES('$nokkel','$epost', '$bruker_id')");
      
      if($bekreft) {
        //Sender informasjon til funksjonen
        $info = array(
          'brukernavn' => $brukernavn,
          'epost' => $epost,
          'nokkel' => $nokkel,
          'fornavn' => $fornavn);
      
        //Prøver å sende eposten
        if(send_email($info)){
          echo "mail sendt!  ";

          //email sent
          $action['result'] = 'success';
          array_push($text,'Thanks for signing up. Please check your email for confirmation!');
        
        }else{
          echo "Mail failed";
          $action['result'] = 'error';
          array_push($text,'Could not send confirm email');
        
        }
      
      } else {
        echo "Bekreft kunne ikke legge til i database";
        $action['result'] = 'error';
        array_push($text,'Confirm row was not added to the database. Reason: ' . mysql_error());
      }

    } else {
      echo "Bruker kunne ikke legge til bruker i database";
      $action['result'] = 'error';
      array_push($text,'User could not be added to the database. Reason: ' . mysql_error());
    
    }
  }
  $action['text'] = $text;
}

?>

<script type="text/javascript">
  $(document).ready(function() {
    $("#registrer-box").hide();
    $("#registrer").click(function() {
      $("#registrer-box").show();
    });

    $(".popup-registrer-button-avbryt").click(function() {
      $("#registrer-box").hide();
    });

    $(document).mouseup(function (i) {
      var box = $("#registrer-box");
      if (!box.is(i.target) && box.has(i.target).length === 0) {
        box.hide();
      }
    });
  });
</script>

<!-- REGISTER BOX -->
<div id="registrer-box">
  <div class="popup-header center">
    <h2 class="popup-header-text"><span class="popup-header-icon"></span> Registrer deg!</h2>
  </div>

  <div class="popup-container center">
    <form name="registrer" class="popup-registrer-form" method="post" action="">
      <div class="popup-divider">
        <input name="brukernavn_reg" type="text" class="popup-input valid" placeholder="Brukernavn"><span></span>
      </div>

      <div class="popup-divider" >
        <input name="fornavn_reg" type="text" class="popup-input double input-pull-left" placeholder="Fornavn">
        <input name="etternavn_reg" type="text" class="popup-input double" placeholder="Etternavn">
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
      <input type="submit" value="Kjør på!" name="registrer-btn" class="popup-registrer-button pull-left"><span class=""></span>
    </form>
    <button form="registrer" name="button-avbryt" type="submit" class="popup-registrer-button-avbryt pull-right"><span class=""></span> Avbryt...</button>
  </div>

</div>