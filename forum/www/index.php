<?php
require_once '/includes/db_connect.php';
require_once '/includes/header.php';
require_once 'chatbox.php';
require_once 'includes/boxes.php';

if (bruker_level() == "admin") {
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
                echo '            <th id="' . $row_kat['kat_id'] . '" class="center skjul_tbody_btn rad-bredde-icon">';
                echo '              <i class="bildeID' . $row_kat['kat_id'] . ' fa fa-caret-square-o-up fa-2x"></i></th>';
                echo '            <th>';
                echo '              <a class="th_text" href="kategori.php?kat_id=' . $row_kat['kat_id'] .'">' . $row_kat['kat_navn'] . '</a>';
                echo '            </th>';
                echo '            <th class="rad-bredde text-center skjul-liten skjul-medium">Emner</th>';
                echo '            <th class="rad-bredde text-center skjul-liten skjul-medium">Innlegg</th>';
                echo '            <th class="rad-bredde-2x skjul-liten skjul-medium">Siste Innlegg</th>';
                echo '      </tr>';
                echo '  </thead>';
                echo '  <tbody class=radID' . $row_kat['kat_id'] . '>';

                $ukat_teller = $row_kat['kat_id'];
            }

            $ukat = mysqli_query($conn, "SELECT kat_id, ukat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge FROM underkategori WHERE kat_id = '$ukat_teller'");
            if ($ukat->num_rows > 0) {
                if (mysqli_num_rows($ukat) > 0) {
                    while ($row_ukat = mysqli_fetch_assoc($ukat)) {

                        $ukat_id = $row_ukat['ukat_id'];
                        // For HTML validering
                        $ukat_navn = (str_replace(" ", "_", $row_ukat['ukat_navn']));

                        // Teller antall tråder
                        $anttråd = mysqli_query($conn, "SELECT COUNT(tråd_id) as antPosts FROM tråd WHERE ukat_id = '$ukat_id'");
                        $anttråd_result = mysqli_fetch_assoc($anttråd);

                        // Teller antall svar
                        $antinnlegg = mysqli_query($conn, "SELECT COUNT(innlegg_id) as antInnlegg FROM innlegg WHERE tråd_id = '$ukat_id'");
                        $antinnlegg_result = mysqli_fetch_assoc($antinnlegg);

                        // Finner bruker som skreve siste innlegg
                        $siste_innlegg = mysqli_query($conn, "SELECT innlegg_dato, bruker_navn, bruker_id FROM innlegg WHERE ukat_id = '$ukat_id' ORDER BY innlegg_dato DESC LIMIT 1");
                        $siste_innlegg_row = mysqli_fetch_assoc($siste_innlegg);

                        // Finner bruker som skrev siste tråd
                        $siste_innlegg = mysqli_query($conn, "SELECT tråd_dato, bruker_navn, bruker_id FROM tråd WHERE ukat_id = '$ukat_id' ORDER BY tråd_dato DESC LIMIT 1");
                        $siste_traad_row = mysqli_fetch_assoc($siste_innlegg);


                        if ($siste_innlegg_row['innlegg_dato'] > $siste_traad_row['tråd_dato']) {
                            $siste_aktivitet = datoSjekk($siste_innlegg_row['innlegg_dato']);
                            $siste_innlegg_navn = $siste_innlegg_row['bruker_navn'];
                            $siste_innlegg_id = $siste_innlegg_row['bruker_id'];
                        } else {
                            $siste_aktivitet = datoSjekk($siste_traad_row['tråd_dato']);
                            $siste_innlegg_navn = $siste_traad_row['bruker_navn'];
                            $siste_innlegg_id = $siste_traad_row['bruker_id'];
                        }


                        if ($siste_innlegg->num_rows > 0) {
                            // Finner dato på siste tråd.. lag en spørring som sjekker BÅDE tråd og innlegg dato. Finn siste!!
                            $dagensdato = date("y-d/m");
                            $meldingdato = date("y-d/m", strtotime($siste_innlegg_row['innlegg_dato']));

                            $postdm = utf8_encode(strftime("%a %d %B", strtotime($siste_innlegg_row['innlegg_dato'])));
                            $postgis = date("G:i ", strtotime($siste_innlegg_row['innlegg_dato']));

                            if ($meldingdato == $dagensdato) {
                                $postdm = " I dag ";
                            }
                            $postdato = '<i class="fa fa-clock-o"></i> ' . $postdm . ' ' . $postgis;
                        }
                        else {
                            $postdato = "";
                        }

                        echo '      <tr>';
                        echo '          <td class="center"><i class="' . $row_ukat['ukat_img'] . $row_ukat['ukat_img_farge'] . '"></i></td>';
                        echo '          <td>
                                            <h4>
                                                <a href="kategori.php?kat_id=' . $row_kat['kat_id'] . '&ukat_id=' . $row_ukat['ukat_id'] . '&ukat_navn=' . $ukat_navn . '">
                                                    ' . $row_ukat['ukat_navn'] . '
                                                </a><br>
                                                <small>
                                                    ' . $row_ukat['ukat_beskrivelse'] . '
                                                </small>
                                            </h4>
                                        </td>';
                        echo '          <td class="text-center skjul-liten skjul-medium"><a href="kategori.php?kat_id=' . $row_kat['kat_id'] . '&ukat_id=' .
                            $row_ukat['ukat_id'] . '">' . $anttråd_result['antPosts'] . '</a></td>';
                        echo '          <td class="text-center skjul-liten skjul-medium"><a href="#">' . $antinnlegg_result['antInnlegg'] . '</a></td>';

                        echo '          <td class="skjul-liten skjul-medium">';
                        if ($siste_innlegg->num_rows > 0 ) {
                            echo '<small>av </small> <a href="bruker.php?bruker=' . $siste_innlegg_id .  '">' .
                                $siste_innlegg_navn . '</a><br><small>' . $siste_aktivitet . '</small></td>';
                        }
                        else {
                            echo '<small>ingen innlegg enda</small>';
                        }

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