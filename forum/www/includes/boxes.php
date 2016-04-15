<?php
require_once 'db_connect.php';
?>

<!-- REGISTRER.php -->
<!-- Feilkoder -->
<div id="registrer-feil">
    <div class="popup-header center">
        <h2 class="popup-header-text icon-error white"><i class="fa fa-exclamation-triangle"></i> Uff da...</h2>
        <hr class="hr-popup">
    </div>

    <div class="popup-container center">
        <p class="white">Det oppstod en feil under registreringen.<br>Venligst prøv igjen...</p>
        <p class="white">Skulle problemet fortsette, ta <a class="link-light" href="#">kontakt</a> med administrator
            og oppgi denne feilkoden:</p>
        <h2 style="display: none" id="feilkode" class="white"></h2>
        <button name="button-avbryt" type="submit" class="button-lukk"> Lukk</button>
    </div>
</div>

<!-- Mail sendt -->
<div id="registrer-mail-sendt">
    <div class="popup-header center">
        <h3 class="white"><i class="fa fa-check-square-o"></i> Mail sendt!</h3>
    </div>

    <div class="popup-container center">
        <h1 class="white">Takk for din registrering!</h1>
        <p class="white">Fullfør registreringen ved å sjekke eposten din.<br>Du kan nå lukke dette vinduet.</p>
        <button type="submit" class="button-lukk">Lukk</button>
    </div>
</div>

<!-- REGISTER BOX -->
<div id="registrer-box">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h3 class="white pull-right"><i class="fa fa-user-plus"></i> Registrer deg!</h3>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="registrer-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>

    <div class="popup-container center">
        <form id="registrerForm" name="registrer" method="post" action="registrer.php" onsubmit="return sjekkSkjema()">
            <div class="popup-divider">
                <input type="text" name="brukernavn_reg" id="brukernavn_reg" class="popup-input valid"
                       placeholder="Brukernavn" onblur="sjekkBNavn(id)">
                <span id="bnavnErr"></span> <span id="sjekkBnavn"></span>
            </div>

            <div class="popup-divider-half pull-left">
                <input type="text" name="fornavn_reg" id="fornavn_reg" class="popup-input pull-left"
                       placeholder="Fornavn" onblur="sjekkFNavn(id)">
                <span id="fnavnErr"></span>
            </div>

            <div class="popup-divider-half pull-right">
                <input type="text" name="etternavn_reg" id="etternavn_reg" class="popup-input pull-right"
                       placeholder="Etternavn" onblur="sjekkENavn(id)">
                <span id="enavnErr" class="pull-right"></span>
            </div>

            <div class="popup-divider clearfix">
                <input type="text" name="epost_reg" id="epost_reg" class="popup-input" placeholder="Epost-adresse"
                       onkeyup="sjekkEpost(id)" onblur="sjekkEpost(id)">
                <span id="epostErr"></span> <span id="sjekkEpost"></span>
            </div>

            <div class="popup-divider">
                <input type="password" name="pass_reg" id="pass_reg" class="popup-input" placeholder="Passord"
                       onblur="sjekkPass(id)">
                <span id="passErr"></span>
            </div>

            <div class="popup-divider">
                <input type="password" name="pass_two_reg" id="pass_two_reg" class="popup-input"
                       placeholder="Gjenta passord" onblur="sjekkPassTo(id)">
                <span id="passTwoErr"></span>
            </div>
            <input type="submit" name="registrer-btn" id="registrer_submitt" value="Fullfør">
        </form>
    </div>
</div>

<!-- Logginn.php -->
<div id="logginn-box">
    <div class="popup-header center">
        <div class="pull-left" style="width: 65%">
            <h3 class="white pull-right"><i class="fa fa-sign-in"></i> Logg inn</h3>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>

    <div class="popup-container center">
        <form id="logginnForm" name="logginn" method="post" action="logginn.php">
            <div class="popup-divider">
                <input type="text" name="brukernavn_logginn" id="brukernavn_logginn" class="popup-input"
                       placeholder="Brukernavn">
                <span id="ikkeAktiv"></span>
            </div>

            <div class="popup-divider">
                <input type="password" name="passord_logginn" id="passord_logginn" class="popup-input"
                       placeholder="Passord">
                <span id="feilPass"></span>
            </div>
            <input type="submit" name="logginn-btn" id="logginn_submitt" value="LOGG INN">
        </form>
    </div>
</div>

<!-- BEKREFTBRUKER.php -->
<!-- Bruker laget -->
<div class="registrer-box-success">
    <div class="popup-header center">
        <h2 class="popup-header-text icon-success white"><i class="fa fa-check-square-o"></i> Registrering fullført!</h2>
        <hr class="hr-popup">
    </div>

    <div class="popup-container center">
        <h1 class="white">Bruker aktivert</h1>
        <p class="white">Velkommen til HSN forum!<br>Du kan nå logge deg inn.</p>
        <button name="button-avbryt" type="submit" class="button-lukk"> Lukk</button>
    </div>
</div>

<!-- NY KATEGORI -->
<div id="ny_kat">
    <div class="popup-header center">
        <div class="pull-left" style="width: 70%">
            <h3 class="white icon-user pull-right"><i class="fa fa-plus-square-o"></i> Legg til kategori</h3>
        </div>
        <div class="pull-right half" style="width: 30%;">
            <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
        </div>
    </div>

    <div class="popup-container center">
        <form id="ny_kat_form" name="ny_kat_form" method="post" action="includes/endringer.php">
            <div class="popup-divider">
                <input type="text" name="ny_kat_navn" id="ny_kat_navn" placeholder="Kategori navn" class="popup-input">
            </div>
            <input type="submit" name="ny_kat_btn" id="ny_kat_submit" value="LEGG TIL">
        </form>
    </div>
</div>