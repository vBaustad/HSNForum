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

	if (ptn.test(document.getElementById(verdi).value)) {
		document.getElementById('bnavnErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('bnavnErr').style.display = "block";
	}
}

function sjekkFNavn(verdi) {
	var ptn = /^[A-Za-z -']+$/;

	if (ptn.test(document.getElementById(verdi).value)) {
		document.getElementById('fnavnErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('fnavnErr').style.display = "block";
	}
}

function sjekkENavn(verdi) {
	var ptn = /^[A-Za-z -']+$/;

	if (ptn.test(document.getElementById(verdi).value)) {
		document.getElementById('enavnErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('enavnErr').style.display = "block";
	}
}

function sjekkEpost(verdi) {
	var ptn = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	
	if (ptn.test(document.getElementById(verdi).value)) {
		document.getElementById('epostErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('epostErr').style.display = "block";
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
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('passErr').style.display = "block";
	}
}

function sjekkPassTo(verdi) {
	var pass1 = document.getElementById('pass_reg').value;
	var pass2 = document.getElementById('pass_two_reg').value;

	if (pass2 == pass1) {
		document.getElementById('passTwoErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('passTwoErr').style.display = "block";
	}
}

// Skal returnere true for at vi kan sende inn skjemaet
function sjekkSkjema() {
	var bnavn = document.getElementById('brukernavn_reg').value;
	var fnavn = document.getElementById('fornavn_reg').value;
	var enavn = document.getElementById('etternavn_reg').value;
	var epost = document.getElementById('epost_reg').value;
	var pass  = document.getElementById('pass_reg').value;
	var passTo = document.getElementById('pass_two_reg').value;

	var ok = 0;

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
	if (ok === 6) {
		return true;
	}
	else {
		return false;
	}
}