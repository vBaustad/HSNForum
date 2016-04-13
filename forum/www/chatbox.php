<?php
require_once ('includes/functions.php');
require_once ('includes/db_connect.php');
?>

<script src="js/chatbox.js" xmlns="http://www.w3.org/1999/xhtml"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({cache:false});
        setInterval(function () {
            $("#meldinger").load('includes/chat.php');
        }, 2000);

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
if( innlogget() ) {
    $bruker_id = $_SESSION['bruker_id'];
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
                    <input type="button" id="chat_send" class="button-std pull-right" value="SEND" onclick="chat()" >
                    <span id="inline_fix"><input type="text" name="chat_msg_text" id="chat_msg_text" class="pull-left" placeholder="Si hei..."/></span>
                </div>
            </div>
        </div>
_EOL;

    if ($stmt = $conn->prepare("SELECT bruker_fornavn, bruker_bilde, bruker_dato, bruker_sist_aktiv FROM bruker WHERE bruker_id = ?")) {
        $stmt -> bind_param("i", $bruker_id);
        $stmt -> execute();
        $stmt -> store_result();
        $stmt -> bind_result($sql_bruker_fornavn, $sql_bruker_bilde, $sql_bruker_dato, $sql_bruker_sist_aktiv);
        $stmt -> fetch();

        $ant_innlegg = tellInnlegg($conn, "bruker", $bruker_id);
        $ant_traader = tellTraader($conn, "bruker", $bruker_id);

        $bruker_siden = date("d-m-Y", strtotime($sql_bruker_dato));

        $traad_idag = aktivitetIdag($conn, "traad" ,$bruker_id);
        $innlegg_idag = aktivitetIdag($conn, "innlegg" ,$bruker_id);
        $aktivitet_idag = $traad_idag + $innlegg_idag;

        $aktive_brukere = aktiveBrukere($conn, $sql_bruker_sist_aktiv);
        $karma = $ant_innlegg + $ant_traader;

        echo <<<_EOL
        <div class="textarea pull-right skjul-liten skjul-medium">
            <h1>Velkommen $sql_bruker_fornavn!</h1>
            <div class="clearfix"></div>
                <p>Medlem siden: $bruker_siden </p>
                <p>Aktivitet i dag: $aktivitet_idag </p>
                <p>Antall aktive brukere i dag: $aktive_brukere</p>
                <p>Karma: $karma </p>
            </div>
        </div>
        <div class="clearfix seperator"></div>
_EOL;
        $stmt -> close();
    }

} else {
    echo <<<_EOL
        <div id="velkomstBoks">
           <h1>Velkommen til HSNForum!</h1>
                <p>Velkommen til Høgskolen i Sørøst-Norge. Vi ønsker at alle studenter skal ha muligheten til å få så mye hjelp som mulig med studiene også utenfor forelesingene. 
                    Forumet skal hjelpe studenter ved å gjøre det enkelt å stille spørsmål angående timeplaner, skoleoppgaver eller oppretting av studiegrupper. 
                    Registrer deg ved å klikke øverst i høyre hjørne. For å kunne ta i bruk forumet krever vi at du er student ved HSN og har registrert deg med en gyldig student-epost. 
                </p>
        </div>
_EOL;

}

?>