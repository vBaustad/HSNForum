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

<?php


if(innlogget()){

echo <<<_EOL
<div id="chatbox">
    <div class="chatbox-container pull-left">
        <div id="meldinger">
            <span id="chat_laster" class="center">
                <i class="fa fa-circle-o-notch fa-spin fa-3x"></i>
            </span>
        </div>
        <div class="chatbox-bottom">
            <div class="chat_footer">
                <input type="button" id="chat_send" class="button-std pull-right" value="SEND" onclick="chat()" />
                <span id="inline_fix"><input type="text" name="chat_msg_text" id="chat_msg_text" class="pull-left" placeholder="Si hei..."/></span>
            </div>
        </div>
    </div>
_EOL;

    $bruker_id = $_SESSION['bruker_id'];

    if($stmt = $conn->prepare("SELECT bruker_fornavn, bruker_bilde, bruker_dato, bruker_sist_aktiv FROM bruker WHERE bruker_id = ?")){
        // Bind parameters
        $stmt -> bind_param("i", $bruker_id);
        $stmt -> execute();
        $stmt -> store_result();

        //Bind results
        $stmt -> bind_result($sql_bruker_fornavn, $sql_bruker_bilde, $sql_bruker_dato, $sql_bruker_sist_aktiv);

        //fetch value
        $stmt -> fetch();

        //close statement
        $stmt -> close();
    }

    $bruker_siden = date("d-m-Y", strtotime($sql_bruker_dato));
    $ant_innlegg = tellInnlegg($conn, "bruker", $bruker_id);
    $ant_tråder = tellTraader($conn, "bruker", $bruker_id);
    $karma = $ant_innlegg + $ant_tråder;
    $innlegg_idag = innleggIdag($conn, $bruker_id);
    $aktive_brukere = aktiveBrukere($conn, $sql_bruker_sist_aktiv);

echo <<<_EOL
    <div class="textarea pull-right skjul-liten skjul-medium">
        <h1>Velkommen $sql_bruker_fornavn !</h1>
        <div class="clearfix"></div><img style="float:right" class="avatar_forum" src="img/profilbilder/1.jpg">
            <p>Medlem siden: $bruker_siden </p>
            <p>Antall innlegg i dag: $innlegg_idag </p>
            <p>Antall aktive brukere: $aktive_brukere</p>
            <p>Karma: $karma </p>
        </div>
    </div>
<div class="clearfix seperator"></div>
_EOL;

}
else {
echo <<<_EOL

<div id="velkomstBoks">
   <h1>Velkommen til HSNForum!</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dolor purus, lobortis vitae turpis vitae, 
            eleifend convallis orci. Sed ac lectus rutrum, suscipit nisi ut, iaculis ligula. Suspendisse pellentesque pharetra ipsum ultrices tincidunt.
             Mauris pellentesque magna quis elit mattis, vel rutrum purus ullamcorper. Nulla ut urna a urna euismod dignissim. Phasellus at sapien vitae dui pharetra sollicitudin non sit amet nisi. 
             Vestibulum a diam at odio rhoncus aliquam eget non sem. Fusce at quam eget ante dignissim laoreet. Donec eleifend convallis semper. Nunc nec risus ut sapien scelerisque malesuada. 
             Praesent sollicitudin nisi et quam viverra, in venenatis ipsum tristique. Cras neque orci, bibendum eu nisl in, varius pellentesque mauris.
            
            Ut ut scelerisque purus. Aenean pretium velit sed tellus ullamcorper porttitor. Suspendisse varius neque eget semper bibendum. 
            Cras consequat tortor at magna consectetur porttitor. Duis congue hendrerit nibh, sed maximus est placerat sed. Integer non tempus leo, ut venenatis quam. 
            Curabitur eget quam sagittis, laoreet libero eu, cursus eros. Donec ullamcorper gravida dolor. Curabitur et ex placerat, tempus augue non, tristique purus. 
            Ut molestie mauris nisi, ut molestie turpis commodo sit amet. Sed malesuada finibus nibh ut mattis. Nulla pretium feugiat leo, vitae dapibus sapien suscipit vitae.
            Nulla elementum pulvinar diam ac suscipit. Nam ut velit nec lectus sodales suscipit placerat sit amet magna.
        </p>
</div>
_EOL;

}

?>