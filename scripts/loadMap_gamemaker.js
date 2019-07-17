var mymap = L.map('mapid').setView([52.247101, 20.983118], 15);

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
}).addTo(mymap);

function loadJSON(path, success, error) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (success)
                    success(JSON.parse(xhr.responseText));
            } else {
                if (error)
                    error(xhr);
            }
        }
    };
    xhr.open("GET", path, true);
    xhr.send();
}

loadJSON("./scripts/json/boiska.json", loadPins);
let boiskaJSON;

function loadPins(boiska) {
    boiskaJSON = boiska;
    for (point of boiska) {
        let pin = L.marker(point[1]).addTo(mymap);
        let popupText = "<p class='popup-name'>" + point[2] + "</p>";
        popupText += "<p class='popup-address'>Ulica: " + point[3] + "</p>";
        popupText += "<p>Kod boiska: " + point[0] + "</p>";
        popupText += "<input class='btn btn-success' value='Zagraj tutaj!' onclick='selectSportObject(\""+point[0]+"\");'>";
        pin.bindPopup(popupText);
    }
}

function selectSportObject(boisko) {
    document.getElementById('place').value = boisko;
}

window.onload = function () {
    let selectPlace = document.getElementById('place');
    selectPlace.addEventListener('change', ()=>{
        for(b of boiskaJSON) {
            if(b[0]==selectPlace.value){
                mymap.setView(b[1], 14);
            }
        }
    });
}