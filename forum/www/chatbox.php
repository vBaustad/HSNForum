<?php
require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/db_connect.php');
?>
<script src="js/chatbox.js" xmlns="http://www.w3.org/1999/html"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({cache:false});
        setInterval(function () {
            $("#meldinger").load('http://localhost/forum/www/includes/chat.php');
        }, 2000);
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
            <script type="text/javascript">
                // Aktiverer funksjonen_chat_ved trykk på ENTER
                $(document).ready(function () {
                    $("#chat_msg").keyup(function(event){
                        if(event.keyCode == 13){
                            $("#chat_send").click();
                            // clear form!
                        }
                    });
                })

            </script>
            <div class="chat_footer">
                <input type='text' name='chat_msg' id='chat_msg' class="pull-left" placeholder='Si hei...'/>
                <input type="button" id="chat_send" class="button-std pull-right" value="SEND" onclick="chat()" />
            </div>
        </div>
    </div>
<div class="textarea pull-right hidden-xs hidden-sm">
    <h1>TESTING</h1>
    <p>Mer test her om test og anndre ikke test reaterte tester </p>
</div>
<div class="clearfix seperator"></div>