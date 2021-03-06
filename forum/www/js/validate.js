/*                                       */
/*  Funksjoner for "backend" validering  */
/*                                       */
function valBNavn(verdi) {
    var ptn = /^[A-Za-z0-9]+$/;
    return ptn.test(verdi);
}

function valNavn(verdi) {
    var ptn = /^[A-Za-z0-9øæåØÆÅ]+$/;
    return ptn.test(verdi);
}

function valEpost(verdi) {
    var ptn = /^(([0-9]{6})+@+(student)+.|([a-zA-Z]+.+[a-zA-Z]+@))+(hit|usn|hbv)+.(no)$/;
    return ptn.test(verdi)
}

function valPassord(verdi) {
    /*
     * RegEx forklart
     * Passordet MÅ inneholde:
     * x antall karakterer mellom a-z (lower case)
     * x antall karakterer mellom a-z (upper case)
     * x antall siffer mellom 0-9
     * All of the above er KRAV. De må til sammen tilsvare en lengde på 6+
     * Det godtaes også noen spesialtegn som (!, @, $, ?, %, *, og &). Dise er ikke påkrevet.
     * */
    var ptn = /^(?=.*[a-z])(?=.*[A-Z])(?=.+[0-9])[a-zA-Z0-9$@$!%*?&]{6,}$/;
    return ptn.test(verdi);
}

/*                                    */
/*  Funksjoner for visuel validering  */
/*                                    */
function sjekkBNavn(verdi) {
    var ptn = /^[A-Za-z0-9]+$/;

    var bnavn = document.getElementById("brukernavn_reg").value;

    if (bnavn == 0) {
        document.getElementById("sjekkBnavn").innerHTML = "";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
        document.getElementById("bnavnErr").innerHTML = 'Brukernavn kan ikke vær blankt';
    } else {

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("sjekkBnavn").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "includes/sjekkinfo.php?bnavn="+bnavn, true);
        xmlhttp.send();

        var resultat = document.getElementById("sjekkBnavn").innerHTML;

        if (resultat.indexOf("er tatt!") >= 0) {
            document.getElementById('sjekkBnavn').style.display = "block";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
        else {
            if (resultat.indexOf("er ledig") >= 0) {
                document.getElementById('sjekkBnavn').style.display = "";
            }

            if (ptn.test(document.getElementById(verdi).value) && bnavn != "") {
                document.getElementById(verdi).style.border = 'solid 3px #60bb80';
                document.getElementById("bnavnErr").innerHTML = '';
            }
            else if (!ptn.test(document.getElementById(verdi).value) && bnavn != "") {
                document.getElementById(verdi).style.border = 'solid 3px #e35152';
                document.getElementById("bnavnErr").innerHTML = 'Ugyldige karakterer!';
            }
        }
    }
}

