/* Funksjoner for "backend" validering */
function valBNavn(verdi) {
    var ptn = /^[A-Za-z0-9]+$/;
    return ptn.test(verdi);
}

function valNavn(verdi) {
    var ptn = /^[a-zA-Z]+$/;
    return ptn.test(verdi);
}

function valEpost(verdi) {
    var ptn = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return ptn.test(verdi)
}

function lengde(verdi, min, max) {
    return (verdi.length >= min && verdi.length <= max);
}

function valPassord(verdi) {
    var ptn1 = /[a-z]+/;
    var ptn2 = /[A-Z]+/;
    var ptn3 = /[0-9]+/;
    return lengde(verdi, 6, 10) && ptn1.test(verdi) && ptn2.test(verdi) && ptn3.test(verdi);
}

/* Funksjoner for visuel validering */
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
        xmlhttp.open("GET", "http://localhost/forum/www/includes/sjekkinfo.php?bnavn="+bnavn, true);
        xmlhttp.send();

        var resultat = document.getElementById("sjekkBnavn").innerHTML;

        if (resultat.indexOf("er tatt!") >= 0) {
            document.getElementById('sjekkBnavn').style.display = "block";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
        else {
            if (resultat.indexOf("er ledig") >= 0) {
                document.getElementById('sjekkBnavn').style.display = "none";
            }

            if (ptn.test(document.getElementById(verdi).value) && bnavn != "") {
                document.getElementById(verdi).style.border = 'solid 3px #60bb80';
            }
            else if (!ptn.test(document.getElementById(verdi).value) && bnavn != "") {
                document.getElementById(verdi).style.border = 'solid 3px #e35152';
                document.getElementById("bnavnErr").innerHTML = 'Feil brukernavn';
            }
        }
    }
}

// Dårlig regEx (f.eks: ! er lov)!
function sjekkFNavn(verdi) {
    var ptn = /^[A-Za-z -']+$/;
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

// Dårlig regEx (f.eks: ! er lov)!
function sjekkENavn(verdi) {
    var ptn = /^[A-Za-z -']+$/;
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
    // LAG MIN EGEN REGEX!
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
        xmlhttp.open("GET", "http://localhost/forum/www/includes/sjekkinfo.php?epost=" +epost, true);
        xmlhttp.send();

        var resultat = document.getElementById("sjekkEpost").innerHTML;

        if (resultat.indexOf("er tatt!") >= 0) {
            document.getElementById("epostErr").innerHTML = "";
            document.getElementById('sjekkEpost').style.display = "block";
            document.getElementById(verdi).style.border = 'solid 3px #e35152';
        }
        else {
            if (resultat.indexOf("er ledig") >= 0) {
                document.getElementById('sjekkEpost').style.display = "none";
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

function sjekkPass(verdi) {
    var ptn1 = /[a-z]+/;
    var ptn2 = /[A-Z]+/;
    var ptn3 = /[0-9]+/;

    if (ptn1.test(document.getElementById(verdi).value)
        && ptn2.test(document.getElementById(verdi).value)
        && ptn3.test(document.getElementById(verdi).value)) {
        document.getElementById('passErr').style.display = "none";
        document.getElementById(verdi).style.border = 'solid 3px #60bb80';
        return true;
    }
    else {
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
        document.getElementById('passErr').style.display = "block";
    }
}

function sjekkPassTo(verdi) {
    var pass1 = document.getElementById('pass_reg').value;
    var pass2 = document.getElementById('pass_two_reg').value;

    if (pass2 == pass1) {
        document.getElementById('passTwoErr').style.display = "none";
        document.getElementById(verdi).style.border = 'solid 3px #60bb80';
        return true;
    }
    else {
        document.getElementById(verdi).style.border = 'solid 3px #e35152';
        document.getElementById('passTwoErr').style.display = "block";
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
    return ok == 6;
}