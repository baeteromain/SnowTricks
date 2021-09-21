let collection, collectionVideos, boutonAjoutVideos, spanVideo, trickvideo


window.onload = () => {
    // Gestion de l'ajout / modification / suppression des liens vidéos
    collectionVideos = document.querySelector("#video");
    spanVideo = collectionVideos.querySelector("#video_span");
    boutonAjoutVideos = document.createElement("button");
    boutonAjoutVideos.className = "ajout-video btn btn-secondary";
    boutonAjoutVideos.innerText = "Add video link";

    trickvideo = document.querySelector('#trick_videos');

    trickvideo.querySelectorAll("div.mb-3").forEach((vid) => {
        let boutonSupprOldVideo = document.createElement("button");
        boutonSupprOldVideo.type = "button";
        boutonSupprOldVideo.className = "btn btn-danger mt-1";

        boutonSupprOldVideo.innerHTML = "Supprimer ce lien vidéo";
        let iframe = document.querySelectorAll('iframe').forEach((ifra) => {
            if (vid.firstChild.value.includes("dailymotion")) {
                embed = vid.firstChild.value.replace("/video/", "/embed/video/");
            } else {
                embed = vid.firstChild.value.replace("watch?v=", "embed/");
            }
            if (ifra.src === embed) {
                vid.appendChild(ifra);
            }
        })

        vid.appendChild(boutonSupprOldVideo);
        boutonSupprOldVideo.addEventListener("click", function () {
            this.previousElementSibling.parentElement.remove();
        })
    })


    let nouveauBoutonVideo = spanVideo.append(boutonAjoutVideos);


    collectionVideos.dataset.index = collectionVideos.querySelectorAll("input").length;

    boutonAjoutVideos.addEventListener("click", function () {
        addButtonVideo(collectionVideos, nouveauBoutonVideo);
    })


    // Gestion de la suppression des liens images
    // On boucle sur links
    let links = document.querySelectorAll("[data-delete]");
    for (link of links) {
        // On écoute le clic
        link.addEventListener("click", function (e) {
            // On empêche la navigation
            e.preventDefault();

            // On envoie une requête Ajax vers le href du lien avec la méthode DELETE
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(
                // On récupère la réponse en JSON
                (response) => response.json()
            ).then((data) => {
                if (data.success) {
                    document.querySelector(".img-card-update").parentElement.remove()
                } else {
                    alert(data.error)
                }
            }).catch((e) => alert(e))
        })
    }
}

function addButtonVideo(collection, nouveauBouton) {
    let prototype = collection.dataset.prototype;
    let index = collection.dataset.index;
    prototype = prototype.replace(/__name__/g, index);

    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");

    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.className = "btn btn-danger mb-3";
    boutonSuppr.id = "delete-video-" + index;
    boutonSuppr.innerHTML = "Supprimer un lien vidéo";

    newForm.append(boutonSuppr);

    collection.dataset.index++;

    let boutonAjout = collection.querySelector(".ajout-video");

    spanVideo.insertBefore(newForm, boutonAjout);

    boutonSuppr.addEventListener("click", function () {
        this.previousElementSibling.parentElement.remove();
    })
}


