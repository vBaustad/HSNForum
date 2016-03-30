$(document).ready(function () {
    // Lukker vinduer
    $(".button-lukk, .registrer-icon-lukk, .logginn-icon-lukk, #registrer-avbryt").click(function () {
        $(".registrer-box-success, #registrer-mail-sendt, #registrer-feil, #registrer-box," +
            "#logginn-box, #ny_kat, #ny_ukat, #slett_kat, #logginn-box-ikke-aktiv").hide();
    });

    // Skjul/vis bokser
    $("#registrer").click(function () {
        $("#registrer-box").show();
    });
    $("#logg_inn").click(function () {
        $("#logginn-box").show();
    });
});