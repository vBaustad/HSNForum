<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'includes/boxes.php';

// Lister alle innlegg i en traad
if (isset($_GET['ukat_id']) && isset($_GET['traad_id']) && innlogget()) {
    $ukat_id = $_GET['ukat_id'];
    $traad_id = $_GET['traad_id'];

    $kat_id = hvorErJeg($conn, "ukat", $ukat_id)[0];
    $katnavn = hvorErJeg($conn, "kat", $kat_id);
    $ukatnavn = hvorErJeg($conn, "ukat", $ukat_id)[1];
    echo <<<_END
        <ol class="sti pull-left mar-bot">
            <li><a href="index.php">Hjem</a></li>
            <li><a href="kategori.php?kat_id=$kat_id">$katnavn</a></li>
            <li><a href="kategori.php?kat_id=$kat_id&ukat_id=$ukat_id">$ukatnavn</a></li>            
        </ol>
_END;

    // Finner sideNr
    $curr_side = isset($_GET['side']) ? intval($_GET['side']) : 1;

    $traad_sql = "SELECT traad_id, ukat_id, traad_tittel, traad_innhold, traad_dato, bruker_navn, bruker_id FROM traad WHERE ukat_id = ? AND traad_id = ?";
    $stmt_traad = $conn->prepare($traad_sql);
    $stmt_traad->bind_param("ii", $ukat_id, $traad_id);
    $stmt_traad->execute();
    $stmt_traad->store_result();
    $stmt_traad->bind_result($traad_traad_id, $traad_ukat_id, $traad_traad_tittel,
                       $traad_traad_innhold, $traad_traad_dato, $traad_bruker_navn, $traad_bruker_id);
    $stmt_traad->fetch();
    $stmt_traad->close();

    $innlegg_sql = "SELECT innlegg_id, traad_id, innlegg_innhold, innlegg_dato, ukat_id, bruker_id, bruker_navn FROM innlegg WHERE traad_id = ?";
    $stmt_innlegg = $conn->prepare($innlegg_sql);
    $stmt_innlegg->bind_param("i", $traad_id);
    $stmt_innlegg->execute();
    $stmt_innlegg->bind_result($innlegg_innlegg_id, $innlegg_traad_id, $innlegg_innlegg_innhold, $innlegg_innlegg_dato,
                               $innlegg_ukat_id, $innlegg_bruker_id, $innlegg_bruker_navn);
    $stmt_innlegg->fetch();
    $stmt_innlegg->close();

    // Fnner ant innlegg
    $antInnlegg = tellInnlegg($conn, "traad", $traad_id);

    // TODO: Gå gjennom kode og forstå!!!
    $innlegg_per_side = 10;

    // Finner antall sider vi må vise
    $side_teller = ceil($antInnlegg / $innlegg_per_side);

    // ???
    $forste_innlegg = ($curr_side - 1) * $innlegg_per_side;

    if (innlogget() && bruker_level() == "admin" || innlogget() && $traad_bruker_id == $_SESSION['bruker_id'] ) {
        echo <<<_END
            <a class="pull-right button-std mar-bot" name="slett_traad_btn" id="slett_traad_btn" href="#">
                <i class="fa fa-minus-square-o"></i> Slett tråd
            </a>
            <div class="clearfix"></div>
_END;
    }

    // Viser ut sideLinks
    echo '<div class="pull-right">';
    for ($i = 1; $i <= $side_teller; $i++) {
        if($i == $curr_side) {
            echo '<div class="traad_page_spacer traad_side_btn active pad-right pull-left">';
                echo $i;
            echo '</div>';
        } else {
            echo '<a class="traad_page_spacer" href="traad.php?ukat_id='
                        . $ukat_id . '&traad_id='
                        . $traad_id . '&side='
                        . $i . '"><div class="traad_side_btn pad-right pull-left">' . $i . '</div></a> ';
        }
    }
    $bilde = hentBilde($conn, $traad_bruker_id);
    $datosjekk = datoSjekk($traad_traad_dato);
    $likes = getLikes($conn, null, $traad_id);
    $traad_innhold = strip_tags($traad_traad_innhold, '<i><b><u>');


    if ($stmt = $conn->prepare("SELECT bruker_dato FROM bruker WHERE bruker_id = ?")) {
        $stmt->bind_param("i", $traad_bruker_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($bruker_dato);
        $stmt->fetch();
        $stmt->close();
    }


    $bruker_siden_traad = date("d-m-Y", strtotime($bruker_dato));


    echo <<<_END
        </div>
        <div class="traadtop"><h3>$traad_traad_tittel<br><small>Skrevet av 
            <a href="bruker.php?bruker_id=$traad_bruker_id">$traad_bruker_navn</a> 
            <i class="fa fa-clock-o"></i> $datosjekk</small></h3>
        </div>
        <div id="traadtable">
            <div class="table-row-group">
                <div class="table-row">
                    <div class="table-cell center traadleft skjul-liten">
                        <a href="bruker.php?bruker=$traad_bruker_id">
                            <img alt="profilbilde" class="avatar_forum" src="img/profilbilder/$bilde">
                            <div class="clearfix"></div>
                            $traad_bruker_navn
                        </a>
                        <div class="clearfix"></div>
                        <small>Medlem siden: <br>$bruker_siden_traad!</small>
                    </div>
                    <div class="table-cell traadright">
                        <i class="fa fa-clock-o"></i> $datosjekk<p class="traad_mobile"> av <a href="#">$traad_bruker_navn</a></p>
                        <div class="traad_innhold">
                            $traad_innhold
                        </div>
                        <ol class="likepost pull-right clearfix">
_END;
                            /* TODO: Sjekk erinnlogget() */
                            if (harLikt($conn, "traad", null, $traad_id, $_SESSION['bruker_id']) == false) {
                                echo '<li id="likepost_btn">
                                            <a href="includes/endringer.php?traad_id='
                                                . $traad_id . '&bruker_id='
                                                . $_SESSION['bruker_id'] . '&bruker_navn='
                                                . $_SESSION['bruker_navn'] . '"> 
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
    $sql = "SELECT innlegg_id, traad_id, innlegg_innhold, innlegg_dato, ukat_id, bruker_id, bruker_navn FROM innlegg WHERE traad_id = ? LIMIT ?, ?";
    $stmt_pagedata = $conn->prepare($sql);
    $stmt_pagedata->bind_param("iii", $traad_id, $forste_innlegg, $innlegg_per_side);
    $stmt_pagedata->execute();
    $stmt_pagedata->store_result();
    $stmt_pagedata->bind_result($pagedata_innlegg_id, $pagedata_traad_id, $pagedata_innlegg_innhold, $pagedata_innlegg_dato,
                                $pagedata_ukat_id, $pagedata_bruker_id, $pagedata_bruker_navn);






    while ($stmt_pagedata->fetch()) {
        $bilde = hentBilde($conn, $pagedata_bruker_id);
        $dato = datoSjekk($pagedata_innlegg_dato);
        $innlegg_innhold = strip_tags($pagedata_innlegg_innhold, '<i><b><u>');

        if ($stmt = $conn->prepare("SELECT bruker_dato FROM bruker WHERE bruker_id = ?")) {
            $stmt->bind_param("i", $pagedata_bruker_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($innlegg_bruker_dato);
            $stmt->fetch();
            $stmt->close();
        }

        $bruker_siden_innlegg = date("d-m-Y", strtotime($innlegg_bruker_dato));

        echo <<<_END
            <div class="table-row traadspacer"></div>
            <div class="table-row">
                <div class="table-cell center traadleft skjul-liten">
                    <a href="bruker.php?bruker=$pagedata_bruker_id">
                        <img alt="avatar" class="avatar_forum" src="img/profilbilder/$bilde">
                        <div class="clearfix"></div>
                        $pagedata_bruker_navn
                    </a>
                    <div class="clearfix"></div>
                    <small>Medlem siden: <br>$bruker_siden_innlegg</small>
                </div>
                <div class="table-cell traadright">
_END;
                if (innlogget() == true && bruker_level() == "admin" || innlogget() && $pagedata_bruker_id == $_SESSION['bruker_id']) {
                        echo <<<_END
                            <input type="button" class="pull-right button-std mar-bot mar-right" id="$pagedata_innlegg_id" 
                                   value="slett innlegg" onclick="slettPost(id)">
                            <div class="clearfix"></div>
_END;
                    }
                echo <<<_END
                
                    <i class="fa fa-clock-o"></i> $dato<p class="traad_mobile"> av <a href="#">$traad_bruker_navn</a></p>
                    <div class="innlegg_innhold">
                        $pagedata_innlegg_innhold
                    </div>
                </div></div>
_END;
    }
    $stmt_pagedata->close();
    echo <<<_END
    
        </div>
        <div id="innleggErr" class="red clearfix"></div>
        <form name="form_svar" action="includes/endringer.php?ukat_id=$ukat_id&traad_id=$traad_id" method="post" onsubmit="return innleggVal()">
            
            <textarea name="innlegg_innhold" id="innlegg_innhold" placeholder="Har du noe spennende å bidra med..?"></textarea>
            <input type="submit" name="svar_btn" id="svar_btn" class="std_btn" value="Svar" onclick="post()">
        </form>
_END;
} else if (innlogget() == false) {
    echo "Du må logge inn for å lese dette.";
}

// Hvis vi skal lage ny tråd
if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && isset($_GET['nytraad']) && innlogget()) {
    $kat_id = $_GET['kat_id'];
    $ukat_id = $_GET['ukat_id'];

    echo <<<_END
    <form action="includes/endringer.php?kat_id=$kat_id&ukat_id=$ukat_id" method="post" onsubmit="return traadVal()">
        <span id="TittelErr"></span>
        <input type="text" name="ny_traad_navn" id="ny_traad_navn" class="std_input mar-bot" placeholder="Trådnavn">
        <span id="InnholdErr"></span>
        <textarea name="ny_traad_text" id="ny_traad_text" class="std_input" placeholder="Hva har du på hjertet...?"></textarea>

        <div id="traad_buttons" clasS="pull-right">
            <div class="traad_buttons_one pull-left mar-bot pad-right">
                <input type="submit" name="ny_traad_submitt" id="ny_traad_submitt" value="Lag traad">
            </div>
            <div class="traad_buttons_two pull-left mar-bot">
                <input type="button" class="std_btn_avbryt" value="Avbryt">
            </div>
        </div>
    </form>
_END;
}
?>

<!-- SLETT TRÅD -->
<div id="slett_traad">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette tråd?</h2>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_kat_form" name="slett_kat_form" method="post" action="includes/endringer.php?slett_traad_id=' . $traad_id  . '&ukat_id=' . $ukat_id . '">' ?>
            <div class="popup-divider">
                <?php echo '<p class="white">Er du sikker på at du vil slette tråden ' . $traad_traad_tittel .  '?</p>' ?>
            </div>
            <button type="submit" name="slett_traad_submit" id="slett_traad_submit" class="button-lukk">Slett den</button>
        </form>
    </div>
</div>

<!-- SLETT INNLEGG -->
<div id="slett_innlegg">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette innlegg?</h2>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <div class="popup-divider">
            <p class="white">Er du sikker på at du vil slette dette innlegget?</p>
        </div>
        <button type="submit" name="slett_innlegg_btn" id="slett_innlegg_btn" class="button-lukk">Slett den</button>
    </div>
</div>

<script type="text/javascript">
<?php

    $ukat_id = $_GET['ukat_id'];
    $traad_id = $_GET['traad_id'];

    $traad_sql = "SELECT bruker_id FROM traad WHERE ukat_id = ? AND traad_id = ?";
    $stmt_traad = $conn->prepare($traad_sql);
    $stmt_traad->bind_param("ii", $ukat_id, $traad_id);
    $stmt_traad->execute();
    $stmt_traad->store_result();
    $stmt_traad->bind_result($traad_bruker_id);
    $stmt_traad->fetch();
    $stmt_traad->close();

    $traad_sql = "SELECT bruker_id FROM innlegg WHERE ukat_id = ? AND innlegg_id = ?";
    $stmt_traad = $conn->prepare($traad_sql);
    $stmt_traad->bind_param("ii", $ukat_id, $innlegg_id);
    $stmt_traad->execute();
    $stmt_traad->store_result();
    $stmt_traad->bind_result($innlegg_bruker_id);
    $stmt_traad->fetch();
    $stmt_traad->close();
?>

<?php   if (innlogget() && $traad_bruker_id == $_SESSION['bruker_id'])  { ?>
         $(document).ready(function() {

             $("#slett_traad_btn").click(function () {
                 $("#slett_traad").show();
             });
         });
    <?php } ?>
<?php if (innlogget() && $innlegg_bruker_id == $_SESSION['bruker_id'])  { ?>
        $(document).ready(function() {

            $("#slett_post_btn").click(function () {
                $("#slett_post").show();
            });
        });
    <?php } ?>
</script>

<?php
require_once 'includes/footer.php';
?>


