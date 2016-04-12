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


$id = '3';

$stmt_antTraader = $conn->prepare("SELECT COUNT(traad_id) AS anttraader FROM traad WHERE ukat_id = ?");
$stmt_antTraader->bind_param("i", $id);
$stmt_antTraader->execute();
$stmt_antTraader->store_result();
$stmt_antTraader->bind_result($sql_anttraader);
$stmt_antTraader->fetch();

echo $sql_anttraader;

echo "<br>";

echo tellTraader($conn, "ukat", $id);

?>


