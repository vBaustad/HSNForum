<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'includes/boxes.php';

if (isset($_GET['ukat_id']) && isset($_GET['tråd_id'])) {
    $ukad_id = $_GET['ukat_id'];
    $tråd_id = $_GET['tråd_id'];

    $traad_sql = mysqli_query($conn, "SELECT * FROM tråd WHERE ukat_id = '$ukad_id' AND tråd_id = '$tråd_id'");
    $traad_row = mysqli_fetch_assoc($traad_sql);

    $innlegg_sql = mysqli_query($conn, "SELECT * FROM innlegg WHERE tråd_id = '$tråd_id'");

    echo '<div class="traadtop"><h3>'   . $traad_row['tråd_tittel']
                                        . '<br><small>Skrevet av <a href="bruker.php?bruker_id='
                                        . $traad_row['bruker_id'] . '">'
                                        . $traad_row['bruker_navn'] .  '</a>  <i class="fa fa-clock-o"></i>'
                                        . " 17 timer siden" . '</small></h3></div>';
    echo '<div id="traadtable">';
        echo '<div class="table-row-group">';
            echo '<div class="table-row">';
                echo '<div class="table-cell traadleft skjul-liten">';
                        echo '<a href="#">' . $traad_row['bruker_navn'];
                        echo '<div class="clearfix"></div><img class="avatar_forum" src="img/profilbilder/' . hentBilde($conn, $traad_row['bruker_id']) . '">';
                        echo '</a><div class="clearfix"></div><small>Echo mer om bruker her!</small>';
                echo '</div>';
                    echo '<div class="table-cell traadright">';
                        echo '<i class="fa fa-clock-o"></i> Skrevet 1/1/2016 <p class="traad_mobile"> av <a href="#">' . $traad_row['bruker_navn'] . '</a></p>';
                        echo '<div class="traad_innhold">';
                            echo $traad_row['tråd_innhold'];
                        echo '</div>';
                    echo '</div>';
            echo '</div>';
        echo '</div>';

    while ($innlegg_row = $innlegg_sql->fetch_assoc()) {
        echo '<div class="table-row traadspacer"></div>';
        echo '<div class="table-row">';
            echo '<div class="table-cell traadleft skjul-liten">';
                echo $innlegg_row['bruker_navn'];
                echo '<img class="avatar_forum"src="img/profilbilder/' . hentBilde($conn, $innlegg_row['bruker_id']) . '"><div class="clearfix"></div><small></small>';
            echo '</div>';
            echo '<div class="table-cell traadright">';
                echo '<i class="fa fa-clock-o"></i> ' . datoSjekk($traad_row['tråd_dato']) . '<p class="traad_mobile"> av <a href="#">' . $traad_row['bruker_navn'] . '</a></p>';
                echo '<div class="innlegg_innhold">';
                    echo $innlegg_row['innlegg_innhold'];
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}

?>








<!-- SLETT UNDERKATEGORI -->
<div id="slett_traad" style="display: none">
    <div class="popup-header center">
        <div class="pull-left" style="width: 80%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette underkategori?</h2>
        </div>
        <div class="pull-right half" style="width: 20%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="includes/endringer.php?slett_ukat_id=' . $ukat_id .'&kat_id=' . $kat_id . '">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du vikker på at du vil slette underkategorien ' . $ukat_navn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_ukat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div>

<?php
if (isset($_GET['ukat_id'])) {
    $ukad_id = $_GET['ukat_id'];

}
require_once 'includes/footer.php';
?>


