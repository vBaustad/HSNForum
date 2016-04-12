<?php
require_once 'db_connect.php';
require_once 'functions.php';

/* LEGGE TIL KATEGORIER */
if (isset($_POST['ny_kat_btn']) && $_SESSION['bruker_level'] == '2') {
    $sql = "INSERT INTO kategori(kat_navn) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kat_navn);
    $kat_navn = $_POST['ny_kat_navn'];
    $stmt->execute();
    $stmt->close();

    if ($sql) {
        header("Location: ../index.php", true, 301);
        exit;
    } else {
        echo "Kunne ikke legge til ny kat";
    }
}

/* NY UNDERKATEGORIER */
if (isset($_POST['ny_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
    $sql = "INSERT INTO underkategori(kat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge) VALUES(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $kat_id, $ukat_navn, $ukat_besk, $ukat_img, $ukat_img_farge);

    $kat_id = $_GET['kat_id'];
    $ukat_navn = $_POST['ny_ukat_navn'];
    $ukat_besk = $_POST['ny_ukat_besk'];
    $ukat_img = $_POST['ny_ukat_img'];
    $ukat_img_farge = $_POST['ny_ukat_img_farge'];
    $stmt->execute();
    $stmt->close();

    if ($sql) {
        header("Location: ../index.php", true, 301);
        exit;
    }else {
        echo "Kunne ikke legge til ny ukat";
    }
}

/* SLETTE KATEGORIER  */
if (isset($_POST['slett_kat_btn']) && $_SESSION['bruker_level'] == '2') {

    $kat_id = $_GET['slett_id'];
    $sql = "DELETE FROM kategori WHERE kat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kat_id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        header("Location: ../index.php", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette kat.";
    }
}

/* SLETTE UNDERKATEGORIER */
if (isset($_POST['slett_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
    $kat_id = $_GET['kat_id'];
    $ukat_id = $_GET['slett_ukat_id'];

    $sql = "DELETE FROM underkategori WHERE ukat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ukat_id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        header("Location: ../kategori.php?kat_id=$kat_id", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette ukat.";
    }
}

/* SLETTE TRÅDER */
if (isset($_POST['slett_traad_btn'])) {
    $traad_id = $_GET['slett_traad_id'];
    $ukat_id = $_GET['ukat_id'];
    $kat_id = $_GET['kat_id'];
    
    if ($stmt = $conn->prepare("DELETE FROM traad WHERE traad_id = ?")) {
        $stmt->bind_param("i", $traad_id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: ../kategori.php?kat_id=$kat_id&ukat_id=$ukat_id", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette ukat.";
    }
}

/* SLETTE INNLEGG */
if (isset($_GET['innlegg_id'])) {
    $innlegg_id = $_GET['innlegg_id'];

    if ($stmt =  $conn-> prepare("DELETE FROM innlegg WHERE innlegg_id = ?")) {
        $stmt->bind_param("i", $innlegg_id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        echo "kunne ikke slette innlegg.";
    }
}

/* NY EPOST */
if (isset($_POST['ny_epost_submitt']) && innlogget()) {
    $bruker_id = $_SESSION['bruker_id'];
    $ny_epost = $_POST['epost_reg'];
    $passord = mysqli_real_escape_string($conn, $_POST ['brukernavn_pass']);
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $sql = "SELECT bruker_pass FROM bruker WHERE bruker_pass = ? AND bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $passordhash, $bruker_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows > 0) {
        $sql = "UPDATE bruker SET bruker_mail=? WHERE bruker_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $ny_epost, $bruker_id);

        if ($stmt->execute()) {
            header("Location: ../bruker.php?bruker=$bruker_id&ny_epost=1");
        } else {
            echo "kunne ikke opdatere epost";
        }
    } else {
        echo "feil passord. Prøv igjen";
    }

}

/* NYTT PASSORD */
if (isset($_POST['nytt_pass_submitt']) && innlogget()) {
    $bruker_id = $_SESSION['bruker_id'];
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';

    $passord = mysqli_real_escape_string($conn, $_POST['curr_pass']);
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $new_passhash = hash('ripemd160', "$salt1$new_pass$salt2");

    $sql = "SELECT bruker_pass FROM bruker WHERE bruker_pass = ? AND bruker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $passordhash, $bruker_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows > 0) {
        $sql = "UPDATE bruker SET bruker_pass = ? WHERE bruker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_passhash, $bruker_id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../bruker.php?bruker=$bruker_id&nytt_pass=1");
        } else {
            echo "Kunen ikke oppdatere passord";
        }
    } else {
        echo "feil passord. Prøve igjen";
    }
}

