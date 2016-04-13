<?php

// TODO: Skvis dette inn i èn spørring? COOOL!
$sql_brukere = $conn->prepare("SELECT COUNT(bruker_id) FROM bruker");
$sql_brukere->execute();
$sql_brukere->store_result();
$sql_brukere->bind_result($ant_brukere);
$sql_brukere->fetch();

$sql_kat = $conn->prepare("SELECT COUNT(kat_id) FROM kategori");
$sql_kat->execute();
$sql_kat->store_result();
$sql_kat->bind_result($ant_kat);
$sql_kat->fetch();

$sql_ukat = $conn->prepare("SELECT COUNT(ukat_id) FROM underkategori");
$sql_ukat->execute();
$sql_ukat->store_result();
$sql_ukat->bind_result($ant_ukat);
$sql_ukat->fetch();

$sql_traader = $conn->prepare("SELECT COUNT(traad_id) FROM traad");
$sql_traader->execute();
$sql_traader->store_result();
$sql_traader->bind_result($ant_traader);
$sql_traader->fetch();

$sql_innlegg = $conn->prepare("SELECT COUNT(innlegg_id) FROM innlegg");
$sql_innlegg->execute();
$sql_innlegg->store_result();
$sql_innlegg->bind_result($ant_innlegg);
$sql_innlegg->fetch();


?>
</div> <!-- Container  -->
    <footer class="center">
        <div id="footerleft" class="footer">
            <p class="footer_left_text mar-bot">Denne siden er et resultat av eksamensoppgaven i Web og databaser 2016.</p>
            <ul class="footer-li">
                <li>Laget av</li>
                <li><a href="#">Vebjørn Baustad</a></li>
                <li><a href="#">Jørgen Solli</a></li>
            </ul>
        </div>
        <div id="footermiddle" class="footer">
            <ul class="footer-li">
                <li>Funfact</li>
                <li>Registrerte brukere: <?php  echo $ant_brukere ?></li>
                <li>Kategorier: <?php  echo $ant_kat ?></li>
                <li>Underkategorier: <?php  echo $ant_ukat ?></li>
                <li>Tråder startet: <?php echo $ant_traader ?></li>
                <li>Innlegg: <?php echo $ant_innlegg ?></li>
            </ul>
        </div>
        <div id="footerright" class="footer">
            <ul class="footer-li">
                <li>Du befinner deg her</li>
                <li>Vebjørn Baustad</li>
                <li>Jørgen Solli</li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </footer>

</body>
</html>