<?php
require_once '/php/functions.php';
require_once '/php/db_connect.php';

//setup some variables/arrays
$action = array();
$action['result'] = null;

$text = array();

//check if the form has been submitted
if(isset($_POST['registrer'])){
  //prevent mysql injection
  $bruker_id = NULL;
  $brukernavn = mysql_real_escape_string($_POST["brukernavn_reg"]);
  $fornavn = mysql_real_escape_string($_POST["fornavn_reg"]);
  $etternavn = mysql_real_escape_string($_POST["etternavn_reg"]);
  $passord = mysql_real_escape_string($_POST["pass_reg"]);
  $epost = mysql_real_escape_string($_POST["epost_reg"]);
  $dato = date("Y-m-d G:i:s");
  $level = 1;
  $aktiv = 0;
  
  //quick/simple validation
  if(empty($brukernavn)){ $action['result'] = 'error'; array_push($text,'You forgot your username'); }
  if(empty($passord)){ $action['result'] = 'error'; array_push($text,'You forgot your password'); }
  if(empty($epost)){ $action['result'] = 'error'; array_push($text,'You forgot your email'); }
  
  if($action['result'] != 'error'){
    

    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $password = hash('ripemd160', "$salt1$passord$salt2");
      
    //add to the database
    $add = mysql_query("INSERT INTO bruker VALUES(
    '$bruker_id', '$brukernavn', '$fornavn', '$etternavn', '$password', '$epost', '$dato', '$level', '$aktiv')");
    
    if($add){
      
      //get the new user id
      $userid = mysql_insert_id();
      
      //create a random nokkel
      $nokkel = $brukernavn . $epost . date('mYG');
      $nokkel = md5($nokkel);
      
      //add confirm row
      $bekreft = mysql_query("INSERT INTO `bekreft` VALUES(NULL,'$bruker_id','$nokkel','$epost')");  
      
      if($bekreft){
      
        //include the swift class
        require_once('/home/120400/public_html/html/2016/eksamen/forum_v2/swift/lib/swift_required.php');
      
        //Sender informasjon til funksjonen
        $info = array(
          'brukernavn' => $brukernavn,
          'epost' => $epost,
          'nokkel' => $nokkel,
          'fornavn' => $fornavn);
      
        //Prøver å sende eposten
        if(send_email($info)){
                
          //email sent
          $action['result'] = 'success';
          array_push($text,'Thanks for signing up. Please check your email for confirmation!');
        
        }else{
          
          $action['result'] = 'error';
          array_push($text,'Could not send confirm email');
        
        }
      
      }else{
        
        $action['result'] = 'error';
        array_push($text,'Confirm row was not added to the database. Reason: ' . mysql_error());
        
      }
      
    }else{
    
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
    <form class="popup-registrer-form" name="registrer" method="post" action="">
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
    </form>
  </div>
  <button form="registrer" name="registrer" type="submit" class="popup-registrer-button pull-left"><span class=""></span> Kjør på!</button>
  <button form="registrer" name="button-avbryt" type="submit" class="popup-registrer-button-avbryt pull-right"><span class=""></span> Avbryt...</button>
</div>