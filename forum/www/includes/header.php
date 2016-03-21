<?php
require_once 'functions.php';
require_once 'db_connect.php';
// error_reporting(0);
?>
<script src="js/validate.js"></script>
<script src="js/sjekkbruker.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // Lukker vindues
        $(".registrer-button-lukk, .registrer-icon-lukk, .logginn-icon-lukk, #registrer-avbryt").click(function () {
            $(".registrer-box-success").hide();
            $(".registrer-mail-sendt").hide();
            $("#registrer-feil").hide();
            $("#registrer-box").hide();
            $("#logginn-box").hide();
            $("#ny_kat").hide();
            $("#ny_ukat").hide();
            $("#slett_kat").hide();
        });
        /* Loading...
         $("#registrer-submitt").click(function () {
         $(".registrer-box-loading").show();
         $(window).load(function () {
         $(".registrer-box-loading").hide();
         });
         }); */

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
    if ($_SESSION['bruker_level'] == '2') {
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
        if ($_SESSION['innlogget']) {
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
                </div>

                <div class="popup-divider">
                    <input type="password" name="passord_logginn" id="passord_logginn" class="popup-input"
                           placeholder="Passord">
                </div>
                <input type="submit" name="logginn-btn" id="logginn_submitt" value="LOGG INN">
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
            <button form="registrer" name="button-avbryt" type="submit" class="registrer-button-lukk"> Lukk</button>
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
                        <option value="fa fa-th-list fa-2x">Velg bilde</option>
                        <option value="fa fa-exclamation-triangle fa-2x">Trekantvarsel</option>
                        <option value="fa fa-info fa-2x">Info</option>
                        <option value="fa fa-archive fa-2x">Arkiv</option>
                        <option value="fa fa-comment-o fa-2x">Komentar</option>
            <!--        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        <option value=" fa-2x"></option>
                        Legg til mange bilder her!! -->

                    </select>
                </div>
                <input type="submit" name="ny_ukat_btn" id="ny_ukat_submit" value="LEGG TIL">
            </form>
        </div>
    </div>
<?php
echo "Dato og tid: " . date("Y-d-m G:i:s");
require_once(__DIR__ . '/../chatbox.php');
?>