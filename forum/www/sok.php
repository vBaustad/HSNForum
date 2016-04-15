<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

if (isset($_POST['sok_btn'])) {
    $soktekstRen = $_POST['sok_text'];
    $soktekst = "%" . $_POST['sok_text'] . "%";

    if ($soktekstRen == "") {
        echo "Søketeksten din var tom... Whops!";
    } else {

        // Søker hele forumet
        if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'hele') {
            // Finner brukere
            $sql = "SELECT bruker_navn, bruker_id FROM bruker WHERE bruker_navn LIKE ?";
            $stmt_bruker = $conn->prepare($sql);
            $stmt_bruker->bind_param("s", $soktekst);
            $stmt_bruker->execute();
            $stmt_bruker->bind_result($bruker_navn, $bruker_id);
            $stmt_bruker->store_result();

            echo <<<_END
            <table class="table table_sok table-striped">
                <thead>
                    <tr>
                    <th class="rad-bredde"></th>
                    <th><h2>Brukere</h2></th>
                    </tr>
                    </thead>
                <tbody>
_END;
            while ($stmt_bruker->fetch()) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4>
                        <a href="bruker.php?bruker=$bruker_id">$bruker_navn</a><br>
                        </h4></td>
                    </tr>
_END;
            }
            if ($stmt_bruker->num_rows < 1) {
                echo '<tr>';
                echo '<td></td>';
                echo '<td><h4>';
                echo 'Ingen treff på <i>' . $soktekstRen . '</i><br>';
                echo '</h4></td>';
                echo '</tr>';
            }
        echo '</tbody></table><br>';

            // Finner traader
            $sql = "SELECT ukat_id, traad_id, traad_tittel FROM traad WHERE traad_tittel LIKE ?";
            $stmt_traad = $conn->prepare($sql);
            $stmt_traad->bind_param("s", $soktekst);
            $stmt_traad->execute();
            $stmt_traad->bind_result($ukat_id, $traad_id, $traad_tittel);
        $stmt_traad->store_result();

            if (innlogget()) {
            echo <<<_END
                <table class="table table_sok table-striped">
                    <thead>
                        <tr>
                        <th class="rad-bredde"></th>
                        <th><h2>Tråder</h2></th>
                        </tr>
                    </thead>
                    <tbody>
_END;
            } else {
                echo '<ol class="sti red center"><li>Du må være logget inn for å søke på tråder</li></ol>';
            }
            while ($stmt_traad->fetch()) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td>
                            <h4><a href="traad.php?ukat_id=$ukat_id&traad_id=$traad_id">$traad_tittel</a><br></h4>
                        </td>
                    </tr>
_END;
            }
            if ($stmt_traad->num_rows < 1 && innlogget()) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td>
                        <h4>Ingen treff på <i>$soktekstRen</i><br></h4>
                        </td
                    </tr>
_END;
            }
        echo '</tbody></table>';

            // Finner underkategorier
            $sql = "SELECT ukat_id, kat_id, ukat_navn FROM underkategori WHERE ukat_navn LIKE ?";
            $stmt_ukat = $conn->prepare($sql);
            $stmt_ukat->bind_param("s", $soktekst);
            $stmt_ukat->execute();
            $stmt_ukat->bind_result($ukat_id, $kat_id, $ukat_navn);
            $stmt_ukat->store_result();

            echo '<table class="table table_sok table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="rad-bredde"></th>';
            echo '<th><h2>Underkategorier</h2></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($stmt_ukat->fetch() || $stmt_ukat->num_rows < 0) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4>
                        <a href="kategori.php?kat_id=$kat_id&ukat_id=$ukat_id">$ukat_navn</a><br>
                        </h4></td>
                    </tr>
_END;
            }
            if ($stmt_ukat->num_rows < 1) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4>
                        Ingen treff på <i>$soktekstRen</i><br>
                        </h4></td>
                    </tr>
