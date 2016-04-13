<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
require_once 'chatbox.php';
require_once 'includes/boxes.php';

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
        // Printer ut table head. Hver tbody begynner med en egen generert class slik at jquery kan identifisere radene og skjule de etter behov.
        echo <<<_END
        <table class="main-table table table-striped">
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
        <tbody class="radID$sq_kat_id">
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
            $anttraad = tellTraader($conn, "ukat", $ukat_id);

            // Teller antall innlegg
            $antInnlegg = tellInnlegg($conn, "ukat", $ukat_id);

            // Finner bruker som skrev siste traad
            $sistetraad = sistAktivUkat($conn, "traad", $ukat_id);

            // Finner bruker som skreve siste innlegg
            $sisteInnlegg = sistAktivUkat($conn, "innlegg", $ukat_id);

            // Sjekker siste aktivitet i både tråd og innlegg mot hverandre og finner absolutt siste aktivitet
            if ($sisteInnlegg[0] > $sistetraad[0]) {
                $siste_aktivitet = datoSjekk($sisteInnlegg[0]);
                $siste_innlegg_navn = $sisteInnlegg[1];
                $siste_innlegg_id = $sisteInnlegg[2];
            } else {
                $siste_aktivitet = datoSjekk($sistetraad[0]);
                $siste_innlegg_navn = $sistetraad[1];
                $siste_innlegg_id = $sistetraad[2];
            }

            echo <<<_END
                <tr>
                <td class="center"><i class="$sql_ukat_img$sql_ukat_img_farge"></i>
                </td>
                <td><h4>
                    <a href="kategori.php?kat_id=$sql_kat_id&#38;ukat_id=$sql_ukat_id">$sql_ukat_navn</a><br>
                        <small>$sql_ukat_beskrivelse</small>
                </h4></td>

                <td class="text-center skjul-liten skjul-medium">
                    <a href="kategori.php?kat_id=$sql_kat_id&#38;ukat_id=$sql_ukat_id">$anttraad</a>
                </td>

                <td class="text-center skjul-liten skjul-medium">
                    <a href="#">$antInnlegg</a>
                </td>

                <td class="skjul-liten skjul-medium">
_END;
                if ($antInnlegg > 0 ) {
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
        $stmt_ukat->close();
    }
}
$stmt->close();
require_once(__DIR__ . '/includes/footer.php');