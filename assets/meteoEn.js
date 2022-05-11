// ************************* POUR L'AFFICHAGE ******************************

// objet qui regroupe les icons de météo du site https://erikflowers.github.io/weather-icons/
const weatherIcons = {
    Rain: "wi wi-sprinkle",
    Clouds: "wi wi-day-cloudy",
    Clear: "wi wi-day-sunny",
    mist: "wi wi-fog",
    Drizzle: "wi wi-day-sleet",
};

// fonction pour mettre 1ère lettre en majuscule pour adapter l'icone metéo par rapport à la valeur récupéré en json
function capitalize(str) {
    return str[0].toUpperCase() + str.slice(1);
}

// ************************** UTILISATION DE LA METHODE DE GEOLOCALISATION DE L'UTILISATEUR *******************

// Utilisation de Geolocation.getCurrentPosition() pour récupérer la position actuelle de l'appareil avec demande d'autorisation de l'utilisateur

var options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0,
};

// Fonction d'affichage de la météo qd l'utilisateur autorise la localisation
async function success(pos) {
    var crd = pos.coords;  // crd est un tableau d'objet pour le voir faire un console.log(crd);


    // déclare des variables pour récupérer les données du tableau d'objet
    let latitude = crd.latitude;
    let longitude = crd.longitude;


    // Récupération de la météo en fonction de la latitude et longitude avec l'api https://openweathermap.org/current#name

    const meteo = await fetch(
        `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=5c1c8f0cd3de20c84aaf760a0a9bfaaf&units=imperial&lang=en`
    )
        .then((resultat) => resultat.json())
        .then((json) => json);


// ****************** Traitement de l'affichage en anglais ***********************

    function infosMeteo(data) {

        // stocke les données de l'objet json dans des constantes
        const name = data.name;
        const temperature = data.main.temp;
        const conditions = data.weather[0].main;
        const description = data.weather[0].description;

        // injecte dans la view
        document.querySelector("#ville").textContent = name;
        document.querySelector("#temperature").textContent = Math.round(temperature);

        // utilise la fonction capitalize pour mettre majuscule
        document.querySelector("#conditions").textContent = capitalize(description);


        // pour afficher l'icone en fonction du tps utilise la constante créée au début sur la constante conditions
        document.querySelector("i.wi").className = weatherIcons[conditions];

        // pour adapter l'image en background en fonction du tps
        document.getElementById("app").className = conditions.toLowerCase();

    }

// appel de la fonction infosMeteo
    infosMeteo(meteo);
}

// Fonction qd l'utilisateur n'autorise pas la localisation
function error(err) {
    console.warn(`ERREUR (${err.code}): ${err.message}`);
}

// appelle de la methode Geolocation.getCurrentPosition() qui prend en parametre les fonctions success, error et les options
navigator.geolocation.getCurrentPosition(success, error, options);