_END;
            }
            echo '</tbody></table>';
            $stmt_bruker->close();
            $stmt_traad->close();
            $stmt_ukat->close();
          }

        // Finner brukere
        if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'bruker') {
            // Finner brukere
            $sql = "SELECT bruker_navn, bruker_id FROM bruker WHERE bruker_navn LIKE ?";
            $stmt_bruker = $conn->prepare($sql);
            $stmt_bruker->bind_param("s", $soktekst);
            $stmt_bruker->execute();
            $stmt_bruker->bind_result($bruker_navn, $bruker_id);
            $stmt_bruker->store_result();

            echo <<<_END
            <table class="table table_sok table-striped">
                <thead>
                    <tr>
                    <th class="rad-bredde"></th>
                    <th><h2>Brukere</h2></th>
                    </tr>
                    </thead>
                <tbody>
_END;
            while ($stmt_bruker->fetch()) {
                echo <<<_END
                    <tr>
                        <td></td>
        
                        <td><h4>
                        <a href="bruker.php?bruker=$bruker_id">$bruker_navn</a><br>
                        </h4></td>
                    </tr>
_END;
            }
            if ($stmt_bruker->num_rows < 1) {
                echo '<tr>';
                echo '<td></td>';
                echo '<td><h4>';
                echo 'Ingen treff på <i>' . $soktekstRen . '</i><br>';
                echo '</h4></td>';
                echo '</tr>';
            }
            echo '</tbody></table><br>';
            $stmt_bruker->close();
          }

        // Finner traader
        if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'traader') {
            // Finner traader
            $sql = "SELECT ukat_id, traad_id, traad_tittel FROM traad WHERE traad_tittel LIKE ?";
            $stmt_traad = $conn->prepare($sql);
            $stmt_traad->bind_param("s", $soktekst);
            $stmt_traad->execute();
            $stmt_traad->bind_result($ukat_id, $traad_id, $traad_tittel);
            $stmt_traad->store_result();

            if (innlogget()) {
            echo <<<_END
                <table class="table table_sok table-striped">
                    <thead>
                        <tr>
                        <th class="rad-bredde"></th>
                        <th><h2>Tråder</h2></th>
                        </tr>
                    </thead>
                    <tbody>
_END;
            } else {
                echo '<ol class="sti red center"><li>Du må være logget inn for å søke på tråder</li></ol>';
            }
            while ($stmt_traad->fetch()) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td>
                            <h4><a href="traad.php?ukat_id=$ukat_id&traad_id=$traad_id">$traad_tittel</a><br></h4>
                        </td>
                    </tr>
_END;
            }
            if ($stmt_traad->num_rows < 1 && innlogget()) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td>
                        <h4>Ingen treff på <i>$soktekstRen</i><br></h4>
                        </td
                    </tr>
_END;
            }
            echo '</tbody></table>';
            $stmt_traad->close();
          }

        // Finner underkategorier
        if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'ukategorier') {
            $sql = "SELECT ukat_id, kat_id, ukat_navn FROM underkategori WHERE ukat_navn LIKE ?";
            $stmt_ukat = $conn->prepare($sql);
            $stmt_ukat->bind_param("s", $soktekst);
            $stmt_ukat->execute();
            $stmt_ukat->bind_result($ukat_id, $kat_id, $ukat_navn);
            $stmt_ukat->store_result();

            echo '<table class="table table_sok table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="rad-bredde"></th>';
            echo '<th><h2>Underkategorier</h2></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($stmt_ukat->fetch() || $stmt_ukat->num_rows < 0) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4>
                        <a href="kategori.php?kat_id=$kat_id&ukat_id=$ukat_id">$ukat_navn</a><br>
                        </h4></td>
                    </tr>
_END;
            }
            if ($stmt_ukat->num_rows < 1) {
                echo <<<_END
                    <tr>
                        <td></td>
                        <td><h4>
                        Ingen treff på <i>$soktekstRen</i><br>
                        </h4></td>
                    </tr>
_END;
            }
            echo '</tbody></table>';
            $stmt_ukat->close();
        }

    }
}

require_once 'includes/footer.php';