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

echo '<div id="chatbox">';
    echo '<div class="chatbox-container pull-left">';
        echo '<div id="meldinger">';
            echo '<span id="chat_laster" class="center">';
                echo '<i class="fa fa-circle-o-notch fa-spin fa-3x"></i>';
            echo '</span>';
        echo '</div>';
        echo '<div class="chatbox-bottom">';
            echo '<div class="chat_footer">';
                echo '<input type="button" id="chat_send" class="button-std pull-right" value="SEND" onclick="chat()" />';
                echo '<span id="inline_fix"><input type="text" name="chat_msg_text" id="chat_msg_text" class="pull-left" placeholder="Si hei..."/></span>';
            echo '</div>';
        echo '</div>';
echo '</div>';

    if(innlogget()){

    // TODO: Prepared statement goes here
    $bruker_id = $_SESSION['bruker_id'];
    $sql = mysqli_query($conn, "SELECT bruker_fornavn, bruker_bilde, bruker_dato, bruker_sist_aktiv FROM bruker WHERE bruker_id = '$bruker_id'");
    $row = mysqli_fetch_assoc($sql);

    $bruker_siden = date("d-m-Y", strtotime($row['bruker_dato']));

    $ant_innlegg = tellInnlegg($conn, $bruker_id);
    $ant_tråder = tellTraader($conn, $bruker_id);
    $karma = $ant_innlegg + $ant_tråder;


    echo '<div class="textarea pull-right skjul-liten skjul-medium">';
        echo '<h1>Velkommen ' . $row['bruker_fornavn'] . '!</h1>';
        echo '<div class="clearfix"></div><img style="float:right" class="avatar_forum" src="img/profilbilder/1.jpg">';
            echo '<p>Medlem siden: ' . $bruker_siden  . '</p>';

            // TODO: not working
            echo '<p>antall innlegg i dag: '. innleggIdag($conn, $bruker_id) . ' </p>';

            // TODO: not working
            echo '<p>antall aktive brukere: '. aktiveBrukere($conn, $row['bruker_sist_aktiv']) . ' </p>';
            echo '<p>karma: ' . $karma . '</p>';
        echo '</div>';
    echo '</div>';
echo '<div class="clearfix seperator"></div>';

}

?>