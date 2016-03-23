function chat() {

    var melding = document.getElementById('chat_msg').value;

    if (melding == "") {
        alert ("Du må nesten skrive noe først...");
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("meldinger").innerHTML = xmlhttp.responseText;
                }
            }
        xmlhttp.open("GET", "http://localhost/forum/www/includes/chatinn.php?melding="+melding, true);
        xmlhttp.send();
    }
}