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

    if ($sql) {
        header("Location: ../index.php", true, 301);
        exit;
    }else {
        echo "Kunne ikke legge til ny ukat";
    }
}

/* SLETTE KATEGORIER - TRENGER VI PREPARED STATEMENT HER? */
if (isset($_POST['slett_kat_btn']) && $_SESSION['bruker_level'] == '2') {

    $kat_id = mysqli_real_escape_string($conn, $_GET['slett_id']);
    $sql = mysqli_query($conn, "DELETE FROM kategori WHERE kat_id = '$kat_id'");

    if ($sql) {
        header("Location: ../index.php", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette kat.";
    }
}

/* SLETTE UNDERKATEGORIER - TRENGER VI PREPARED STATEMENT HER? */
if (isset($_POST['slett_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
    $kat_id = mysqli_real_escape_string($conn, $_GET['kat_id']);
    $ukat_id = mysqli_real_escape_string($conn, $_GET['slett_ukat_id']);
    $sql = mysqli_query($conn, "DELETE FROM underkategori WHERE ukat_id = '$ukat_id'");

    if ($sql) {
        header("Location: ../kategori.php?kat_id=$kat_id", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette ukat.";
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

    $sql = mysqli_query($conn, "SELECT bruker_pass FROM bruker WHERE bruker_pass = '$passordhash' AND bruker_id = '$bruker_id'");

    if ($sql->num_rows > 0) {
        $sql = "UPDATE bruker SET bruker_mail=? WHERE bruker_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $ny_epost, $bruker_id);

        if ($stmt->execute()) {
            header("Location: ../bruker.php?bruker=$bruker_id&ny_epoost=1");
        } else {
            echo "kunne ikke opdatere epost";
        }
    } else {
        echo "feil passord. Prøv igjen";
    }

}

/* NYT PASSORD */
if (isset($_POST['nytt_pass_submitt']) && innlogget()) {
    $bruker_id = $_SESSION['bruker_id'];
    $salt1 = 'dkn?';
    $salt2 = '$l3*!';

    $passord = mysqli_real_escape_string($conn, $_POST['curr_pass']);
    $passordhash = hash('ripemd160', "$salt1$passord$salt2");

    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $new_passhash = hash('ripemd160', "$salt1$new_pass$salt2");

    $sql = mysqli_query($conn, "SELECT bruker_pass FROM bruker WHERE bruker_pass = '$passordhash' AND bruker_id = '$bruker_id'");

    if ($sql->num_rows > 0) {
        $sql = "UPDATE bruker SET bruker_pass=? WHERE bruker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_passhash, $bruker_id);

        if ($stmt->execute()) {
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
