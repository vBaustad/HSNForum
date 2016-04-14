$(document).ready(function () {
    // Lukker vinduer
    $(".button-lukk, .registrer-icon-lukk, .box-icon-lukk, #registrer-avbryt, #ny_traad_btn").click(function () {
        $("#slett_traad, .endringer_box, .registrer-box-success, #registrer-mail-sendt, #registrer-feil, #registrer-box," +
            "#logginn-box, #ny_kat, #ny_ukat, #slett_kat, #logginn-box-ikke-aktiv, #slett_ukat").hide();
    });

    // Skjul/vis bokser
    $("#registrer").click(function () {
        $("#registrer-box").show();
    });
    $("#logg_inn").click(function () {
        $("#logginn-box").show();
    });

    // Skjuler kategorier
    $(".skjul_tbody_btn").click(function () {
        var getID = $(this).attr('id');
        var faclass = ".bildeID" + getID;
        var rowclass = ".radID" + getID;
        $(rowclass).toggle();
        $(faclass).toggleClass('fa-caret-square-o-down').toggleClass('fa-caret-square-o-up');
        $(this).parent().toggleClass('tablehide');
    });
    
    // bruker.php funksjoner
    $("#bruker_info").show();
    $("#om_bruker").addClass("bruker_container_pil green_bg");
    $("#endre_pass, #endre_epost, #endre_bilde, #endre_rettigheter").removeClass("bruker_container_pil green_bg");
    $("#endre_pass_box, #endre_epost_box, #endre_bilde_box, #endre_rettigheter_box").hide();

    $("#om_bruker").click(function () {
        $("#bruker_info").show();
        $("#om_bruker").addClass("bruker_container_pil green_bg");
        $("#endre_pass, #endre_epost, #endre_bilde, #endre_rettigheter").removeClass("bruker_container_pil green_bg");
        $("#endre_pass_box, #endre_epost_box, #endre_bilde_box, #endre_rettigheter_box").hide();
    });
    $("#endre_rettigheter").click(function () {
        $("#endre_rettigheter_box").show();
        $("#endre_rettigheter").addClass("bruker_container_pil green_bg");
        $("#om_bruker, #endre_epost, #endre_bilde").removeClass("bruker_container_pil green_bg");
        $("#endre_pass_box, #bruker_info, #endre_epost_box, #endre_bilde_box").hide();
    });
    $("#endre_pass").click(function () {
        $("#endre_pass_box").show();
        $("#endre_pass").addClass("bruker_container_pil green_bg");
        $("#om_bruker, #endre_epost, #endre_bilde, #endre_rettigheter").removeClass("bruker_container_pil green_bg");
        $("#bruker_info, #endre_epost_box, #endre_bilde_box, #endre_rettigheter_box").hide();
    });
    $("#endre_epost").click(function () {
        $("#endre_epost_box").show();
        $("#endre_epost").addClass("bruker_container_pil green_bg");
        $("#om_bruker, #endre_pass, #endre_bilde, #endre_rettigheter").removeClass("bruker_container_pil green_bg");
        $("#bruker_info, #endre_pass_box, #endre_bilde_box, #endre_rettigheter_box").hide();
    });
    $("#endre_bilde").click(function () {
        $("#endre_bilde_box").show();
        $("#endre_bilde").addClass("bruker_container_pil green_bg");
        $("#om_bruker, #endre_pass, #endre_epost").removeClass("bruker_container_pil green_bg");
        $("#bruker_info, #endre_pass_box, #endre_epost_box").hide();
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
} //TODO: Hva gjør egentlig denne? lol...

function chat() {
    var melding = document.getElementById('chat_msg_text').value;
    if (melding == "") {
        return false;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("meldinger").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "includes/chat.php?melding="+melding, true);
        xmlhttp.send();
    }
    // Tømmer input field.
    document.getElementById('chat_msg_text').value = '';
}

function slettPost(id) {
    $("#slett_innlegg").show();

    $("#slett_innlegg_btn").click(function () {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("meldinger").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "includes/endringer.php?innlegg_id="+id, true);
        xmlhttp.send();
        location.reload();
    });
}