function sjekkFNavn(verdi) {
    var ptn = /^[A-Za-z0-9øæåØÆÅ]+$/;
    var fnavn = document.getElementById(verdi).value;

    if (fnavn == "") {
        document.getElementById("fnavnErr").innerHTML = "Fornavn kan ikke være blank";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
    }
    else {
        if (ptn.test(fnavn)) {
            document.getElementById("fnavnErr").innerHTML = "";
            document.getElementById(verdi).style.border = 'solid 3px #60bb80';
            return true;
        }
        else {
            document.getElementById("fnavnErr").innerHTML = "Fornavnet har ugyldige karakterer";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
    }
}

function sjekkENavn(verdi) {
    var ptn = /^[A-Za-z0-9øæåØÆÅ]+$/;
    var enavn = document.getElementById(verdi).value;

    if (enavn == "") {
        document.getElementById("enavnErr").innerHTML = "Etternavn kan ikke være blank";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
    }
    else {
        if (ptn.test(document.getElementById(verdi).value)) {
            document.getElementById("enavnErr").innerHTML = "";
            document.getElementById(verdi).style.border = 'solid 3px #60bb80';
            return true;
        }
        else {
            document.getElementById("enavnErr").innerHTML = "Etternavn har ugyldige karakterer";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
    }
}

function sjekkEpost(verdi) {
    /*
     * RegEx forklart
     * Enten:    6 på rad mellom 0-9 + "@" + ordet "student" etterfulgt av punktum.
     * Eller:    x antall bokstaver a-z etterfulgt av punktum etterfulgt av x antall bokstaver igjen a-z + "@"
     * Deretter: hit eller usn eller hbn etterfulgt av punktum etterfulgt av "no"
     * */
    var ptn = /^(([0-9]{6})+@+(student)+.|([a-zA-Z]+.+[a-zA-Z]+@))+(hit|usn|hbv)+.(no)$/;

    // Brukes for testing-purposes
    //var ptn = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var epost = document.getElementById("epost_reg").value;

    if (epost == 0) {
        document.getElementById("epostErr").innerHTML = "Epost kan ikke være blank";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
    } else {

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("sjekkEpost").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "includes/sjekkinfo.php?epost=" +epost, true);
        xmlhttp.send();

        var resultat = document.getElementById("sjekkEpost").innerHTML;

        if (resultat.indexOf("er tatt!") >= 0) {
            document.getElementById("epostErr").innerHTML = "";
            document.getElementById('sjekkEpost').style.display = "block";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
        else {
            if (resultat.indexOf("er ledig") >= 0) {
                document.getElementById('sjekkEpost').style.display = "";
            }

            if (ptn.test(document.getElementById(verdi).value)) {
                document.getElementById("epostErr").innerHTML = "";
                document.getElementById(verdi).style.border = 'solid 3px #60bb80';
                return true;
            }
            else {
                document.getElementById(verdi).style.border = 'solid 3px #e35152';
                document.getElementById("epostErr").innerHTML = "Ugyldig epost!";
            }
        }
    }
}

// For validering ved endring av epost
function sjekkEpostTo(verdi) {
    var epost1 = document.getElementById('epost_reg').value;
    var epost2 = document.getElementById('epost_reg_two').value;

    if (epost2 == 0) {
        document.getElementById('epostErrTwo').innerHTML = "Kan ikke være blank!";
        document.getElementById(verdi).style.border = "solid 3px #e35152";
    } else {
        if (epost1 == epost2) {
            document.getElementById('epostErrTwo').innerHTML = "";
            document.getElementById(verdi).style.border = "solid 3px #60bb80";
        } else {
            document.getElementById('epostErrTwo').innerHTML = "Stemmer ikke overens";
            document.getElementById(verdi).style.border = "solid 3px #e35152";
        }
    }
}

function sjekkPass(verdi) {
    var ptn = /^(?=.*[a-z])(?=.*[A-Z])(?=.+[0-9])[a-zA-Z0-9$@$!%*?&]{6,}$/;

    var pw = document.getElementById(verdi).value;
    if (pw == 0) {
        document.getElementById("passErr").innerHTML = "Passordet kan ikke være blank";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
    } else {
        if (ptn.test(document.getElementById(verdi).value)) {
            document.getElementById("passErr").innerHTML = "";
            document.getElementById(verdi).style.border = 'solid 3px #60bb80';
            return true;
        }
        else {
            document.getElementById('passErr').innerHTML = "Passordet må ha en minst stor bokstag og ett tall";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
            document.getElementById('passErr').style.display = "block";
        }
    }
}

function sjekkPassTo(verdi) {
    var pass1 = document.getElementById('pass_reg').value;
    var pass2 = document.getElementById('pass_two_reg').value;

    if (pass2 == pass1) {
        document.getElementById('passTwoErr').innerHTML = "";
        document.getElementById(verdi).style.border = "solid 3px #60bb80";
        return true;
    } else {
        document.getElementById(verdi).style.border = "solid 3px #e35152";
        document.getElementById('passTwoErr').innerHTML = "Passordene er ikke like!";
    }
}

// Skal returnere true for at vi kan sende inn skjemaet
function sjekkSkjema() {
    var bnavn = document.getElementById('brukernavn_reg').value;
    var fnavn = document.getElementById('fornavn_reg').value;
    var enavn = document.getElementById('etternavn_reg').value;
    var epost = document.getElementById('epost_reg').value;
    var pass = document.getElementById('pass_reg').value;
    var passTo = document.getElementById('pass_two_reg').value;

    var ok = 0;

    // Brukernavn regEx er OK
    if (valBNavn(bnavn)) {
        ok += 1;
    }

    if (valNavn(fnavn)) {
        ok += 1;
    }

    if (valNavn(enavn)) {
        ok += 1;
    }

    if (valEpost(epost)) {
        ok += 1;
    }

    if (valPassord(pass)) {
        ok += 1;
    }

    if (pass === passTo) {
        ok += 1;
    }

    // Hvis alle 6 felter er riktige, send skjema!
    if (ok == 6) {
        return true;
    }
}

function innleggVal() {
    var innhold = document.getElementById("innlegg_innhold").value;
    // Hvis innholdet er KUN whitespace elle er tom
    if (!/\S/.test(innhold)) {
        document.getElementById("innlegg_innhold").style.border = 'solid 3px #e35152';
        document.getElementById("innleggErr").style.display = "block";
        document.getElementById("innleggErr").innerHTML = "Innlegget må inneholde noe mer enn bare mellomrom!";

    } else if (innhold == "") {
        document.getElementById("innlegg_innhold").style.border = 'solid 3px #e35152';
        document.getElementById("innleggErr").style.display = "block";
        document.getElementById("innleggErr").innerHTML = "Du må skrive noe først!";
    } else if (innhold.length < 6) {
        document.getElementById("innlegg_innhold").style.border = 'solid 3px #e35152';
        document.getElementById("innleggErr").style.display = "block";
        document.getElementById("innleggErr").innerHTML = "Innlegget må ha mer enn 5 bokstaver!";
    } else {
        document.getElementById("innlegg_innhold").style.border = 'solid 3px #60bb80';
        document.getElementById("innleggErr").style.display = "none";
        return true;
    }
    return false;
}

function traadVal() {
    var tittel = document.getElementById("ny_traad_navn").value;
    var innhold = document.getElementById("ny_traad_text").value;

    var tittelok = 0;
    var innholdok = 0;

    if (!/\S/.test(tittel)) {
        document.getElementById("ny_traad_navn").style.border = 'solid 3px #e35152';
        document.getElementById("TittelErr").style.display = "block";
        document.getElementById("TittelErr").innerHTML = "Tittelen kan ikke være tom!";
    } else if (tittel == "") {
        document.getElementById("ny_traad_navn").style.border = 'solid 3px #e35152';
        document.getElementById("TittelErr").style.display = "block";
        document.getElementById("TittelErr").innerHTML = "Tittelen kan ikke være tom!";
    } else if (tittel.length < 6) {
        document.getElementById("ny_traad_navn").style.border = 'solid 3px #e35152';
        document.getElementById("TittelErr").style.display = "block";
        document.getElementById("TittelErr").innerHTML = "Tittelen må ha mer enn 5 bokstaver!";
    } else {
        document.getElementById("ny_traad_navn").style.border = 'solid 3px #60bb80';
        document.getElementById("TittelErr").style.display = "none";
    }

    if (!/\S/.test(innhold)) {
        document.getElementById("ny_traad_text").style.border = 'solid 3px #e35152';
        document.getElementById("InnholdErr").style.display = "block";
        document.getElementById("InnholdErr").innerHTML = "Innholdet kan ikke være tomt!";
    } else if (innhold == "") {
        document.getElementById("ny_traad_text").style.border = 'solid 3px #e35152';
        document.getElementById("InnholdErr").style.display = "block";
        document.getElementById("InnholdErr").innerHTML = "Innholdet kan ikke være tomt!";
    } else if (innhold.length < 6) {
        document.getElementById("ny_traad_text").style.border = 'solid 3px #e35152';
        document.getElementById("InnholdErr").style.display = "block";
        document.getElementById("InnholdErr").innerHTML = "Innlegget må ha mer enn 5 bokstaver!";
    } else {
        document.getElementById("ny_traad_text").style.border = 'solid 3px #60bb80';
        document.getElementById("InnholdErr").style.display = "none";

    }

    if (/\S/.test(innhold) && innhold != "" && innhold.length > 5 && /\S/.test(tittel) && tittel != "" && tittel.length > 5) {
        return true;
    }

    return false;
}