<div class="container">
  <div class="page-header">
    <h1 class="pull-left">FORUM FOR HSN STUDENTER</h1>
    <ol class="breadcrumb pull-right">
      <li><a id="log_inn" href="#" data-rel="popup">Log inn</a></li>
      <li><a id="registrer" href="#" data-rel="popup">Registrer deg</a></li>
    </ol>  
    <div class="clearfix"></div>
  </div> 

<?php
  echo "Dato og tid: " . date("Y-d-m G:i:s");
  require_once '/php/functions.php';
  require_once '/php/db_connect.php';
  require_once 'registrer.php';
  require_once 'loginn.php';
?>