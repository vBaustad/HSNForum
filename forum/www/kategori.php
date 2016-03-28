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

    $_SESSION['kat_id'] = $kat_id;
?>

<!-- SLETT KATEGORI -->
<div id="slett_kat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h2 class="white icon-user pull-right"><i class="fa fa-minus-square-o"></i> Slette kategori?</h2>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="logginn-icon-lukk fa fa-times fa-2x red pull-right"></i>
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

<?php
require_once 'includes/header.php';

echo '<h1>' . $katnavn['kat_navn'] . '</h1>';
if (innlogget() == true && eradmin() == true) {
    echo '<a class="pull-right button-std mar-bot" id="ny_ukat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny underkategori</a>';
    echo '<a class="pull-right button-std mar-bot mar-right" id="slett_kat_btn" href="#"><i class="fa fa-minus-square-o"></i> Slett kategori</a>';
}

$ukat = mysqli_query($conn, "SELECT kat_id, ukat_navn, ukat_beskrivelse, ukat_img FROM underkategori WHERE `kat_id` = '$kat_id' ");

if ($ukat) {
    if (mysqli_num_rows($ukat) > 0) {
        echo '<table class="main-table table forum table-striped">';
        echo '  <thead>';
        echo '       <tr>';
            echo '            <th class="cell-stat"></th>';
            echo '            <th></th>';
                echo '            <th class="cell-stat text-center skjul-liten skjul-medium">Emner</th>';
            echo '            <th class="cell-stat text-center skjul-liten skjul-medium">Innlegg</th>';
            echo '            <th class="cell-stat-2x skjul-liten skjul-medium">Siste Innlegg</th>';
            echo '      </tr>';
        echo '  </thead>';
        echo '  <tbody>';

        while ($row_ukat = mysqli_fetch_assoc($ukat)) {
            echo '      <tr>';
            echo '          <td class="center"><i class="' . $row_ukat['ukat_img'] . '"></i></span></td>';
            echo '          <td><h4><a href="#">' . $row_ukat['ukat_navn'] . '</a><br><small>' . $row_ukat['ukat_beskrivelse'] . '</small></h4></td>';
            echo '          <td class="text-center skjul-liten skjul-medium"><a href="#">1 234</a></td>';
            echo '          <td class="text-center skjul-liten skjul-medium"><a href="#">4 321</a></td>';
            echo '          <td class="skjul-liten skjul-medium">av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small></td>';
            echo '      </tr>';
        }

        echo '  </tbody>';
        echo '</table>';
    }
}
    require_once 'includes/footer.php';
?>
