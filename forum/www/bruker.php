<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'includes/boxes.php';

// TODO: Fikse ordentlig svar på opplastingav bilde. med header
// TODO: Fikse alle gamle get_result til bind_result! Siden SKAL kjøre på db-kurs :D
if (isset($_GET['bruker']) && $_GET['bruker'] > 0) {
    $bruker_id = $_GET['bruker'];

    $stmt = $conn->prepare("SELECT bruker_level FROM bruker WHERE bruker_id = ?");
    $stmt->bind_param("i", $bruker_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($bruker_level);
    $stmt->fetch();
    $brukerlevel = $bruker_level;
    $stmt->close();

    if($stmt = $conn->prepare("SELECT bruker_navn, bruker_fornavn, bruker_etternavn, bruker_bilde, bruker_dato, bruker_mail FROM bruker WHERE bruker_id = ? ")){
        // Bind parameters
        $stmt -> bind_param("i", $bruker_id);
        $stmt -> execute();
        $stmt -> store_result();
        $stmt -> bind_result($sql_bruker_navn, $sql_bruker_fornavn, $sql_bruker_etternavn, $sql_bruker_bilde, $sql_bruker_dato, $sql_bruker_mail);
        $stmt -> fetch();
        $stmt -> close();
    }

    if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
        $header_text = "Min profil";
        $brukernavn = "meg";
    } else {
        $brukernavn = $sql_bruker_navn;
        $header_text = $brukernavn;
    }

    /* Kaller på metodene som henter bilde, teller innlegg, teller traader, og sjekker dato brukeren regisrerte seg*/
    $profilbilde = hentBilde($conn, $bruker_id);
    $ant_innlegg = tellInnlegg($conn, "bruker", $bruker_id);
    $ant_traader = tellTraader($conn, "bruker", $bruker_id);
    $medlem_dato = datoSjekk($sql_bruker_dato);

        echo <<<_END
       <div class="bruker_profilbilde_container">
            <img class="bruker_profilbilde" src="img/profilbilder/$profilbilde  ">
            <h1> $header_text </h1>
        </div>
        <div class="clearfix"></div>

    <div id="bruker_container">
    
        <ul id="bruker_endringer">
            <li id="om_bruker"><i class="fa fa-user"></i>Om $sql_bruker_navn</li>
_END;
    if (innlogget() && bruker_level(null, "session", null) == 'admin' && $_GET['bruker'] != $_SESSION['bruker_id']) {
        echo '<li id="endre_rettigheter"><i class="fa fa-star"></i>Endre rettigheter</li>';
    }
        if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
        echo <<<_END
            <li id="endre_pass"><i class="fa fa-key"></i>Endre passord</li>
            <li id="endre_epost"><i class="fa fa-envelope"></i>Endre epost</li>
            <li id="endre_bilde"><i class="fa fa-picture-o"></i>Endre profilbilde</li>
_END;
        }
        echo '</ul>';

        // Nytt passord bekreftelse
        if (isset($_GET['nytt_pass']) && $_GET['nytt_pass'] == 1) {
        echo <<<_END
            <div class="endringer_box">
                <div class="popup-header center">
                    <div class="pull-left" style="width: 80%">
                        <h2 class="endringer_box_h2 white pull-right"><i class="fa fa-check-square-o"></i> Passord endret!</h2>
                    </div>
                    <div class="pull-right" style="width: 20%;">
                        <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
                    </div>
                </div>
            </div>
_END;
        }

        // Ny epost bekreftelse
        if (isset($_GET['ny_epost']) && $_GET['ny_epost'] == 1) {
        echo <<<_END
            <div class="endringer_box">
                <div class="popup-header center">
                    <div class="pull-left" style="width: 80%">
                        <h2 class="endringer_box_h2 white pull-right"><i class="fa fa-check-square-o"></i> Epost endret!</h2>
                    </div>
                    <div class="pull-right" style="width: 20%;">
                        <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
                    </div>
                </div>
            </div>
_END;
        }

    // Opplasting av nytt profilbilde feilet. Fil format.
    if (isset($_GET['feil']) && $_GET['feil'] == 1) {
        echo <<<_END
            <div class="endringer_box">
                <div class="popup-header center">
                    <div class="pull-left" style="width: 80%">
                        <h2 class="endringer_box_h2 white pull-right"><i class="fa fa-exclamation-triangle"></i> Noe gikk galt!</h2>
                    </div>
                    <div class="pull-right" style="width: 20%;">
                        <i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>
                    </div>
                </div>
                    <p class="popup-feil white center">Bildet du prøvde å laste opp har enten ikke riktig format eller er for stort. <br><br>
                    Kun jpg, gif eller png er tillatt! Bildet kan heller ikke være større enn 2MB</p>
            </div>
_END;
    }

    /*Om bruker*/
        $status = bruker_level($conn, "bruker", $bruker_id);

        if ($status == 'admin') {
            $status = "Administrator";
        } else {
            $status = "Vanlig bruker";
        }
        echo <<<_END
        <div id="bruker_info">
            <h2>Om $sql_bruker_navn </h2>
            <div>Navn: <p class="bruker_info_format">$sql_bruker_fornavn $sql_bruker_etternavn </p></div>
            <div>Status: <p class="bruker_info_format">$status</p></div>
