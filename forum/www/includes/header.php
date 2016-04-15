<?php
require_once ('functions.php');
require_once ('db_connect.php');
?>
<!DOCTYPE html>
<html lang="no" xmlns="http://www.w3.org/1999/xhtml">
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

    <script src="js/jquery-1.12.3.min.js"></script>
</head>
<body>

<script src="js/validate.js"></script>
<script src="js/functions.js"></script>

<script type="text/javascript">
    <?php if (innlogget() == true && bruker_level(null, "session", null) == "admin")  { ?>
        $(document).ready(function() {
            $("#ny_ukat_btn").click(function () {
                $("#ny_ukat").show();
            })
            $("#slett_ukat_btn").click(function () {
                $("#slett_ukat").show();
            });

            $("#ny_kat_btn").click(function () {
                $("#ny_kat").show();
            });
            $("#slett_kat_btn").click(function () {
                $("#slett_kat").show();
            });
            
            $("#slett_traad_btn").click(function () {
                $("#slett_traad").show();
            });

            $("#slett_post_btn").click(function () {
                $("#slett_post").show();
            });
        });
    <?php } ?>
</script>

<div class="container">
    <div class="page-header">
        <h1 class="pull-left">
            <a id="logo-text" href="index.php"><b>FORUM</b> FOR <span class="purple">HSN</span> STUDENTER</a><br>
            <small>Lær, delta, forstå...</small>
        </h1>
        <div class="pull-right">
            <?php
            if (innlogget() == true) {
                $bilde = hentBilde($conn, $_SESSION['bruker_id']);
                echo    '<ol class="sti panel pull-right clearfix">';
                echo        '<li><img alt="profilbilde" class="avatar_panel" src="img/profilbilder/' . $bilde . '">';
                echo            '<a id="profil-img" href="bruker.php?bruker=' . $_SESSION['bruker_id'] . '">' . $_SESSION['bruker_navn'] . "</a></li> ";
                echo        '<li><a id="logg_ut" href="includes/loggut.php">Logg ut</a></li>';
                echo    '</ol>';
            }
            else {
                echo    '<ol class="sti panel pull-right">';
                echo        '<li><a id="logg_inn" href="#" data-rel="popup">Logg inn</a></li>';
                echo        '<li><a id="registrer" href="#" data-rel="popup">Registrer deg</a></li>';
                echo    '</ol>';
            }
            ?>
            <form class="sok_form pull-right clearfix" method="post" action="sok.php">
                <input name="sok_text" class="sok_text" type="search" placeholder="Søk i forumet...">
                <select name="sok_select" class="sok_select">
                    <option value="hele" selected>Hele forumet</option>
                    <option value="bruker">Brukere</option>
                    <option value="traader">Tråder</option>
                    <option value="ukategorier">Underkategorier</option>
                </select>
                <input type="submit" id="sok_btn" name="sok_btn" value="Søk">
            </form>
        </div> <!--pull-right-->
        <div class="clearfix"></div>
    </div> <!--Page-header-->