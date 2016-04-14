<?php
$sql_funfact = $conn->prepare("SELECT COUNT(bruker.bruker_id) as antBrukere,
        ( SELECT COUNT(kategori.kat_id) FROM kategori ) as antKat,
        ( SELECT COUNT(underkategori.ukat_id ) FROM underkategori ) as antUkat,
        ( SELECT COUNT(traad.traad_id) FROM traad ) as antTraad,
        ( SELECT COUNT(innlegg.innlegg_id) FROM innlegg )  as antInnlegg FROM bruker");
$sql_funfact->execute();
$sql_funfact->store_result();
$sql_funfact->bind_result($ant_brukere, $ant_kategorier, $ant_underkategorier, $ant_traader, $ant_innlegg);
$sql_funfact->fetch();

$sti = '<ul class="sti">
                <li><a href="index.php">Hjem</a></li>
            </ul>';

if (isset($_GET['bruker'])) {
    $sti = '<ul class="sti">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="bruker.php?bruker=' . $_GET['bruker'] . '">Brukerprofil</a></li>
            </ul>';
}

if (isset($_GET['kat_id']) && !isset($_GET['ukat_id']) && !isset($_GET['traad_id'])) {
    $katnavn = hvorErJeg($conn, "kat", $kat_id);
    $sti = '<ul class="sti">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="kategori.php?kat_id=' . $_GET['kat_id'] . '">' . $katnavn . '</a></li>
            </ul>';
}

if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && !isset($_GET['traad_id'])) {
    $katnavn = hvorErJeg($conn, "kat", $kat_id);
    $ukatnavn = hvorErJeg($conn, "ukat", $ukat_id)[1];

    $sti = '<ul class="sti">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="kategori.php?kat_id=' . $_GET['kat_id'] . '">' . $katnavn . '</a></li>
                <li><a href="kategori.php?kat_id=' . $_GET['kat_id'] . '&ukat_id=' . $_GET['ukat_id'] . '">' . $ukatnavn . '</a></li>
            </ul>';
}

if (!isset($_GET['kat_id']) && isset($_GET['ukat_id']) && isset($_GET['traad_id'])) {
    $ukatnavn = hvorErJeg($conn, "ukat", $ukat_id)[1];
    $traadnavn = hvorErJeg($conn, "traad", $_GET['traad_id']);
    $katid = hvorErJeg($conn, "ukat", $ukat_id)[0];

    $sti = '<ul class="sti">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="kategori.php?kat_id=' . $katid . '">' . $katnavn . '</a></li>
                <li><a href="kategori.php?kat_id=' . $katid . '&ukat_id=' . $_GET['ukat_id'] . '">' . $ukatnavn . '</a></li>
                <li><a href="traad.php?ukat_id=' . $_GET['ukat_id'] . '&traad_id=' . $_GET['traad_id'] . '">' . $traadnavn . '</a></li>
            </ul>';
}

?>

</div> <!-- Container  -->
<div class="mar-bot"></div>
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
            <li>Funfacts</li>
            <li>Registrerte brukere: <?php  echo $ant_brukere ?></li>
            <li>Kategorier: <?php  echo $ant_kategorier ?></li>
            <li>Underkategorier: <?php  echo $ant_underkategorier ?></li>
            <li>Tråder startet: <?php echo $ant_traader ?></li>
            <li>Innlegg: <?php echo $ant_innlegg ?></li>
        </ul>
    </div>
    <div id="footerright" class="footer">
        <ul class="footer-li">
            <li>Du befinner deg her</li>
        </ul>
        <?php echo $sti ?>

    </div>
    <div class="clearfix"></div>
</footer>
</body>
</html>