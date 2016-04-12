<script type="text/javascript">
    function test() {
        var brukernavn = document.getElementById("bruker").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("meldinger").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "includes/endringer.php?nybruker="+brukernavn, true);
        xmlhttp.send();
    }
</script>

<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if ($stmt = $conn->prepare("SELECT bruker_id, bruker_navn FROM bruker")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($brukerid, $brukernavn);
    echo '<div id="meldinger">';
    while ($stmt->fetch()) {
        echo $brukerid . ' ' . $brukernavn . '<br>';
    }
    echo '</div>';
}



echo '<input type="text" id="bruker" name="bruker">
          <input type="button" id="test_send" class="button-std" value="SEND" onclick="test()" >';
?>


