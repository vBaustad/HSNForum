<!DOCTYPE html>
<html lang="no">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Forum for studenter på HSN avdeling Bø</title>
    <!-- CSS, FONTS AND OTHER LIBS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800,400italic' rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet-m.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
    $(document).ready(function () {
        // Lukker vindues
        $(".registrer-button-lukk, .registrer-icon-lukk, .logginn-icon-lukk, #registrer-avbryt").click(function () {
            $(".registrer-box-success").hide();
            $(".registrer-mail-sendt").hide();
            $("#registrer-feil").hide();
            $("#registrer-box").hide();
            $("#logginn-box").hide();
        });

        // Loading...
        $(".registrer-button").click(function () {
            $(".registrer-box-loading").show();
            $(window).load(function () {
                $(".registrer-box-loading").hide();
            });
        });

        // Skjul/vis registrer-box
        $("#registrer").click(function () {
            $("#registrer-box").show();
        });
        $("#logg_inn").click(function () {
           $("#logginn-box").show();
        });
    });
</script>

<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';
?>

<br>
<table class="main-table table forum table-striped">
    <thead>
    <tr>
        <th class="cell-stat"></th>
        <th>
            <h2>Informatikk</h2>
        </th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td class="center"><i class="fa fa-internet-explorer fa-2x"></i></span></td>
        <td>
            <h4><a href="#">Databaser og Web</a><br>
                <small>Alle spørsmål angående Databaser og Web</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    <tr>
        <td class="center"><i class="fa fa-tasks fa-2x"></i></td>
        <td>
            <h4><a href="#">Objektorientert programnering</a><br>
                <small>Javastuff!</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    </tbody>
</table>

<table class="main-table table forum table-striped">
    <thead>
    <tr>
        <th class="cell-stat"></th>
        <th>
            <h2>Kat1</h2>
        </th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td class="center"><i class="fa fa-question fa-2x"></i></td>
        <td>
            <h4><a href="#">Kat1_1</a><br>
                <small>En beskrivelse</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    <tr>
        <td class="center"><i class="fa fa-question fa-2x"></i></td>
        <td>
            <h4><a href="#">Kat1_1</a><br>
                <small>En beskrivelse</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    </tbody>
</table>

<table class="main-table table forum table-striped">
    <thead>
    <tr>
        <th class="cell-stat"></th>
        <th>
            <h2>Kat1</h2>
        </th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td class="center"><i class="fa fa-question fa-2x"></i></td>
        <td>
            <h4><a href="#">Kat1_1</a><br>
                <small>En beskrivelse</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    <tr>
        <td class="center"><i class="fa fa-question fa-2x"></i></td>
        <td>
            <h4><a href="#">Kat1_1</a><br>
                <small>En beskrivelse</small>
            </h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
        <td class="hidden-xs hidden-sm">
            av <a href="#">Bruker:1</a><br>
            <small><i class="fa fa-clock-o"></i> 1 dag siden</small>
        </td>
    </tr>
    </tbody>
</table>

<?php
    require_once 'includes/footer.php';
?>
</body>
</html>