_END;
            if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
                echo '<div>Epost: <p class="bruker_info_format">' . $sql_bruker_mail . '</p></div>';
            }

            echo <<<_END
            <div>Antal innlegg: <p class="bruker_info_format">$ant_innlegg</p></div>
            <div>Antal traader: <p class="bruker_info_format">$ant_traader</p></div>
            <div>Medlem siden: <p class="bruker_info_format"></p>$medlem_dato</div>
        </div>
_END;


    /* HTML-kode for å endre passord, endre epost, og endre/legge til profilbilde. */
        echo <<<_END
        <div id="endre_pass_box">
            <h2>Endre passord</h2>
            <form id="endre_pass_form" name="endre_pass_form" method="post" action="includes/endringer.php" onsubmit="return sjekkSkjema()">
                <div class="form_divider">
                    <input type="password" name="curr_pass" id="curr_pass" class="bruker_endre_input"
                                   placeholder="Gamelt passord">
                    <span id="ikkeAktiv"></span>
                </div>

                <div class="form_divider">
                    <input type="password" name="new_pass" id="new_pass" class="bruker_endre_input"
                                 placeholder="Nytt passord">
                    <span id="feilPass"></span>
                </div>

                <div class="form_divider">
                    <input type="password" name="new_pass_to" id="new_pass_to" class="bruker_endre_input"
                                 placeholder="Gjenta nytt passord" onblur="sjekkPass(id)">
                    <span id="feilPass"></span>
                </div>
                <input type="submit" name="nytt_pass_submitt" id="nytt_pass_submitt" value="BYTT PASSORD">
            </form>
        </div>
    

        <div id="endre_epost_box">
            <h2>Endre epost</h2>

           <form id="endre_epost_form" name="endre_epost_form" method="post" action="includes/endringer.php" onsubmit="return sjekkSkjema()">
                <div class="form_divider">
                    <input type="password" name="brukernavn_pass" id="brukernavn_pass" class="bruker_endre_input" placeholder="Ditt passord">
                    <span id="feilPass" class="black"></span>
            </div>

            <div class="form_divider">
                <input type="text" name="epost_reg" id="epost_reg" class="bruker_endre_input" placeholder="Ny epost adresse" 
                             onkeyup="sjekkEpost()" onblur="sjekkEpost(id)">
                <span id="epostErr" class="position_inherit black"></span><span id="sjekkEpost" class="black"></span>
            </div>

             <div class="form_divider">
                 <input type="text" name="epost_reg_two" id="epost_reg_two" class="bruker_endre_input" placeholder="Gjenta ny epost adresse" 
                              onblur="sjekkEpostTo(id)">
                 <span id="epostErrTwo" class="position_inherit black"></span>
             </div>

             <input type="submit" name="ny_epost_submitt" id="ny_epost_submitt" value="BYTT EPOST">
         </form>
     </div>

        
        <div id="endre_bilde_box">
            <h2>Endre/legge til profilbilde</h2>
            <form action="includes/endringer.php" method="post" enctype="multipart/form-data">
                <input type="file" name="upload_file" id="upload_file" class="type_file_input">
                <input type="submit" name="nytt_bilde_submitt" id="nytt_bilde_submitt" value="LAST OPP">
            </form>
        </div>
        
        <div id="endre_rettigheter_box">
            <h2>Gi/endre rettigheter</h2>
            <form id="endre_pass_form" name="endre_pass_form" method="post" action="includes/endringer.php?bruker_id=$bruker_id&bruker_level=$brukerlevel">
                
_END;
                if ($brukerlevel == 1) {
                    echo <<<_END
                    <h4>Ved å gi $brukernavn administrative rettigheter, vil han/hun ha mulighet til å:<br>
                        Slette innlegg, tråder, underkategorier, og kategorier.
                    </h4>
                    <input type="submit" name="endre_rettigheter_submit" id="endre_rettigheter_submit" value="GI RETTIGHETER">
_END;
                } else {
                    echo '<input type="submit" name="endre_rettigheter_submit" id="endre_rettigheter_submit" value="FJERN RETTIGHETER">';
                }
            echo <<<_END
            </form>
        </div>
    <div class="clearfix"></div>
    </div>
_END;

} else {
    echo "Ingen bruker funnet";
}

require_once 'includes/footer.php';
