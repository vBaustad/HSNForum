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
    <link rel="stylesheet" type="text/css" href="http://localhost/forum/www/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/forum/www/css/stylesheet-m.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body>

<?php
require_once(__DIR__ . '/includes/db_connect.php');
require_once(__DIR__ . '/includes/header.php');

if ($_SESSION && $_SESSION['bruker_level'] == '2') {
    echo '<a class="pull-right button-std mar-bot" id="ny_kat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny kategori</a>';
}

$kat = mysqli_query($conn, "SELECT kat_id, kat_navn FROM kategori");

if ($kat) {
    if (mysqli_num_rows($kat) > 0) {

        $ukat_teller = 0;

        while ($row_kat = mysqli_fetch_assoc($kat)) {
            if ($row_kat['kat_id']) {
                echo '<table class="main-table table forum table-striped">';
                echo '  <thead>';
                echo '       <tr>';
                echo '            <th class="cell-stat"></th>';
                echo '            <th><h2><a href="kategori.php?kat_id=' . $row_kat['kat_id'] .'">' . $row_kat['kat_navn'] . '</a></h2></th>';
                echo '            <th class="cell-stat text-center hidden-sm">Emner</th>';
                echo '            <th class="cell-stat text-center hidden-sm">Innlegg</th>';
                echo '            <th class="cell-stat-2x hidden-xs hidden-sm">Siste Innlegg</th>';
                echo '      </tr>';
                echo '  </thead>';
                echo '  <tbody>';

                $ukat_teller = $row_kat['kat_id'];
            }

            $ukat = mysqli_query($conn, "SELECT kat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge FROM underkategori WHERE kat_id = '$ukat_teller'");

            if ($ukat) {
                if (mysqli_num_rows($ukat) > 0) {
                    while ($row_ukat = mysqli_fetch_assoc($ukat)) {
                        echo '      <tr>';
                        echo '          <td class="center"><i class="' . $row_ukat['ukat_img'] . $row_ukat['ukat_img_farge'] . '"></i></span></td>';
                        echo '          <td><h4><a href="#">' . $row_ukat['ukat_navn'] . '</a><br><small>' . $row_ukat['ukat_beskrivelse'] . '</small></h4></td>';
                        echo '          <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>';
                        echo '          <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>';
                        echo '          <td class="hidden-xs hidden-sm">av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small></td>';
                        echo '      </tr>';
                    }
                }
            }
            echo '  </tbody>';
            echo '</table>';
        }
    }
}

require_once(__DIR__ . '/includes/footer.php');
?>