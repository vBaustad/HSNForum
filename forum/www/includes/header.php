<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/db_connect.php');
// error_reporting(0);
?>
<script src="js/validate.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // Lukker vindues
        $(".button-lukk, .registrer-icon-lukk, .logginn-icon-lukk, #registrer-avbryt").click(function () {
            $(".registrer-box-success").hide();
            $("#registrer-mail-sendt").hide();
            $("#registrer-feil").hide();
            $("#registrer-box").hide();
            $("#logginn-box").hide();
            $("#ny_kat").hide();
            $("#ny_ukat").hide();
            $("#slett_kat").hide();
            $("#logginn-box-ikke-aktiv").hide();
            /*RAMS OPP ALLE PÅ EN LINJE FOR FUCK SAKE!*/
        });

        // Skjul/vis bokser
        $("#registrer").click(function () {
            $("#registrer-box").show();
        });
        $("#logg_inn").click(function () {
            $("#logginn-box").show();
        });


    });
</script>

<?php

    if (innlogget() == true && eradmin() == true) {
        echo <<<_END
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#ny_ukat_btn").click(function () {
                            $("#ny_ukat").show();
                        })
            
                        $("#ny_kat_btn").click(function () {
                            $("#ny_kat").show();
                        });
                        
                        $("#slett_kat_btn").click(function () {
                            $("#slett_kat").show();
                        });
                    });
                </script>
_END;
    }
?>

<div class="container">
    <div class="page-header">
        <h1 class="pull-left"><a id="logo-text" href="http://localhost/forum/www/"><b>FORUM</b> FOR <i>HSN</i> STUDENTER</a></h1>
        
        <?php
        if (innlogget() == true) {
            echo    '<ol class="breadcrumb pull-right">';
            echo        '<li><i class="fa fa-user pad-right"></i><a id="profil-img" href="min_profil.php">' . $_SESSION['bruker_navn'] . "</a></li> ";
            echo        '<li><a id="logg_ut" href="includes/loggut.php">Logg ut</a></li>';
            echo     '</ol>';
        }
        else {
            echo    '<ol class="breadcrumb pull-right">';
            echo        '<li><a id="logg_inn" href="#" data-rel="popup">Logg inn</a></li>';
            echo        '<li><a id="registrer" href="#" data-rel="popup">Registrer deg</a></li>';
            echo     '</ol>';
        }
        ?>
        <div class="clearfix"></div>
    </div>

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
            <h2 style="display: none" id="feilkode1" class="feilkode white">Feilkode 1</h2>
            <h2 style="display: none" id="feilkode2" class="feilkode white">Feilkode 2</h2>
            <h2 style="display: none" id="feilkode3" class="feilkode white">Feilkode 3</h2>
            <h2 style="display: none" id="feilkode4" class="feilkode white">Feilkode 4</h2>

            <button form="registrer" name="button-avbryt" type="submit" class="button-lukk"> Lukk</button>
        </div>
    </div>

    <!-- Mail sendt -->
    <div id="registrer-mail-sendt">
        <div class="popup-header center">
            <h2 class="white"><i class="fa fa-check-square-o"></i> Mail sendt!</h2>
        </div>

        <div class="popup-container center">
            <h1 class="white">Takk for din registrering!</h1>
            <p class="white">Fullfør registreringen ved å sjekke eposten din.<br>Du kan nå lukke dette vinduet.</p>
            <button form="registrer" type="submit" class="button-lukk">Lukk</button>
        </div>
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
                           placeholder="Brukernavn" onkeyup="sjekkBNavn()" onblur="sjekkBNavn(id)">
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
                           onkeyup="sjekkEpost()" onblur="sjekkEpost(id)">
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
            <div class="pull-left" style="width: 70%">
                <h2 class="white pull-right"><i class="fa fa-sign-in"></i> Logg inn</h2>
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
            <h2 class="popup-header-text icon-success white"> Registrering fullført!</h2>
            <hr class="hr-popup">
        </div>

        <div class="popup-container center">
            <h1 class="white">Bruker aktivert</h1>
            <p class="white">Velkommen til HSN forum!<br>Du kan nå logge deg inn.</p>
            <button form="registrer" name="button-avbryt" type="submit" class="button-lukk"> Lukk</button>
        </div>
    </div>
    
    <!-- NY KATEGORI -->
    <div id="ny_kat">
        <div class="popup-header center">
            <div class="pull-left" style="width: 70%">
                <h2 class="white icon-user pull-right"><i class="fa fa-plus-square-o"></i> Legg til kategori</h2>
            </div>
            <div class="pull-right half" style="width: 30%;">
                <i class="logginn-icon-lukk fa fa-times fa-2x red pull-right"></i>
            </div>
        </div>

        <div class="popup-container center">
            <form id="ny_kat_form" name="ny_kat_form" method="post" action="http://localhost/forum/www/includes/endringer.php">
                <div class="popup-divider">
                    <input type="text" name="ny_kat_navn" id="ny_kat_navn" placeholder="Kategori navn" class="popup-input">
                </div>
                <input type="submit" name="ny_kat_btn" id="ny_kat_submit" value="LEGG TIL">
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
                <i class="logginn-icon-lukk fa fa-times fa-2x red pull-right"></i>
            </div>
        </div>

        <div class="popup-container center">
            <form id="ny_ukat_form" name="ny_ukat_form" method="post" action="http://localhost/forum/www/includes/endringer.php">
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

    
<?php
require_once(__DIR__ . '/../chatbox.php');
?>