/* NYTT PROFILBILDE */
if (isset($_POST['nytt_bilde_submitt']) && innlogget()) {
    $img_fil = basename($_FILES['upload_file']['name']);
    $img_fil_type = pathinfo($img_fil, PATHINFO_EXTENSION);
    $uploadok = 1;

    $sjekk_filtype = getimagesize($_FILES['upload_file']['tmp_name']);
    if ($sjekk_filtype !== false ) {
        echo "Filen er ett bilde - " . $sjekk_filtype['mime'] . ".<br>";
    } else {
        echo "Filen er ikke ett bilde<br>";
        $uploadok = 0;
    }

    // Hvis filen er større en 5MB
    if ($_FILES['upload_file']['size'] > 5000000) {
        echo "Filen er for stor!<br>";
        $uploadok = 0;
    }

    if ($img_fil_type != "jpg" && $img_fil_type != "jpeg" && $img_fil_type != "png" && $img_fil_type != "gif"
        && $img_fil_type != "JPG" && $img_fil_type != "JPEG" && $img_fil_type != "PNG" && $img_fil_type != "GIF") {
        echo "feil filformat. Kun PNG, JPG, JPEG, GIF<br>";
        $uploadok = 0;
    }

    if ($uploadok == 0) {
        echo "Kunne ikke laste opp filen";
    } else {
        // Setter bilenavn = bruker_id
        $bruker_id = $_SESSION['bruker_id'];
        $tmp = explode(".", $_FILES["upload_file"]["name"]);
        $nyfilnavn = $bruker_id . '.' . end($tmp);

        // Laster opp og plaserer filen.
        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], "../img/profilbilder/" . $nyfilnavn)) {
            echo "Filen " . basename($_FILES['upload_file']['name']) . " Har blitt opplastet!<br>";
            $bildefil = $nyfilnavn;
            echo $bildefil;
            $sql = "UPDATE bruker SET bruker_bilde = ? WHERE bruker_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $bildefil, $bruker_id);
            $stmt->execute();

        } else {
            echo "noe gikk galt...<br>";
        }
    }
}

/* NY TRÅD */
if (isset($_POST['ny_traad_submitt']) && innlogget()) {
    $ukat_id = $_GET['ukat_id'];
    $traad_tittel = $_POST['ny_traad_navn'];
    $traad_innhold = $_POST['ny_traad_text'];

    $sql = "INSERT INTO traad(ukat_id, traad_tittel, traad_innhold, traad_dato, bruker_navn, bruker_id) 
                   VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $ukat_id, $traad_tittel, $traad_innhold, $_SESSION['bruker_navn'], $_SESSION['bruker_id']);
    $stmt->execute();
    $stmt->close();
    $traad_id = mysqli_insert_id($conn);

    header("Location: ../traad.php?ukat_id=$ukat_id&traad_id=$traad_id");
}

/* NYTT INNLEGG */
if (isset($_POST['svar_btn']) && innlogget()) {
    $innlegg_innhold = $_POST['innlegg_innhold'];
    $traad_id = $_GET['traad_id'];
    $ukat_id = $_GET['ukat_id'];
    $bruker_id = $_SESSION['bruker_id'];
    $bruker_navn = $_SESSION['bruker_navn'];

    $sql = "INSERT INTO innlegg(innlegg_innhold, innlegg_dato, traad_id, ukat_id, bruker_id, bruker_navn) VALUES(?, NOW(), ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiis", $innlegg_innhold, $traad_id, $ukat_id, $bruker_id, $bruker_navn);
    $stmt->execute();
    $stmt->close();

    header("Location: ../traad.php?ukat_id=$ukat_id&traad_id=$traad_id");
}

/* NY LIKE, TRÅD */
if (isset($_GET['traad_id']) && isset($_GET['bruker_id']) && isset($_GET['bruker_navn']) && innlogget()) {
    echo likTraad($conn, $_GET['traad_id'], $_GET['bruker_id'], $_GET['bruker_navn']);
}

/* NY LIKE, INNLEGG */
if (isset($_GET['traad_id']) && isset($_GET['innlegg_id']) && isset($_GET['bruker_id']) && isset($_GET['bruker_navn']) && innlogget()) {
    echo likInnlegg($conn, $_GET['traad_id'], $_GET['innlegg_id'], $_GET['bruker_id'], $_GET['bruker_navn']);
}