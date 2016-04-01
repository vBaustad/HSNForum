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


    $(".skjul_tbody_btn").click(function () {
        var getID = $(this).attr('id');
        var faclass = ".bildeID" + getID;
        var rowclass = ".radID" + getID;
        $(rowclass).toggle();
        $(faclass).toggleClass('fa-toggle-on');
    });
});

// bruker til registrer.php
function feilmelding(box, melding) {
    $(document).ready(function () {
        $(box).show();
        $("#feilkode").css("display", "block").text(melding);
    })
}

function visbox(box) {
    $(document).ready(function () {
        $(box).show();
    })
}