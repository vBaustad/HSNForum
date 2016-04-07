<?php
require_once 'includes/db_connect.php';

$kat_id = $_GET['kat_id'];

$ukat = "SELECT kat_id, ukat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge 
                              FROM underkategori WHERE `kat_id` = ? ";
$stmt_ukat = $conn->prepare($ukat);
$stmt_ukat->bind_param("i", $kat_id);
$stmt_ukat->execute();
$ukat_res = $stmt_ukat->get_result();

$finnkatnavn = "SELECT kat_navn FROM kategori WHERE kategori.kat_id = ?";
$stmt = $conn->prepare($finnkatnavn);
$stmt->bind_param("i", $kat_id);
$stmt->execute();
$res = $stmt->get_result();
$katnavn = $res->fetch_assoc();
$stmt->close();


require_once 'includes/header.php';
require_once 'chatbox.php';
require_once 'includes/boxes.php';


/* Viser alle underkategorier */
if (isset($_GET['kat_id']) && !isset($_GET['ukat_id'])) {

    if (innlogget() == true && bruker_level() == "admin") {
        echo '<a class="pull-right button-std mar-bot" id="ny_ukat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny underkategori</a>';
        echo '<a class="pull-right button-std mar-bot mar-right" id="slett_kat_btn" href="#"><i class="fa fa-minus-square-o"></i> Slett kategori</a>';
    }

    if ($ukat_res) {

        if ($ukat_res->num_rows > 0) {

            echo '<table class="main-table table forum table-striped">';
            echo '  <thead>';
            echo '       <tr>';
            echo '            <th class="rad-bredde"></th>';
            echo '            <th class="th_text">' . $katnavn['kat_navn'] . '</th>';
            echo '            <th class="rad-bredde text-center skjul-liten skjul-medium">Emner</th>';
            echo '            <th class="rad-bredde text-center skjul-liten skjul-medium">Innlegg</th>';
            echo '            <th class="rad-bredde-2x skjul-liten skjul-medium">Siste Innlegg</th>';
            echo '      </tr>';
            echo '  </thead>';
            echo '  <tbody>';

            while ($ukat = $ukat_res->fetch_assoc()) {
                $ukat_id = $ukat['ukat_id'];
                // For HTML validering
                $ukat_navn = (str_replace(" ", "_", $ukat['ukat_navn']));

                $antposts = mysqli_query($conn, "SELECT COUNT(tråd_id) as antPosts FROM tråd WHERE ukat_id = '$ukat_id'");
                $antposts_result = mysqli_fetch_assoc($antposts);

                $siste_innlegg = mysqli_query($conn, "SELECT tråd_dato, bruker_navn, bruker_id FROM tråd WHERE ukat_id = '$ukat_id' ORDER BY tråd_dato DESC LIMIT 1");
                $siste_innlegg_row = mysqli_fetch_assoc($siste_innlegg);

                echo '      <tr>';
                echo '          <td class="center"><i class="'
                                                            . $ukat['ukat_img'] . ' '
                                                            . $ukat['ukat_img_farge'] . '"></i></span></td>';

                echo '          <td><h4><a href="kategori.php?kat_id='
                                                            . $ukat['kat_id']
                                                            . '&ukat_id=' . $ukat['ukat_id']
                                                            . '&ukat_navn=' . $ukat_navn . '">'
                                                            . $ukat['ukat_navn'] . '</a><br><small>'
                                                            . $ukat['ukat_beskrivelse'] . '</small></h4></td>';

                echo '          <td class="text-center skjul-liten skjul-medium"><a href="#">1 234</a></td>';

                echo '          <td class="text-center skjul-liten skjul-medium"><a href="#">' . $antposts_result['antPosts'] . '</a></td>';

                echo '          <td class="skjul-liten skjul-medium">av <a href="bruker.php?brukerid='
                                                            . $siste_innlegg_row['bruker_id'] . '">'
                                                            . $siste_innlegg_row['bruker_navn']
                                                            . '</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small></td>';
                echo '      </tr>';
            }
            echo '  </tbody>';
            echo '</table>';
        }
    }
}

