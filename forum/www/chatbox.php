<?php
require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/db_connect.php');
?>
<script src="js/chatbox.js" xmlns="http://www.w3.org/1999/xhtml"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({cache:false});
        setInterval(function () {
            $("#meldinger").load('includes/chat.php');
        }, 1000);

        $("#chat_msg_text").keyup(function(event){
            if(event.keyCode == 13){
                $("#chat_send").click();
            }
        });

        // Funker ikke når man skriver nye meldinger. :(
        $("#meldinger").animate({
            scrollTop: $(document).height()
        }, "slow");
    });
</script>

<div id="chatbox">
    <div class="chatbox-container pull-left">
        <div id="meldinger">
            <span id="chat_laster" class="center">
                <i class="fa fa-circle-o-notch fa-spin fa-3x"></i>
            </span>
        </div>

        <div class='chatbox-bottom'>
            <div class="chat_footer">
                <input type="button" id="chat_send" class="button-std pull-right" value="SEND" onclick="chat()" />
                <span id="inline_fix"><input type="text" name="chat_msg_text" id="chat_msg_text" class="pull-left" placeholder="Si hei..."/></span>
            </div>
        </div>
    </div>
    <div class="textarea pull-right skjul-liten skjul-medium">
        <h1>TESTING</h1>
        <p>Mer test her om test og anndre ikke test reaterte tester </p>
    </div>
</div>
<div class="clearfix seperator"></div>