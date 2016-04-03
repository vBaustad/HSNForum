<?php
require_once 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Forum for studenter på HSN avdeling Bø</title>
    <!-- CSS, FONTS AND OTHER LIBS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800,400italic' rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet-m.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body>

<?php
$kat_id = mysqli_real_escape_string($conn, $_GET['kat_id']);
$finnkatnavn = mysqli_query($conn, "SELECT kat_navn FROM kategori WHERE kategori.kat_id = '$kat_id'");
$katnavn = mysqli_fetch_assoc($finnkatnavn);
$ukat = mysqli_query($conn, "SELECT kat_id, ukat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge FROM underkategori WHERE `kat_id` = '$kat_id' ");

require_once 'includes/header.php';


/* Viser alle underkategorier */
if (isset($_GET['kat_id']) && !isset($_GET['ukat_id'])) {

    if (innlogget() == true && bruker_level() == "admin") {
        echo '<a class="pull-right button-std mar-bot" id="ny_ukat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny underkategori</a>';
        echo '<a class="pull-right button-std mar-bot mar-right" id="slett_kat_btn" href="#"><i class="fa fa-minus-square-o"></i> Slett kategori</a>';
    }

    if ($ukat) {
        if (mysqli_num_rows($ukat) > 0) {
            echo '<table class="main-table table forum table-striped">';
            echo '  <thead>';
            echo '       <tr>';
            echo '            <th class="cell-stat"></th>';
            echo '            <th class="th_text">' . $katnavn['kat_navn'] . '</th>';
            echo '            <th class="cell-stat text-center skjul-liten skjul-medium">Emner</th>';
            echo '            <th class="cell-stat text-center skjul-liten skjul-medium">Innlegg</th>';
            echo '            <th class="cell-stat-2x skjul-liten skjul-medium">Siste Innlegg</th>';
            echo '      </tr>';
            echo '  </thead>';
            echo '  <tbody>';

            while ($row_ukat = mysqli_fetch_assoc($ukat)) {
                $ukat_id = $row_ukat['ukat_id'];
                // For HTML validering
                $ukat_navn = (str_replace(" ", "_", $row_ukat['ukat_navn']));

                $antposts = mysqli_query($conn, "SELECT COUNT(tråd_id) as antPosts FROM tråd WHERE ukat_id = '$ukat_id'");
                $antposts_result = mysqli_fetch_assoc($antposts);

                $siste_innlegg = mysqli_query($conn, "SELECT tråd_dato, bruker_navn, bruker_id FROM tråd WHERE ukat_id = '$ukat_id' ORDER BY tråd_dato DESC LIMIT 1");
                $siste_innlegg_row = mysqli_fetch_assoc($siste_innlegg);

                echo '      <tr>';
                echo '          <td class="center"><i class="'
                                                            . $row_ukat['ukat_img'] . ' '
                                                            . $row_ukat['ukat_img_farge'] . '"></i></span></td>';

                echo '          <td><h4><a href="kategori.php?kat_id='
                                                            . $row_ukat['kat_id']
                                                            . '&ukat_id=' . $row_ukat['ukat_id']
                                                            . '&ukat_navn=' . $ukat_navn . '">'
                                                            . $row_ukat['ukat_navn'] . '</a><br><small>'
                                                            . $row_ukat['ukat_beskrivelse'] . '</small></h4></td>';

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

    $posts = mysqli_query($conn, "SELECT * FROM tråd WHERE `ukat_id` = '$ukat_id' ");

    if (innlogget() && bruker_level() == "admin") {
        echo '<a class="pull-right button-std mar-bot" id="ny_traad_btn" href="traad.php?ukat_id=' . $ukat_id . '"><i class="fa fa-plus-square-o"></i> Ny post</a>';
        echo '<a class="pull-right button-std mar-bot mar-right" id="slett_ukat_btn" href="#"><i class="fa fa-minus-square-o"></i> Slett underkategori</a>';
    }
    elseif (innlogget() && bruker_level() == "regular") {
        echo '<a class="pull-right button-std mar-bot" id="ny_traad_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny post</a>';
    }

    echo $ukat_navn;

    if ($posts->num_rows > 0) {
        echo '<table class="main-table table forum table-striped">';
        echo '  <thead>';
        echo '       <tr>';
        echo '            <th class="cell-stat"></th>';
        echo '            <th><h2>Tråd navn</h2></th>';
        echo '            <th class="cell-stat-2x text-center skjul-liten skjul-medium">Antal svar</th>';
        echo '            <th class="cell-stat-2x skjul-liten skjul-medium">Siste svar</th>';
        echo '      </tr>';
        echo '  </thead>';
        echo '  <tbody>';

        while ($row_posts = mysqli_fetch_assoc($posts)) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4><a href="traad.php?ukat_id=' . $ukat_id
                                         . '&tråd_id=' . $row_posts['tråd_id'] . '">'
                                            . $row_posts['tråd_tittel']
                                            . '</a><br><small>
                          <a href="#">'
                          . $row_posts['bruker_navn'] . '</a> @ '
                          . $row_posts['tråd_dato'] . '</small><h4></td>';
            echo '<td class="center">??</td>';
            echo '<td> ?? </td>';
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
        <?php echo '<form id="slett_kat_form" name="slett_kat_form" method="post" action="http://localhost/forum/www/includes/endringer.php?slett_id=' . $kat_id .'">' ?>
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
        <?php echo '<form id="ny_ukat_form" name="ny_ukat_form" method="post" action="http://localhost/forum/www/includes/endringer.php?kat_id=' . $kat_id . '">' ?>
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
        <?php echo '<form id="slett_ukat_form" name="slett_ukat_form" method="post" action="http://localhost/forum/www/includes/endringer.php?slett_ukat_id=' . $ukat_id .'&kat_id=' . $kat_id . '">' ?>
        <div class="popup-divider">
            <?php echo '<p class="white">Er du vikker på at du vil slette underkategorien ' . $ukat_navn .  '?</p>' ?>
        </div>
        <button type="submit" name="slett_ukat_btn" class="button-lukk">Slett den</button>
        </form>
    </div>
</div>
