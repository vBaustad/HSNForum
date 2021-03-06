<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'chatbox.php';
require_once 'includes/boxes.php';

if ($stmt_ukat = $conn->prepare("SELECT kat_id, ukat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge FROM underkategori WHERE `kat_id` = ?")) {

    if (isset($_GET['kat_id'])) {
        $kat_id = $_GET['kat_id'];
    } else {
        die();
    }

    $stmt_ukat->bind_param("i", $kat_id);
    $stmt_ukat->execute();
    $stmt_ukat->store_result();
    $stmt_ukat->bind_result($sql_ukat_kat_id, $sql_ukat_ukat_id, $sql_ukat_ukat_navn, $sql_ukat_ukat_beskrivelse, $sql_ukat_ukat_img, $sql_ukat_ukat_img_farge);

    $katnavn = hvorErJeg($conn, "kat", $kat_id);

    /* Viser alle underkategorier*/
    if (isset($_GET['kat_id']) && !isset($_GET['ukat_id'])) {

        echo <<<_END
            <ol class="sti pull-left mar-bot">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="#">$katnavn</a></li>          
            </ol>
_END;

        if (innlogget() == true && bruker_level(null, "session", null) == "admin") {
            echo '<a class="pull-right button-std mar-bot" id="ny_ukat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny underkategori</a>';
            echo '<a class="pull-right button-std mar-bot mar-right" id="slett_kat_btn" href="#"><i class="fa fa-minus-square-o"></i> Slett kategori</a>';
        }

        if ($stmt_ukat->num_rows > 0) {

            echo '<table class="main-table table forum table-striped">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th class="rad-bredde"></th>';
                        echo '<th class="th_text">' . $katnavn . '</th>';
                        echo '<th class="rad-bredde text-center skjul-liten skjul-medium">Tråder</th>';
                        echo '<th class="rad-bredde text-center skjul-liten skjul-medium">Innlegg</th>';
                        echo '<th class="rad-bredde-2x skjul-liten skjul-medium">Siste aktivitet</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

            while ($stmt_ukat->fetch()) {
                // Henter antall tråder
                $anttraader = tellTraader($conn, "ukat", $sql_ukat_ukat_id);

                // Henter antall innlegg
                $antInnlegg = tellInnlegg($conn, "ukat", $sql_ukat_ukat_id);

                // Finner bruker som skrev siste traad
                $sistetraad = sistAktivUkat($conn, "traad", $sql_ukat_ukat_id);

                // Finner bruker som skreve siste innlegg
                $sisteInnlegg = sistAktivUkat($conn, "innlegg", $sql_ukat_ukat_id);

                if ($sisteInnlegg[0] > $sistetraad[0]) {
                    $siste_aktivitet = datoSjekk($sisteInnlegg[0]);
                    $siste_innlegg_navn = $sisteInnlegg[1];
                    $siste_innlegg_id = $sisteInnlegg[2];
                } else {
                    $siste_aktivitet = datoSjekk($sistetraad[0]);
                    $siste_innlegg_navn = $sistetraad[1];
                    $siste_innlegg_id = $sistetraad[2];
                }

                echo <<<_END
                    <tr>
                        <td class="center"><i class="$sql_ukat_ukat_img $sql_ukat_ukat_img_farge"></i></td>
                        <td><h4><a href="kategori.php?kat_id=$sql_ukat_kat_id&ukat_id=$sql_ukat_ukat_id">
                                                    $sql_ukat_ukat_navn</a><br><small>$sql_ukat_ukat_beskrivelse</small></h4></td>
                        <td class="text-center skjul-liten skjul-medium"><a href="#">$anttraader</a></td>
                        <td class="text-center skjul-liten skjul-medium"><a href="#">$antInnlegg</a></td>
                        <td class="skjul-liten skjul-medium">
_END;
                if ($anttraader > 0 || $antInnlegg > 0) {
                    echo <<<_END
                            av <a href="bruker.php?bruker=$siste_innlegg_id">$siste_innlegg_navn</a><br><small><i class="fa fa-clock-o"></i> $siste_aktivitet</small>
_END;
                } else {
                    echo '<small>ingen aktivitet enda</small>';
                }
                echo '</td>';
                echo '</tr>';
            }
                echo ' </tbody>';
            echo '</table>';
        }
    }

    /* Viser alle traader i en underkateori */
    if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && innlogget()) {
        $ukat_id = $_GET['ukat_id'];
        $ukatnavn = hvorErJeg($conn, "ukat", $ukat_id)[1];

        echo <<<_END
            <ol class="sti pull-left mar-bot">
                <li><a href="index.php">Hjem</a></li>
                <li><a href="kategori.php?kat_id=$kat_id">$katnavn</a></li>
                <li><a href="#">$ukatnavn</a></li>            
            </ol>
_END;
        if (innlogget() && bruker_level(null, "session", null) == "admin") {
            echo <<<_END
            <a class="pull-right button-std mar-bot" id="ny_traad_btn" href="traad.php?kat_id=$kat_id&ukat_id=$ukat_id&nytraad">
                <i class="fa fa-plus-square-o"></i> Ny tråd
            </a>
            <a class="pull-right button-std mar-bot mar-right" id="slett_ukat_btn" href="#">
                <i class="fa fa-minus-square-o"></i> Slett underkategori
            </a>
_END;
        }
        elseif (innlogget() && bruker_level(null, "session", null) == "regular") {
            echo <<<_END
            <a class="pull-right button-std mar-bot" id="ny_traad_btn" href="traad.php?kat_id=$kat_id&ukat_id=$ukat_id&nytraad">
                <i class="fa fa-plus-square-o"></i> Ny tråd
            </a>
_END;
        }

        if ($stmt_traad = $conn->prepare("SELECT traad_id, ukat_id, traad_tittel, traad_dato, bruker_navn, bruker_id FROM traad WHERE `ukat_id` = ?")) {
            $stmt_traad->bind_param("i", $ukat_id);
            $stmt_traad->execute();
            $stmt_traad->store_result();
            $stmt_traad->bind_result($sql_traad_id, $sql_traad_ukat_id, $sql_traad_tittel, $sql_traad_dato, $sql_traad_bruker_navn, $sql_traad_bruker_id);

            echo <<<_END
                <table class="main-table table table-striped">
                    <thead>
                        <tr>
                            <th class="rad-bredde"></th>
                            <th class="th_text">tråd navn</th>
                            <th class="rad-bredde-2x text-center skjul-liten skjul-medium">Antal svar</th>
                            <th class="rad-bredde-2x skjul-liten skjul-medium">Siste svar</th>
                        </tr>
                    </thead>
                <tbody>
_END;
            while ($stmt_traad->fetch()) {
                // Teller antall innlegg og siste svar
                $sql = "SELECT COUNT(innlegg_id) AS antInnlegg, max(innlegg_dato) AS sisteInnlegg,
                            ( SELECT bruker_id FROM innlegg WHERE innlegg_dato = ( SELECT max(innlegg_dato) FROM innlegg)) as bruker_id,
                            ( SELECT bruker_navn FROM innlegg WHERE  innlegg_dato = ( SELECT max(innlegg_dato ) FROM innlegg )) as bruker_navn
                            FROM innlegg WHERE traad_id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $sql_traad_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($sql_antInnlegg, $sql_sisteInnlegg, $sql_bruker_id, $sql_bruker_navn);
                $stmt->fetch();
                $stmt->close();

                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4><a href="traad.php?ukat_id=$ukat_id&traad_id=$sql_traad_id">
                                    $sql_traad_tittel
                                </a><br>
                                <small><a href="bruker.php?bruker=$sql_traad_bruker_id">$sql_traad_bruker_navn</a>
                                     @ $sql_traad_dato
                                </small></h4></td>
                        <td class="center skjul-liten skjul-medium">$sql_antInnlegg</td>
                        <td class="skjul-liten skjul-medium"><h4 class="siste_svar">
_END;
                    if ($sql_sisteInnlegg != NULL) {
                        echo '<a href="bruker.php?bruker='
                                                        . $sql_bruker_id . '">'
                                                        . $sql_bruker_navn . '</a></h4>
                        <small><i class="fa fa-clock-o"></i> ' . datoSjekk($sql_sisteInnlegg) . '</small>';
                    } else {
                        echo 'ingen svar enda</h4>';
                    }
                    echo '</td></tr>';
            }
                echo '</tbody></table>';
        }
    }
    // Brukere som ikke er logget inn kan ikke se dette.
    else if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && innlogget() == false) {
        echo '<ol class="sti red center"><li>Du må være logget inn for å se dette</li></ol>';
    }

}
?>

