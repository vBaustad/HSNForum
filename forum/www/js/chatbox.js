// TODO: Flytt inn i functions.js
function chat() {
    var melding = document.getElementById('chat_msg_text').value;
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
        xmlhttp.open("GET", "includes/chat.php?melding="+melding, true);
        xmlhttp.send();
    }

    document.getElementById('chat_msg_text').value = '';
}