<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'includes/boxes.php';


if (isset($_GET['bruker']) && $_GET['bruker'] > 0) {
    $bruker_id = mysqli_real_escape_string($conn, $_GET['bruker']);
    $sql = mysqli_query($conn, "SELECT bruker_navn, bruker_fornavn, bruker_etternavn, bruker_bilde, bruker_dato, bruker_mail FROM bruker WHERE bruker_id = '$bruker_id'");
    $row = mysqli_fetch_assoc($sql);

    if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
        $header_text = "Min profil";
        $brukernavn = "meg";
    } else {
        $brukernavn = $row['bruker_navn'];
        $header_text = $brukernavn;
    }

        echo '<div class="bruker_profilbilde_container">';
            echo '<img class="bruker_profilbilde" src="img/profilbilder/' . hentBilde($conn, $bruker_id) . '">';
            echo '<h1>' . $header_text . '</h1>';
        echo '</div>';
        echo '<div class="clearfix"></div>';

    echo '<div id="bruker_container">';
    
        echo '<ul id="bruker_endringer">';
            echo '<li id="om_bruker"><i class="fa fa-user"></i>Om ' . $row['bruker_navn'] . '</li>';
        if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
            echo '<li id="endre_pass"><i class="fa fa-key"></i>Endre passord</li>';
            echo '<li id="endre_epost"><i class="fa fa-envelope"></i>Endre epost</li>';
            echo '<li id="endre_bilde"><i class="fa fa-picture-o"></i>Endre profilbilde</li>';
        }
        echo '</ul>';

        if (isset($_GET['nytt_pass']) && $_GET['nytt_pass'] == 1) {
            echo '<div class="endringer_box">';
                echo '<div class="popup-header center">';
                    echo '<div class="pull-left" style="width: 80%">';
                        echo '<h2 class="endringer_box_h2 white pull-right"><i class="fa fa-check-square-o"></i> Passord endret!</h2>';
                    echo '</div>';
                    echo '<div class="pull-right" style="width: 20%;">';
                        echo '<i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

        if (isset($_GET['ny_epost']) && $_GET['ny_epost'] == 1) {
            echo '<div class="endringer_box">';
                echo '<div class="popup-header center">';
                    echo '<div class="pull-left" style="width: 80%">';
                        echo '<h2 class="endringer_box_h2 white pull-right"><i class="fa fa-check-square-o"></i> Epost endret!</h2>';
                    echo '</div>';
                    echo '<div class="pull-right" style="width: 20%;">';
                        echo '<i class="box-icon-lukk fa fa-times fa-2x red pull-right"></i>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

        // Om bruker
        echo '<div id="bruker_info">';
            echo '<h2>Om ' . $row['bruker_navn'] . '</h2>';
            echo '<div>Navn: <p class="bruker_info_format">' . $row['bruker_fornavn'] . ' ' . $row['bruker_etternavn'] . '</p></div>';
            if (innlogget() && $_GET['bruker'] == $_SESSION['bruker_id']) {
                echo '<div>Epost: <p class="bruker_info_format">' . $row['bruker_mail'] . '</p></div>';
            }
            echo '<div>Antal innlegg: <p class="bruker_info_format">' . tellInnlegg($conn, $bruker_id) . '</p></div>';
            echo '<div>Antal tråder: <p class="bruker_info_format">' . tellTraader($conn, $bruker_id) . '</p></div>';
            echo '<div>Medlem siden: <p class="bruker_info_format">' . datoSjekk($row['bruker_dato'])  . '</p></div>';
        echo '</div>';

        // Endre passord
        echo '<div id="endre_pass_box">';
            echo '<h2>Endre passord</h2>';
            echo '<form id="endre_pass_form" name="endre_pass_form" method="post" action="includes/endringer.php" onsubmit="return sjekkSkjema()">';
                echo '<div class="form_divider">';
                    echo '<input type="password" name="curr_pass" id="curr_pass" class="bruker_endre_input"
                                   placeholder="Gamelt passord">';
                    echo '<span id="ikkeAktiv"></span>';
                echo '</div>';

                echo '<div class="form_divider">';
                    echo '<input type="password" name="new_pass" id="new_pass" class="bruker_endre_input"
                                 placeholder="Nytt passord">';
                    echo '<span id="feilPass"></span>';
                echo '</div>';

                echo '<div class="form_divider">';
                    echo '<input type="password" name="new_pass_to" id="new_pass_to" class="bruker_endre_input"
                                 placeholder="Gjenta nytt passord" onblur="sjekkPass(id)">';
                    echo '<span id="feilPass"></span>';
                echo '</div>';
                echo '<input type="submit" name="nytt_pass_submitt" id="nytt_pass_submitt" value="BYTT PASSORD">';
            echo '</form>';
        echo '</div>';

        // Endre epost
        echo '<div id="endre_epost_box">';
            echo '<h2>Endre epost</h2>';

            // Current password
            echo '<form id="endre_epost_form" name="endre_epost_form" method="post" action="includes/endringer.php" onsubmit="return sjekkSkjema()">';
                echo '<div class="form_divider">';
                    echo '<input type="password" name="brukernavn_pass" id="brukernavn_pass" class="bruker_endre_input" placeholder="Ditt passord">';
                    echo '<span id="feilPass" class="black"></span>';
                echo '</div>';

                // Ny epost adresse
                echo '<div class="form_divider">';
                    echo '<input type="text" name="epost_reg" id="epost_reg" class="bruker_endre_input" placeholder="Ny epost adresse" 
                                 onkeyup="sjekkEpost()" onblur="sjekkEpost(id)">';
                    echo '<span id="epostErr" class="position_inherit black"></span><span id="sjekkEpost" class="black"></span>';
                echo '</div>';

                // Gjenta epost adresse
                echo '<div class="form_divider">';
                    echo '<input type="text" name="epost_reg_two" id="epost_reg_two" class="bruker_endre_input" placeholder="Gjenta ny epost adresse" 
                                 onblur="sjekkEpostTo(id)">';
                    echo '<span id="epostErrTwo" class="position_inherit black"></span>';
                echo '</div>';

                echo '<input type="submit" name="ny_epost_submitt" id="ny_epost_submitt" value="BYTT EPOST">';
            echo '</form>';
        echo '</div>';

        // Endre/legge til profilbilde
        echo '<div id="endre_bilde_box">';
            echo '<h2>Endre/legge til profilbilde</h2>';
            echo '<form action="includes/endringer.php" method="post" enctype="multipart/form-data">';
                echo '<input type="file" name="upload_file" id="upload_file" class="type_file_input">';
                echo '<input type="submit" name="nytt_bilde_submitt" id="nytt_bilde_submitt" value="LAST OPP">';
            echo '</form>';
        echo '</div>';
    
        echo '<div class="clearfix"></div>';
    echo '</div>';

} else {
    echo "Ingen bruker funnet";
}

require_once 'includes/footer.php';
