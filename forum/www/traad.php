<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

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
    echo '  <div id="traadtable">';
    echo '    <div class="table-row-group">';
    echo '      <div class="table-row">';
    echo '        <div class="table-cell traadleft"><a href="#">' . $traad_row['bruker_navn'] . '</a><br><small>1/1-2016</small></div>';
    echo '        <div class="table-cell traadright"><i class="fa fa-clock-o"></i> Skrevet 1/1/2016';
    echo '          <div class="traad_innhold">'
                      . $traad_row['tråd_innhold'] . '
                    </div>';
    echo '      </div>';
    echo '    </div>';

    while ($innlegg_row = $innlegg_sql->fetch_assoc()) {
        echo '    <div class="table-row traadspacer"></div>';
        echo '      <div class="table-row">';
        echo '        <div class="table-cell traadleft">' . $innlegg_row['bruker_navn'] . '<br><small>2/1-2016</small></div>';
        echo '        <div class="table-cell traadright"><i class="fa fa-clock-o"></i> Skrevet 1/1/2016<h4></h4>';
        echo '          <div class="innlegg_innhold">'
                          . $innlegg_row['innlegg_innhold'] . '
                        </div>';
        echo '      </div>';
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
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="http://localhost/forum/www/includes/endringer.php?slett_ukat_id=' . $ukat_id .'&kat_id=' . $kat_id . '">' ?>
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

