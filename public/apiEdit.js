/* Comme on utilise Jquery, il faut attendre que l'index.html.twig soit chargé avant de lancer ce script */
$(document).ready(function () {

    /* déclaration de la constante pour l'url de l'api
    prendre sur le site du gouv l'api qu'on souhaite
    on ajoute /search/ et le paramètre ?q= pour récupérer toutes les données */
    const apiUrl = "https://api-adresse.data.gouv.fr/search/?q=";

    /* déclaration de la constante pour ajouter à l'url de l'api le paramètre pour récupérer les données au format json */
    const format = '&format=json';

    /* déclaration de la variable adresse qui stocke l'id de l'input du fichier index.html.twig */
    let adresse = $('#clients_adresse');

    /* création de l'évenement onKeyPress sur l'input adresse */
    $(adresse).on('keyup', function () {

        /* stockage dans une variable le champ adresse de l'input et sa valeur (= .val) */
        let adrs = $(this).val();

        /* création de la requête qui sera envoyée a l'api
        * on concatène l'url de l'api avec la valeur qui est tapé dans l'input et qu'on souhaite recevoir au format json */
        let url = apiUrl + adrs + format;

        /* envoie de la requete à l'api par le biais du fetch
        * envoie de l'url en method GET, ensuite la réponse souhaitée est en json (d'où le response.json)
        * et ensuite on définit ce qu'on veut faire des résultats */
        fetch(url, {method: 'GET'}).then(response => response.json()).then(results => {


            /* ajout d'un paramètre qui ouvre le menu déroulant que lorsqu'il y a au moins 5 résultats renvoyés par l'api */
            $('#adrs').attr('size', 5);

            /* injecte sur l'index les résultats de l'api que l'on souhaite afficher
            * là on veut le label donc on refait tout le chemin
            * on veut le résulta dans le tableau features à la position 0 puis dans properties on veut le label  */
            document.getElementById('api').innerHTML =
                `<select onclick="myFunction2()" class="form-select" id="adrs">
                <option value="` + results.features[0].properties.label + `">"` + results.features[0].properties.label + `"</option>
                <option value="` + results.features[1].properties.label + `">"` + results.features[1].properties.label + `"</option>
                <option value="` + results.features[2].properties.label + `">"` + results.features[2].properties.label + `"</option>
                <option value="` + results.features[3].properties.label + `">"` + results.features[3].properties.label + `"</option>
                <option value="` + results.features[4].properties.label + `">"` + results.features[4].properties.label + `"</option>
                </select>`;


        });

    });


});
