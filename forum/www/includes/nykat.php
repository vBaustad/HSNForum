<?php
require_once 'db_connect.php';
?>

<?php
    // Lett til meg sjekk. f.eks session bruker_level!!!!!
    if (isset($_POST['ny_kat_btn'])) {
        $kat_navn = mysqli_real_escape_string($conn, $_POST['ny_kat_navn']);
        $conn->set_charset('utf-8');
        $sql = mysqli_query($conn, "INSERT INTO kategori(kat_navn) VALUES ('$kat_navn')");
        header("Location: http://localhost/forum/www/", true, 301);
        exit;
    }

    else {
        // Vis feilmelding
    }

?>
