<?php
require_once 'functions.php';
require_once 'db_connect.php';
// error_reporting(0);
?>
<script src="js/validate.js"></script>

<div class="container">
    <div class="page-header">
        <h1 class="pull-left"><a id="logo-text" href="http://localhost/forum/www/"><b>FORUM</b> FOR <i>HSN</i> STUDENTER</a>
        </h1>
        
        <?php
        if ($_SESSION['innlogget']) {
            echo "Velkommen, " . $_SESSION['bruker_navn'];
            echo    "<ol class=\"breadcrumb pull-right\">";
            echo        "<li><a id=\"profil\" href=\"#\" data-rel=\"popup\">Profil</a></li>";
            echo        "<li><a id=\"logg_ut\" href=\"includes/loggut.php\" data-rel=\"popup\">Logg ut</a></li>";
            echo     "</ol>";
        }
        else {
            echo    "<ol class=\"breadcrumb pull-right\">";
            echo        "<li><a id=\"logg_inn\" href=\"#\" data-rel=\"popup\">Logg inn</a></li>";
            echo        "<li><a id=\"registrer\" href=\"#\" data-rel=\"popup\">Registrer deg</a></li>";
            echo     "</ol>";
        }
        ?>
        <div class="clearfix"></div>
    </div>

    <div class="popup-container center">
      <p class="white">Det oppstod en feil under registreringen.<br>Venligst prøv igjen...</p>
      <p class="white">Skulle problemet fortsette, ta <a class="link-light" href="#">kontakt</a> med administrator og oppgi denne feilkoden:</p>
      <h2 style="display: none" class="feilkode1">Feilkode 1</h2>
      <h2 style="display: none" class="feilkode2">Feilkode 2</h2>
      <h2 style="display: none" class="feilkode3">Feilkode 3</h2>
      <h2 style="display: none" class="feilkode4">Feilkode 4</h2>

    <!-- Feilkoder -->
    <div id="registrer-feil">
        <div class="popup-header center">
            <h2 class="popup-header-text icon-error"> Uff da...</h2>
            <hr class="hr-popup">
        </div>

        <div class="popup-container center">
            <p class="white">Det oppstod en feil under registreringen.<br>Venligst prøv igjen...</p>
            <p class="white">Skulle problemet fortsette, ta <a class="link-light" href="#">kontakt</a> med administrator
                og oppgi denne feilkoden:</p>
            <h2 style="display: none" class="feilkode1">Feilkode 1</h2>
            <h2 style="display: none" class="feilkode2">Feilkode 2</h2>
            <h2 style="display: none" class="feilkode3">Feilkode 3</h2>
            <h2 style="display: none" class="feilkode4">Feilkode 4</h2>

            <button form="registrer" name="button-avbryt" type="submit" class="registrer-button-lukk"><span
                    class=""></span> Lukk
            </button>
        </div>
    </div>

    <!-- Mail sendt -->
    <div class="registrer-mail-sendt">
        <div class="popup-header center">
            <h2 class="white"><i class="fa fa-check-square-o"></i> Mail sendt!</h2>
            <hr class="hr-popup">
        </div>

        <div class="popup-container center">
            <h1 class="white">Takk for din registrering!</h1>
            <p class="white">Fullfør registreringen ved å sjekke eposten din.<br>Du kan nå lukke dette vinduet.</p>
            <button form="registrer" type="submit" class="registrer-button-lukk">Lukk</button>
        </div>
    </div>

    <!-- Laster... (sender mail)  -->
    <div class="registrer-box-loading">
        <img class="opptatt" src="http://localhost/forum/www/img/opptatt.gif">
    </div>

    <!-- REGISTER BOX -->
    <div id="registrer-box">
        <div class="popup-header center">
            <div class="pull-left" style="width: 70%">
                <h2 class="white icon-user pull-right"><i class="fa fa-user-plus"></i> Registrer deg!</h2>
            </div>
            <div class="pull-right half" style="width: 30%;">
                <i class="registrer-icon-lukk fa fa-times fa-2x red pull-right"></i>
            </div>
        </div>

        <div class="popup-container center">
            <form id="registrerForm" name="registrer" method="post" action="http://localhost/forum/www/registrer.php" onsubmit="return sjekkSkjema()">
                <div class="popup-divider">
                    <input type="text" name="brukernavn_reg" id="brukernavn_reg" class="popup-input valid"
                           placeholder="Brukernavn" onblur="sjekkBNavn(id)">
                    <span id="bnavnErr">Brukernavnet stemmer ikke</span>
                </div>

                <div class="popup-divider-half pull-left">
                    <input type="text" name="fornavn_reg" id="fornavn_reg" class="popup-input input-pull-left"
                           placeholder="Fornavn" onblur="sjekkFNavn(id)">
                    <span id="fnavnErr">Fornavnet stemmer ikke</span>
                </div>

                <div class="popup-divider-half pull-right">
                    <input type="text" name="etternavn_reg" id="etternavn_reg" class="popup-input pull-right"
                           placeholder="Etternavn" onblur="sjekkENavn(id)">
                    <span id="enavnErr" class="pull-right">Etternavnet stemmer ikke</span>
                </div>

                <div class="popup-divider clearfix">
                    <input type="text" name="epost_reg" id="epost_reg" class="popup-input" placeholder="Epost-adresse"
                           onblur="sjekkEpost(id)">
                    <span id="epostErr">Eposten stemmer ikke</span>
                </div>

                <div class="popup-divider">
                    <input type="password" name="pass_reg" id="pass_reg" class="popup-input" placeholder="Passord"
                           onblur="sjekkPass(id)">
                    <span id="passErr">Passer ikke kriteriene!</span>
                </div>

                <div class="popup-divider">
                    <input type="password" name="pass_two_reg" id="pass_two_reg" class="popup-input"
                           placeholder="Gjenta passord" onblur="sjekkPassTo(id)">
                    <span id="passTwoErr">Samsvarer ikke med passordet over!</span>
                </div>
                <input type="submit" name="registrer-btn" id="registrer-submitt" value="Fullfør">
            </form>
        </div>
    </div>

    <!-- Logginn.php -->
    <div id="logginn-box">
        <div class="popup-header center">
            <div class="pull-left" style="width: 70%">
                <h2 class="white icon-user pull-right"><i class="fa fa-sign-in"></i> Logg inn</h2>
            </div>
            <div class="pull-right half" style="width: 30%;">
                <i class="logginn-icon-lukk fa fa-times fa-2x red pull-right"></i>
            </div>
        </div>

        <div class="popup-container center">
            <form id="logginnForm" name="logginn" method="post" action="http://localhost/forum/www/logginn.php">
                <div class="popup-divider">
                    <input type="text" name="brukernavn_logginn" id="brukernavn_logginn" class="popup-input"
                           placeholder="Brukernavn">
                </div>

                <div class="popup-divider">
                    <input type="password" name="passord_logginn" id="passord_logginn" class="popup-input"
                           placeholder="Passord">
                </div>
                <input type="submit" name="logginn-btn" id="logginn-submitt" value="LOGG INN">
            </form>
        </div>
    </div>

    <!-- BEKREFTBRUKER.php -->
    <!-- Bruker laget -->
    <div class="registrer-box-success">
        <div class="popup-header center">
            <h2 class="popup-header-text icon-success"> Registrering fullført!</h2>
            <hr class="hr-popup">
        </div>

        <div class="popup-container center">
            <h1 class="white">Bruker aktivert</h1>
            <p class="white">Velkommen til HSN forum!<br>Du kan nå logge deg inn.</p>
            <button form="registrer" name="button-avbryt" type="submit" class="registrer-button-lukk"><span
                    class=""></span> Lukk
            </button>
        </div>
    </div>
<?php
echo "Dato og tid: " . date("Y-d-m G:i:s");
require_once(__DIR__ . '/../chatbox.php');
?>