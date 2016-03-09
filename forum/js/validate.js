function sjekkBNavn(verdi) {
	var re = /[A-Za-z -']$/;
	if(re.test(document.getElementById(verdi).value)) {
		document.getElementById('bnavnErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('bnavnErr').style.display = "block";
	}
}

function sjekkNavn(verdi) {
	var re = /[A-Za-z -']$/;
	if(re.test(document.getElementById(verdi).value)) {
		document.getElementById('navnErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('navnErr').style.display = "block";
	}
}

function sjekkEpost(verdi) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if(re.test(document.getElementById(verdi).value)) {
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
		document.getElementById('epostErr').style.display = "none";
		document.getElementById(verdi).style.border ='solid 3px #60bb80';
		return true;
	}
	else {
		document.getElementById(verdi).style.border ='solid 3px #e35152';
		document.getElementById('epostErr').style.display = "block";
	}
}

// Ikke ferdig/implementert! Eventuelt flytte denne inn i sjekkPass?
function matchPassord() {
	var pw1 = document.getElementById('pass_reg');
	var pw2 = document.getElementById('pass_two_reg');

	if (pw2 == pw1) {
		alert("De er like!");
	};
}

// Skal returnere true for at vi kan sende inn skjemaet
function sjekkSkjema() {
	return false;
}