/* Viser alle tråder i en underkateori */
if (isset($_GET['kat_id']) && isset($_GET['ukat_id']) && isset($_GET['ukat_navn'])) {
    $kat_id = $_GET['kat_id'];
    $ukat_id = $_GET['ukat_id'];
    $ukat_navn = (str_replace("_", " ", $_GET['ukat_navn']));

    $sql = "SELECT * FROM tråd WHERE `ukat_id` = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ukat_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if (innlogget() && bruker_level() == "admin") {
        echo '<a class="pull-right button-std mar-bot" id="ny_traad_btn" href="traad.php?ukat_id='
                                . $ukat_id . '"><i class="fa fa-plus-square-o"></i> Ny post</a>';
        echo '<a class="pull-right button-std mar-bot mar-right" id="slett_ukat_btn" href="#">
                    <i class="fa fa-minus-square-o"></i> Slett underkategori
              </a>';
    }
    elseif (innlogget() && bruker_level() == "regular") {
        echo '<a class="pull-right button-std mar-bot" id="ny_traad_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny post</a>';
    }

    echo $ukat_navn; // Erstatt denne med noe litt mer lekkert å se på :p

    if ($res->num_rows > 0) {
        echo '<table class="main-table table forum table-striped">';
        echo '  <thead>';
        echo '       <tr>';
        echo '            <th class="rad-bredde"></th>';
        echo '            <th><h2>Tråd navn</h2></th>';
        echo '            <th class="rad-bredde-2x text-center skjul-liten skjul-medium">Antal svar</th>';
        echo '            <th class="rad-bredde-2x skjul-liten skjul-medium">Siste svar</th>';
        echo '      </tr>';
        echo '  </thead>';
        echo '  <tbody>';

        while ($row = $res->fetch_assoc()) {

            // Teller antall innlegg og siste svar
            $sql = "SELECT COUNT(innlegg_id), max(innlegg_dato) AS sisteInnlegg,
                        ( SELECT bruker_id WHERE innlegg_dato = max(innlegg_dato) ) as Bruker_id
                        FROM innlegg";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $row['tråd_id']);
            $stmt->execute();
            $res_antinnlegg = $stmt->get_result();
            $row_antinnlegg = $res_antinnlegg->fetch_assoc();
            $stmt->close();
            
            

            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4><a href="traad.php?ukat_id=' . $ukat_id
                                         . '&tråd_id=' . $row['tråd_id'] . '">'
                                            . $row['tråd_tittel']
                                            . '</a><br><small>
                          <a href="#">'
                          . $row['bruker_navn'] . '</a> @ '
                          . $row['tråd_dato'] . '</small></h4></td>';
            echo '<td class="center">' . $row_antinnlegg['antInnlegg'] . '</td>'; /*Ant Svar*/
            echo '<td></td>'; /*Siste svar*/
            echo '</tr>';
        }
        echo '    </tbody>';
        echo '</table>';
    }
}

require_once 'includes/footer.php';
?>

<!-- SLETT KATEGORI -->
<div id="slett_kat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette kategori?</h2>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>
    <div class="popup-container center">
        <?php echo '<form id="slett_kat_form" name="slett_kat_form" method="post" action="includes/endringer.php?slett_id=' . $kat_id .'">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du vikker på at du vil slette kategorien ' . $katnavn['kat_navn'] .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_kat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div>

<!-- NY UNDERKATEGORI -->
<div id="ny_ukat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 80%">
            <h2 class="white icon-user pull-right"><i class="fa fa-plus-square-o"></i> Legg til underkategori</h2>
        </div>
        <div class="pull-right half" style="width: 20%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>

    <div class="popup-container center">
        <?php echo '<form id="ny_ukat_form" name="ny_ukat_form" method="post" action="includes/endringer.php?kat_id=' . $kat_id . '">' ?>
            <div class="popup-divider">
                <input type="text" name="ny_ukat_navn" id="ny_kat_navn" placeholder="Kategori navn" class="popup-input">
            </div>
            <div class="popup-divider">
                <input type="text" name="ny_ukat_besk" id="ny_kat_besk" placeholder="Kategori beskrivelse" class="popup-input">
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
</div>

<!-- SLETT UNDERKATEGORI -->
<div id="slett_ukat">
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
