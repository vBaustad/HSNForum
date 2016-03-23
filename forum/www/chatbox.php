<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<script src="js/chatbox.js" xmlns="http://www.w3.org/1999/html"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({cache:false});
        setInterval(function () {
            $("#meldinger").load('http://localhost/forum/www/includes/chatut.php');
        }, 1000);
    });
</script>

<div id="chatbox">
    <div class="chatbox-container pull-left">
        <div id="meldinger" class='chatbox-messages'>
        <!--    <div class="melding_rad"><p class="chat_bnavn">Jogex:</p><p class="chat_msg">Hey hva skjer a?</p><p class="chat_dato">@ 12/12 24:00</p><br></div>
            <div class="melding_rad"><p class="chat_bnavn">Jogex:</p><p class="chat_msg">Hey hva skjer a?</p><p class="chat_dato">@ 12/12 24:00</p><br></div>
            <div class="melding_rad"><p class="chat_bnavn">Jogex:</p><p class="chat_msg">Hey hva skjer a?</p><p class="chat_dato">@ 12/12 24:00</p><br></div>
            <div class="melding_rad"><p class="chat_bnavn">Jogex:</p><p class="chat_msg">Hey hva skjer a?</p><p class="chat_dato">@ 12/12 24:00</p><br></div>
            <div class="melding_rad"><p class="chat_bnavn">Jogex:</p><p class="chat_msg">Hey hva skjer a?</p><p class="chat_dato">@ 12/12 24:00</p><br></div>
    -->    </div>

        <div class='chatbox-bottom'>
            <input type='text' name='chat_msg' id='chat_msg' placeholder='Skriv en melding til alle sammen!'/>
            <button class="button-std"><a href="#" onclick="chat()">SEND</a></button>
        </div>
    </div>

<div class="textarea pull-right hidden-xs hidden-sm">
    <h1>TESTING</h1>
    <p>Mer test her om test og anndre ikke test reaterte tester </p>
</div>
<div class="clearfix seperator"></div>

