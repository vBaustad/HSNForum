<?php
require_once 'db_connect.php';
?>

<?php
    // Lett til meg sjekk. f.eks session bruker_level!!!!!
    if (isset($_POST['ny_ukat_btn']) || $_SESSION['bruker_level'] == '2') {
        $ukat_navn = mysqli_real_escape_string($conn, $_POST['ny_ukat_navn']);
        $ukat_besk = mysqli_real_escape_string($conn, $_POST['ny_ukat_besk']);
        $ukat_img = mysqli_real_escape_string($conn, $_POST['ny_ukat_img']);
        $kat_id = mysqli_real_escape_string($conn, $_GET['id']);

        $conn->set_charset('utf-8');
        $sql = mysqli_query($conn, "INSERT INTO underkategori(kat_id, ukat_navn, ukat_beskrivelse, ukat_img) 
                                    VALUES ('$kat_id', '$ukat_navn', '$ukat_besk', '$ukat_img')");
        header("Location: http://localhost/forum/www/", true, 301);
        exit;
    }

    else {
        // Vis feilmelding
    }

?>
