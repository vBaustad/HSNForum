<?php
require_once 'db_connect.php';

    /* LEGGE TIL KATEGORIER */
    if (isset($_POST['ny_kat_btn']) && $_SESSION['bruker_level'] == '2') {
        $kat_navn = mysqli_real_escape_string($conn, $_POST['ny_kat_navn']);
        $conn->set_charset('utf-8');
        $sql = mysqli_query($conn, "INSERT INTO kategori(kat_navn) VALUES ('$kat_navn')");

        if ($sql) {
            header("Location: http://localhost/forum/www/", true, 301);
            exit;
        }else {
            echo "Kunne ikke legge til ny kat";
        }
    }

    /* LEGGE TIL UNDERKATEGORIER */
    if (isset($_POST['ny_ukat_btn']) && $_SESSION['bruker_level'] == '2') {
        $ukat_navn = mysqli_real_escape_string($conn, $_POST['ny_ukat_navn']);
        $ukat_besk = mysqli_real_escape_string($conn, $_POST['ny_ukat_besk']);
        $ukat_img = mysqli_real_escape_string($conn, $_POST['ny_ukat_img']);
        $ukat_img_farge = mysqli_real_escape_string($conn, $_POST['ny_ukat_img_farge']);
        $kat_id = $_SESSION['kat_id'];
        
        $sql = mysqli_query($conn, "INSERT INTO underkategori(kat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge) 
                                    VALUES ('$kat_id', '$ukat_navn', '$ukat_besk', '$ukat_img', '$ukat_img_farge')");
       if ($sql) {
           header("Location: http://localhost/forum/www/", true, 301);
           exit;
       }else {
           echo "Kunne ikke legge til ny ukat";
       }
    }

    /* SLETTE KATEGORIER */
    // Sett opp trigger for å slette ukat og tråder som er underliggende
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
?>