<!-- SLETT KATEGORI -->
<div id="slett_kat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h3 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette kategori?</h3>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_kat_form" name="slett_kat_form" method="post" action="includes/endringer.php?slett_id=' . $kat_id .'">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du sikker på at du vil slette kategorien ' . $katnavn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_kat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div> <!--slett_kat-->

<!-- NY UNDERKATEGORI -->
<div id="ny_ukat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 80%">
            <h3 class="white icon-user pull-right"><i class="fa fa-plus-square-o"></i> Legg til underkategori</h3>
        </div>
        <div class="pull-right half" style="width: 20%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>

    <div class="popup-container center">
        <?php echo '<form id="ny_ukat_form" name="ny_ukat_form" method="post" action="includes/endringer.php?kat_id=' . $kat_id . '">' ?>
            <div class="popup-divider">
                <input type="text" name="ny_ukat_navn" id="ny_ukat_navn" placeholder="Kategori navn" class="popup-input">
            </div>
            <div class="popup-divider">
                <input type="text" name="ny_ukat_besk" id="ny_ukat_besk" placeholder="Kategori beskrivelse" class="popup-input">
            </div>
            <div class="popup-divider">
                <select name="ny_ukat_img" class="popup-select">
                    <option value="fa fa-th-list fa-2x ">Velg bilde</option>
                    <option value="fa fa-exclamation-triangle fa-2x ">Trekantvarsel</option>
                    <option value="fa fa-info fa-2x ">Info</option>
                    <option value="fa fa-archive fa-2x ">Arkiv</option>
                    <option value="fa fa-comment-o fa-2x ">Kommentar</option>
                    <option value="fa fa-question fa-2x ">Hjelp</option>
                    <option value="fa fa-book fa-2x ">Bok</option>
                    <option value="fa fa-calendar-o fa-2x ">Kalender</option>
                    <option value="fa fa-thumbs-up fa-2x ">Tommel opp</option>
                    <option value="fa fa-thumbs-down fa-2x ">Tommel ned</option>
                    <option value="fa fa-heart-o fa-2x ">Hjerte</option>
                    <option value="fa fa-file fa-2x ">Ark/Papir</option>
                    <option value="fa fa-bar-chart fa-2x ">Diagram</option>
                    <option value="fa fa-database fa-2x ">Database</option>
                    <option value="fa fa-internet-explorer fa-2x ">Internet</option>
                    <option value="fa fa-linux fa-2x ">Linux</option>
                    <option value="fa fa-briefcase fa-2x ">Koffert</option>
                    <option value="fa fa-building-o fa-2x ">Bygning</option>
                    <option value="fa fa-globe fa-2x ">Globe</option>
                    <option value="fa fa-futbol-o fa-2x">Fotball</option>
                </select>
            </div>
            <div class="popup-divider">
                <select name="ny_ukat_img_farge" class="popup-select">
                    <option value="black">Velg farge</option>

                    <option value="black ">Sort</option>
                    <option value="red ">Rød</option>
                    <option value="blue ">Blå</option>
                    <option value="green ">Grønn</option>

                    <option value="cyan ">Cyan</option>
                    <option value="orange ">Oransje</option>
                    <option value="purple ">Lilla</option>
                </select>
            </div>
            <input type="submit" name="ny_ukat_btn" id="ny_ukat_submit" value="LEGG TIL">
        </form>
    </div>
</div> <!--ny-ukat-->

<!-- SLETT UNDERKATEGORI -->
<div id="slett_ukat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 80%">
            <h3 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette underkategori?</h3>
        </div>
        <div class="pull-right half" style="width: 20%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="includes/endringer.php?slett_ukat_id=' . $ukat_id .'&kat_id=' . $kat_id . '">' ?>
        <div class="popup-divider">
            <!-- TODO: ukat_navn out of bounce -->
            <?php echo '<p class="white">Er du sikker på at du vil slette underkategorien ' . $sql_ukat_ukat_navn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_ukat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div> <!--slett-ukat-->

<?php
require_once 'includes/footer.php';
