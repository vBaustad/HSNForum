<?php
require_once ('includes/db_connect.php');
require_once ('includes/header.php');
require_once ('chatbox.php');
require_once ('includes/boxes.php');

if (bruker_level() == "admin") {
    echo '<a class="pull-right button-std mar-bot" id="ny_kat_btn" href="#"><i class="fa fa-plus-square-o"></i> Ny kategori</a>';
}

if ($stmt = $conn->prepare("SELECT kat_id, kat_navn FROM kategori")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($sq_kat_id, $sql_kat_navn);

    $ukat_teller = 0;

    // Ramser opp alle kategorier
    while ($stmt->fetch()) {
        echo <<<_END
        <table class="main-table table forum table-striped">
            <thead>
                 <tr>
                      <th id="$sq_kat_id" class="center skjul_tbody_btn rad-bredde-icon">
                        <i class="bildeID$sq_kat_id fa fa-caret-square-o-up fa-2x"></i></th>
                      <th>
                        <a class="th_text" href="kategori.php?kat_id=$sq_kat_id">$sql_kat_navn</a>
                      </th>
                      <th class="rad-bredde text-center skjul-liten skjul-medium">traader</th>
                      <th class="rad-bredde text-center skjul-liten skjul-medium">Innlegg</th>
                      <th class="rad-bredde-2x skjul-liten skjul-medium">Siste aktivitet</th>
                </tr>
            </thead>
        <tbody>
_END;
        $ukat_teller = $sq_kat_id;

        $sql_ukat = "SELECT kat_id, ukat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge FROM underkategori WHERE kat_id = ?";
        $stmt_ukat = $conn->prepare($sql_ukat);
        $stmt_ukat->bind_param("i", $ukat_teller);
        $stmt_ukat->execute();
        $stmt_ukat->store_result();
        $stmt_ukat->bind_result($sql_kat_id, $sql_ukat_id, $sql_ukat_navn, $sql_ukat_beskrivelse, $sql_ukat_img, $sql_ukat_img_farge);

        // Ramser opp alle underkategorier
        while ($stmt_ukat->fetch()) {
            $ukat_id = $sql_ukat_id;

            // Teller antall traader
            $anttraad = $conn->prepare("SELECT COUNT(traad_id) as anttraader FROM traad WHERE ukat_id = ?");
            $anttraad->bind_param("i", $ukat_id);
            $anttraad->execute();
            $anttraad->store_result();
            $anttraad->bind_result($sql_anttraader);
            $anttraad->fetch();

            // Teller antall innlegg
            $antinnlegg = $conn->prepare("SELECT COUNT(innlegg_id) as antInnlegg FROM innlegg WHERE ukat_id = ?");
            $antinnlegg->bind_param("i", $ukat_id);
            $antinnlegg->execute();
            $antinnlegg->store_result();
            $antinnlegg->bind_result($sql_antInnlegg);
            $antinnlegg->fetch();

            // TODO: bruk funksjon sjekkDato() ?
            // Finner bruker som skreve siste innlegg
            $siste_innlegg = $conn->prepare("SELECT innlegg_dato, bruker_navn, bruker_id FROM innlegg WHERE ukat_id = ? ORDER BY innlegg_dato DESC LIMIT 1");
            $siste_innlegg->bind_param("i", $ukat_id);
            $siste_innlegg->execute();
            $siste_innlegg->store_result();
            $siste_innlegg->bind_result($sql_innlegg_dato, $sql_bruker_navn, $sql_bruker_id);
            $siste_innlegg->fetch();
            
            // TODO: bruk funksjon?
            // Finner bruker som skrev siste traad
            $siste_traad = $conn->prepare("SELECT traad_dato, bruker_navn, bruker_id FROM traad WHERE ukat_id = ? ORDER BY traad_dato DESC LIMIT 1");
            $siste_traad->bind_param("i", $ukat_id);
            $siste_traad->execute();
            $siste_traad->store_result();
            $siste_traad->bind_result($sql_traad_traad_dato, $sql_traad_bruker_navn, $sql_traad_bruker_id);
            $siste_traad->fetch();

            // TODO: Peker på feil bruker.
            if ($sql_innlegg_dato > $sql_traad_traad_dato) {
                $siste_aktivitet = datoSjekk($sql_innlegg_dato);
                $siste_innlegg_navn = $sql_bruker_navn;
                $siste_innlegg_id = $sql_bruker_id;
            } else {
                $siste_aktivitet = datoSjekk($sql_traad_traad_dato);
                $siste_innlegg_navn = $sql_traad_bruker_navn;
                $siste_innlegg_id = $sql_traad_bruker_id;
            }

            if ($siste_innlegg->num_rows > 0) {
                // Finner dato på siste traad.. lag en spørring som sjekker BÅDE traad og innlegg dato. Finn siste!!
                $dagensdato = date("y-d/m");
                $meldingdato = date("y-d/m", strtotime($sql_innlegg_dato));

                $postdm = utf8_encode(strftime("%a %d %B", strtotime($sql_innlegg_dato)));
                $postgis = date("G:i ", strtotime($sql_innlegg_dato));

                if ($meldingdato == $dagensdato) {
                    $postdm = " I dag ";
                }
                $postdato = '<i class="fa fa-clock-o"></i> ' . $postdm . ' ' . $postgis;
            }
            else {
                $postdato = "";
            }

            echo <<<_END
                <tr>
                <td class="center"><i class="$sql_ukat_img$sql_ukat_img_farge"></i>
                </td>
                <td><h4>
                    <a href="kategori.php?kat_id=$sql_kat_id&ukat_id=$sql_ukat_id">$sql_ukat_navn</a><br>
                        <small>$sql_ukat_beskrivelse</small>
                </h4></td>

                <td class="text-center skjul-liten skjul-medium">
                    <a href="kategori.php?kat_id=$sql_kat_id&ukat_id=$sql_ukat_id">$sql_anttraader</a>
                </td>

                <td class="text-center skjul-liten skjul-medium">
                    <a href="#">$sql_antInnlegg</a>
                </td>

                <td class="skjul-liten skjul-medium">
_END;
                if ($siste_innlegg->num_rows > 0 ) {
                    echo <<<_END
                    <small>av </small>
                        <a href="bruker.php?bruker=$siste_innlegg_id">$siste_innlegg_navn</a><br><small>
                        <i class="fa fa-clock-o"></i> $siste_aktivitet</small></td>
_END;
                }
                else {
                    echo '<small>ingen aktivitet enda</small>';
                }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        $siste_traad->close();
        $antinnlegg->close();
        $anttraad->close();
        $stmt_ukat->close();
    }
}
$stmt->close();
require_once(__DIR__ . '/includes/footer.php');