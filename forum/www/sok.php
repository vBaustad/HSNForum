<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

if (isset($_POST['sok_btn'])) {
    $soktekstRen = $_POST['sok_text'];
    $soktekst = "%" . $_POST['sok_text'] . "%";

    if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'hele') {

        // Finner brukere
        $sql = "SELECT bruker_navn, bruker_id FROM bruker WHERE bruker_navn LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        echo '<table class="table table_sok table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="rad-bredde"></th>';
        echo '<th><h2>Brukere</h2></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<tr>';
            echo '<td></td>';

            echo '<td><h4>';
            echo '<a href="bruker.php?bruker=' . $row['bruker_id'] . '">' . $row['bruker_navn'] . '</a><br>';
            echo '</h4></td>';
            echo '</tr>';

        }
        if ($res->num_rows < 1) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4>';
            echo 'Ingen treff på <i>' . $soktekstRen . '</i><br>';
            echo '</h4></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo "<br>";

        // Finner tråder
        $sql = "SELECT ukat_id, tråd_id, tråd_tittel, tråd_innhold FROM tråd WHERE tråd_tittel LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        echo '<table class="table table_sok table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="rad-bredde"></th>';
        echo '<th><h2>Tråder</h2></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4>';
            echo '<a href="traad.php?ukat_id=' . $row['ukat_id'] . '&tråd_id=' . $row['tråd_id'] . '">' . $row['tråd_tittel'] . '</a><br>';
            echo '</h4></td>';
            echo '</tr>';

        }
        if ($res->num_rows < 1) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4>';
            echo 'Ingen treff på <i>' . $soktekstRen . '</i><br>';
            echo '</h4></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        // Finner underkategorier
        $sql = "SELECT ukat_navn FROM underkategori WHERE ukat_navn LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        echo '<table class="table table_sok table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="rad-bredde"></th>';
        echo '<th><h2>Underkategorier</h2></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($res) || $res->num_rows < 0) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4>';
            echo '<a href="kategori.php?kat_id=' . $row['kat_id'] . '&ukat_id=' . $row['ukat_id'] . '">' . $row['ukat_navn'] . '</a><br>';
            echo '</h4></td>';
            echo '</tr>';
        }
        if ($res->num_rows < 1) {
            echo '<tr>';
            echo '<td></td>';
            echo '<td><h4>';
            echo 'Ingen treff på <i>' . $soktekstRen . '</i><br>';
            echo '</h4></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }

    // Finner brukere
    if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'bruker') {
        $sql = "SELECT bruker_navn, bruker_id FROM bruker WHERE bruker_navn LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        while ($row = mysqli_fetch_assoc($res)) {
            echo '<table class="table table_sok table-striped">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th class="rad-bredde"></th>';
                        echo '<th><h2>Brukere</h2></th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td></td>';
                        echo '<td><h4><a href="bruker.php?bruker=' . $row['bruker_id'] . '">' . $row['bruker_navn'] . '</a><br><h4></td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
        }
    }

    // Finner tråder
    if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'traader') {
        $sql = "SELECT tråd_tittel, tråd_innhold FROM tråd WHERE tråd_tittel LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        while ($row = mysqli_fetch_assoc($res)) {
            echo $row['tråd_tittel'] . "<br>";
        }

    }

    // Finner underkategorier
    if (isset($_POST['sok_select']) && $_POST['sok_select'] == 'ukategorier') {
        $sql = "SELECT ukat_navn FROM underkategori WHERE ukat_navn LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $soktekst);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        while ($row = mysqli_fetch_assoc($res)) {
            echo $row['ukat_navn'] . "<br>";
        }
    }
}

require_once 'includes/footer.php';