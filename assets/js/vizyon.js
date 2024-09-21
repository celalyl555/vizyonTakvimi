function openTab2(evt, tabName) {
    var i, tabcontent1, tablinks;

    tabcontent1 = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent1.length; i++) {
        tabcontent1[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.getElementById("vizyondaYeni").style.display = "block";