<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'includes/boxes.php';

// Lister alle innlegg i en tråd
if (isset($_GET['ukat_id']) && isset($_GET['traad_id'])) {
    $ukad_id = $_GET['ukat_id'];
    $tråd_id = $_GET['traad_id'];

    // Finner sideNr
    $curr_side = isset($_GET['side']) ? intval($_GET['side']) : 1;

    $traad_sql = "SELECT * FROM tråd WHERE ukat_id = ? AND tråd_id = ?";
    $stmt = $conn->prepare($traad_sql);
    $stmt->bind_param("ii", $ukad_id, $tråd_id);
    $stmt->execute();
    $traad_res = $stmt->get_result();
    $traad_row = $traad_res->fetch_assoc();
    $stmt->close();

    $innlegg_sql = "SELECT * FROM innlegg WHERE tråd_id = ?";
    $stmt = $conn->prepare($innlegg_sql);
    $stmt->bind_param("i", $tråd_id);
    $stmt->execute();
    $innlegg_res = $stmt->get_result();
    $stmt->close();

    // Fnner ant innlegg
    $sql = "SELECT COUNT('tråd_id') as antInnlegg FROM innlegg WHERE tråd_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tråd_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $antInnlegg_row = $res->fetch_assoc();
    $stmt->close();

    $antInnlegg = $antInnlegg_row['antInnlegg'];

    // TODO: Gå gjennom kode og forstå!!!
    $innlegg_per_side = 15;

    // Finner antall sider vi må vise
    $side_teller = ceil($antInnlegg / $innlegg_per_side);

    // ???
    $forste_innlegg = ($curr_side - 1) * $innlegg_per_side;

    // Viser ut sideLinks
    echo '<div class="pull-right">';
    for($i=1; $i<=$side_teller; $i++) {
        if($i == $curr_side) {
            echo '<div class="traad_page_spacer traad_side_btn active pad-right pull-left">';
            echo $i;
            echo '</div>';
        } else {
            echo '<a class="traad_page_spacer" href="traad.php?ukat_id='
                        . $ukad_id . '&traad_id='
                        . $tråd_id . '&side='
                        . $i . '"><div class="traad_side_btn pad-right pull-left">' . $i . '</div></a> ';
        }
    }
    echo '</div>';
    echo '<div class="traadtop"><h3>'   . $traad_row['tråd_tittel']
                                        . '<br><small>Skrevet av <a href="bruker.php?bruker_id='
                                        . $traad_row['bruker_id'] . '">'
                                        . $traad_row['bruker_navn'] .  '</a>  <i class="fa fa-clock-o"></i> '
                                        . $traad_row['tråd_dato'] . '</small></h3></div>';
    echo '<div id="traadtable">';
        echo '<div class="table-row-group">';
            echo '<div class="table-row">';
                echo '<div class="table-cell center traadleft skjul-liten">';
                    echo '<a href="bruker.php?bruker=' . $traad_row['bruker_id'] . '">';
                    echo '<img class="avatar_forum" src="img/profilbilder/'
                                . hentBilde($conn, $traad_row['bruker_id']) . '"><div class="clearfix"></div>';
                    echo $traad_row['bruker_navn'];
                    echo '</a><div class="clearfix"></div><small>Echo mer om bruker her!</small>';
                echo '</div>';
                    echo '<div class="table-cell traadright">';
                        echo '<i class="fa fa-clock-o"></i> '
                                    . datoSjekk($traad_row['tråd_dato'])
                                    . '<p class="traad_mobile"> av <a href="#">'
                                    . $traad_row['bruker_navn'] . '</a></p>';
                        echo '<div class="traad_innhold">';
                            echo $traad_row['tråd_innhold'];
                        echo '</div>';

                        echo '<ol class="likepost pull-right clearfix">';
                            /* TODO: Sjekk erinnlogget() */
                            if (harLikt($conn, "traad", null, $tråd_id, $_SESSION['bruker_id']) == false) {
                                echo '<li id="likepost_btn"><a href="includes/endringer.php?
                                                                traad_id=' . $tråd_id . '
                                                                &bruker_id=' . $_SESSION['bruker_id'] . '
                                                                &bruker_navn=' . $_SESSION['bruker_navn'] . '" /> 
                                                                <i class="fa fa-thumbs-up"></i> Lik</a></li>';
                            } else {
                                echo "Du og ";
                            }

                            echo '<li>' . getLikes($conn, null, $tråd_id) . '</li>';
                        echo '</ol>';
                    echo '</div>';
            echo '</div>';
        echo '</div>';

    // Then we retrieve the data for this requested page
    $sql = "SELECT * FROM innlegg WHERE tråd_id = ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $tråd_id, $forste_innlegg, $innlegg_per_side);
    $stmt->execute();
    $innlegg_res = $stmt->get_result();
    $stmt->close();

    while ($innlegg_row = $innlegg_res->fetch_assoc()) {
        echo '<div class="table-row traadspacer"></div>';
            echo '<div class="table-row">';
                echo '<div class="table-cell center traadleft skjul-liten">';
                    echo '<a href="bruker.php?bruker=' . $innlegg_row['bruker_id'] . '">';
                    echo '<img class="avatar_forum" src="img/profilbilder/'
                                            . hentBilde($conn, $innlegg_row['bruker_id']) . '"><div class="clearfix"></div>';
                    echo $innlegg_row['bruker_navn'];
                echo '</a><div class="clearfix"></div><small>Echo mer om bruker her!</small></div>';
            echo '<div class="table-cell traadright">';
                echo '<i class="fa fa-clock-o"></i> '   . datoSjekk($innlegg_row['innlegg_dato'])
                                                        . '<p class="traad_mobile"> av <a href="#">'
                                                        . $traad_row['bruker_navn'] . '</a></p>';
                echo '<div class="innlegg_innhold">';
                    echo $innlegg_row['innlegg_innhold'];
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    echo '<form name="form_svar" action="includes/endringer.php?ukat_id='
                                            . $ukad_id . '&traad_id='
                                            . $tråd_id . '" method="post">';
        echo '<textarea name="innlegg_innhold" id="innlegg_innhold" placeholder="Har du noe spennende å bidra med..?"></textarea>';
        echo '<input type="submit" name="svar_btn" id="svar_btn" class="std_btn" value="Svar">';
    echo '</form>';
}

// Hvis vi skal lage ny tråd
if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && isset($_GET['nytraad'])) {
    $kat_id = $_GET['kat_id'];
    $ukad_id = $_GET['ukat_id'];

    echo '<form action="includes/endringer.php?kat_id=' . $kat_id . '&ukat_id=' . $ukad_id . '" method="post">';
        echo '<input type="text" name="ny_traad_navn" id="ny_traad_navn" class="std_input mar-bot" placeholder="Trådnavn">';
        echo '<textarea name="ny_traad_text" id="ny_traad_text" class="std_input" placeholder="Hva har du på hjertet...?"></textarea>';

    echo '<div id="traad_buttons" clasS="pull-right">';
            echo '<div class="traad_buttons_one pull-left mar-bot pad-right">';
                echo '<input type="submit" name="ny_traad_submitt" id="ny_traad_submitt" value="Lag tråd">';
            echo '</div>';
            echo '<div class="traad_buttons_two pull-left mar-bot">';
                echo '<input type="button" class="std_btn_avbryt" value="Avbryt">';
            echo '</div>';
        echo '</div>';
    echo '</form>';
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
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="includes/endringer.php?slett_ukat_id='
                        . $ukat_id .'&kat_id='
                        . $kat_id . '">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du vikker på at du vil slette underkategorien ' . $ukat_navn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_ukat_btn" class="button-lukk">Slett den</button>
        <?php echo '</form>' ?>;
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>


