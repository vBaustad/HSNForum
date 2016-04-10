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

    $traad_sql = "SELECT tråd_id, ukat_id, tråd_tittel, tråd_innhold, tråd_dato, bruker_navn, bruker_id FROM tråd WHERE ukat_id = ? AND tråd_id = ?";
    $stmt_tråd = $conn->prepare($traad_sql);
    $stmt_tråd->bind_param("ii", $ukad_id, $tråd_id);
    $stmt_tråd->execute();
    $stmt_tråd->store_result();
    $stmt_tråd->bind_result($traad_tråd_id, $traad_ukat_id, $traad_tråd_tittel,
                       $traad_tråd_innhold, $traad_tråd_dato, $traad_bruker_navn, $traad_bruker_id);
    $stmt_tråd->fetch();
    $stmt_tråd->close();

    $innlegg_sql = "SELECT innlegg_id, tråd_id, innlegg_innhold, innlegg_dato, ukat_id, bruker_id, bruker_navn FROM innlegg WHERE tråd_id = ?";
    $stmt_innlegg = $conn->prepare($innlegg_sql);
    $stmt_innlegg->bind_param("i", $tråd_id);
    $stmt_innlegg->execute();
    $stmt_innlegg->bind_result($innlegg_innlegg_id, $innlegg_tråd_id, $innlegg_innlegg_innhold, $innlegg_innlegg_dato,
                               $innlegg_ukat_id, $innlegg_bruker_id, $innlegg_bruker_navn);
    $stmt_innlegg->fetch();
    $stmt_innlegg->close();

    // Fnner ant innlegg
    $antInnlegg = tellInnlegg($conn, "traad", 1);

    // TODO: Gå gjennom kode og forstå!!!
    $innlegg_per_side = 10;

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
    $bilde = hentBilde($conn, $traad_bruker_id);
    $datosjekk = datoSjekk($traad_tråd_dato);
    $likes = getLikes($conn, null, $tråd_id);
    echo <<<_END
        </div>
        <div class="traadtop"><h3>$traad_tråd_tittel<br><small>Skrevet av 
            <a href="bruker.php?bruker_id=$traad_bruker_id">$traad_bruker_navn</a> 
            <i class="fa fa-clock-o"></i> $datosjekk</small></h3>
        </div>
        <div id="traadtable">
            <div class="table-row-group">
                <div class="table-row">
                    <div class="table-cell center traadleft skjul-liten">
                        <a href="bruker.php?bruker=$traad_bruker_id">
                            <img class="avatar_forum" src="img/profilbilder/$bilde">
                            <div class="clearfix"></div>
                            $traad_bruker_navn
                        </a>
                        <div class="clearfix"></div>
                        <small>Echo mer om bruker her!</small>
                    </div>
                    <div class="table-cell traadright">
                        <i class="fa fa-clock-o"></i> $datosjekk<p class="traad_mobile"> av <a href="#">$traad_bruker_navn</a></p>
                        <div class="traad_innhold">
                            $traad_tråd_innhold
                        </div>
                        <ol class="likepost pull-right clearfix">
_END;
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
                            echo <<<_END
                            <li>$likes</li>
                        </ol>
                    </div>
                </div>
            </div>
_END;

    // Henter dato for nåværende side
    $sql = "SELECT innlegg_id, tråd_id, innlegg_innhold, innlegg_dato, ukat_id, bruker_id, bruker_navn FROM innlegg WHERE tråd_id = ? LIMIT ?, ?";
    $stmt_pagedata = $conn->prepare($sql);
    $stmt_pagedata->bind_param("iii", $tråd_id, $forste_innlegg, $innlegg_per_side);
    $stmt_pagedata->execute();
    $stmt_pagedata->store_result();
    $stmt_pagedata->bind_result($pagedata_innlegg_id, $pagedata_tråd_id, $pagedata_innlegg_innhold, $pagedata_innlegg_dato,
                                $pagedata_ukat_id, $pagedata_bruker_id, $pagedata_bruker_navn);

    while ($stmt_pagedata->fetch()) {
        $bilde = hentBilde($conn, $pagedata_bruker_id);
        $dato = datoSjekk($pagedata_innlegg_dato);
        echo <<<_END
            <div class="table-row traadspacer"></div>
            <div class="table-row">
                <div class="table-cell center traadleft skjul-liten">
                    <a href="bruker.php?bruker=' . $pagedata_bruker_id . '">
                        <img class="avatar_forum" src="img/profilbilder/$bilde">
                        <div class="clearfix"></div>
                        $pagedata_bruker_navn
                    </a>
                    <div class="clearfix"></div>
                    <small>Echo mer om bruker her!</small>
                </div>
                <div class="table-cell traadright">
                    <i class="fa fa-clock-o"></i> $dato<p class="traad_mobile"> av <a href="#">$traad_bruker_navn</a></p>
                    <div class="innlegg_innhold">
                        $pagedata_innlegg_innhold
                    </div>
                </div>
            </div>
_END;
    }
    $stmt_pagedata->close();
    echo <<<_END
        </div>
        <form name="form_svar" action="includes/endringer.php?ukat_id=$ukad_id&traad_id=$tråd_id" method="post">
            <textarea name="innlegg_innhold" id="innlegg_innhold" placeholder="Har du noe spennende å bidra med..?"></textarea>
            <input type="submit" name="svar_btn" id="svar_btn" class="std_btn" value="Svar">
        </form>
_END;
}

// Hvis vi skal lage ny tråd
if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && isset($_GET['nytraad'])) {
    $kat_id = $_GET['kat_id'];
    $ukad_id = $_GET['ukat_id'];

    echo <<<_END
    <form action="includes/endringer.php?kat_id=$kat_id&ukat_id=$ukad_id" method="post">
        <input type="text" name="ny_traad_navn" id="ny_traad_navn" class="std_input mar-bot" placeholder="Trådnavn">
        <textarea name="ny_traad_text" id="ny_traad_text" class="std_input" placeholder="Hva har du på hjertet...?"></textarea>

        <div id="traad_buttons" clasS="pull-right">
            <div class="traad_buttons_one pull-left mar-bot pad-right">
                <input type="submit" name="ny_traad_submitt" id="ny_traad_submitt" value="Lag tråd">
            </div>
            <div class="traad_buttons_two pull-left mar-bot">
                <input type="button" class="std_btn_avbryt" value="Avbryt">
            </div>
        </div>
    </form>
_END;
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


