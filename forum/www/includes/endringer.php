<?php
require_once(__DIR__ . '/db_connect.php');

/* LEGGE TIL KATEGORIER */
if (isset($_POST['ny_kat_btn']) && $_SESSION['bruker_level'] == '2') {
    $sql = "INSERT INTO kategori(kat_navn) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kat_navn);
    $kat_navn = $_POST['ny_kat_navn'];
    $stmt->execute();

    if ($sql) {
        header("Location: http://localhost/forum/www/", true, 301);
        exit;
    }else {
        echo "Kunne ikke legge til ny kat";
    }
}

/* LEGGE TIL UNDERKATEGORIER */
if (isset($_POST['ny_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
    $sql = "INSERT INTO underkategori(kat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge) VALUES(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $kat_id, $ukat_navn, $ukat_besk, $ukat_img, $ukat_img_farge);

    $ukat_navn = $_POST['ny_ukat_navn'];
    $ukat_besk = $_POST['ny_ukat_besk'];
    $ukat_img = $_POST['ny_ukat_img'];
    $ukat_img_farge = $_POST['ny_ukat_img_farge'];
    $kat_id = $_SESSION['kat_id'];
    $stmt->execute();

    if ($sql) {
        header("Location: http://localhost/forum/www/", true, 301);
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
        header("Location: http://localhost/forum/www/", true, 301);
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
        header("Location: http://localhost/forum/www/kategori.php?kat_id=$kat_id", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette ukat.";
    }
}

/* SLETTE TRÅDER - TRENGER VI PREPARED STATEMENT HER?
if (isset($_POST['slett_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
    $kat_id = mysqli_real_escape_string($conn, $_GET['kat_id']);
    $ukat_id = mysqli_real_escape_string($conn, $_GET['slett_ukat_id']);
    $sql = mysqli_query($conn, "DELETE FROM underkategori WHERE ukat_id = '$ukat_id'");

    if ($sql) {
        header("Location: http://localhost/forum/www/kategori.php?kat_id=$kat_id", true, 301);
        exit;
    }
    else {
        echo "kunne ikke slette ukat.";
    }
}
*/