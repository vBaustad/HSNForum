/*                                       */
/*  Funksjoner for "backend" validering  */
/*                                       */
function valBNavn(verdi) {
    var ptn = /^[A-Za-z0-9]+$/;
    return ptn.test(verdi);
}

function valNavn(verdi) {
    var ptn = /^[a-zA-Z]+$/;
    return ptn.test(verdi);
}

function valEpost(verdi) {
    // var ptn = /^(([0-9]{6})+@+(student)+.|([a-zA-Z]+.+[a-zA-Z]+@))+(hit|usn|hbv)+.(no)$/;

    // Brukes for testing-purposes
    var ptn = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return ptn.test(verdi)
}

function lengde(verdi, min, max) {
    return (verdi.length >= min && verdi.length <= max);
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

    // Erstatt mange linjer(document.getelementbyid med var bnavn ?

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
    // var ptn = /^(([0-9]{6})+@+(student)+.|([a-zA-Z]+.+[a-zA-Z]+@))+(hit|usn|hbv)+.(no)$/;

    // Brukes for testing-purposes
    var ptn = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

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
    var ptn1 = /[a-z]+/;
    var ptn2 = /[A-Z]+/;
    var ptn3 = /[0-9]+/;

    var pw = document.getElementById(verdi).value;
    if (pw == 0) {
        document.getElementById("passErr").innerHTML = "Epost kan ikke være blank";
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
    } else {
        if (ptn1.test(document.getElementById(verdi).value)
            && ptn2.test(document.getElementById(verdi).value)
            && ptn3.test(document.getElementById(verdi).value)) {
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