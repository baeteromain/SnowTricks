let collection, boutonAjout, span, collectionVideos, boutonAjoutVideos, span_video
if(window.location.pathname === "/trick/add"){

    window.onload = () => {
        collection = document.querySelector('#image')
        span = collection.querySelector("span")
        boutonAjout = document.createElement("button")
        boutonAjout.className = "ajout-image btn btn-secondary"
        boutonAjout.innerText = "Ajouter une image"

        let nouveauBouton = span.append(boutonAjout)

        collection.dataset.index = collection.querySelectorAll('input').length

        boutonAjout.addEventListener('click', function () {
            addButton(collection, nouveauBouton)
        })

        collectionVideos = document.querySelector('#video')
        span_video = collectionVideos.querySelector('#video_span')
        boutonAjoutVideos = document.createElement("button")
        boutonAjoutVideos.className = "ajout-video btn btn-secondary"
        boutonAjoutVideos.innerText = "Ajouter un lien vidéo"

        let nouveauBoutonVideo = span_video.append(boutonAjoutVideos)

        collectionVideos.dataset.index = collectionVideos.querySelectorAll('input').length

        boutonAjoutVideos.addEventListener('click', function () {
            addButtonVideo(collectionVideos, nouveauBoutonVideo)
        })
    }

    function addButton(collection, nouveauBouton) {
        let prototype = collection.dataset.prototype
        let index = collection.dataset.index
        prototype = prototype.replace(/__name__/g, index)

        let content = document.createElement('html')
        content.innerHTML = prototype
        let newForm = content.querySelector('div')

        let boutonSuppr = document.createElement('button')
        boutonSuppr.type = "button"
        boutonSuppr.className = "btn btn-danger mb-3"
        boutonSuppr.id = 'delete-image-' + index
        boutonSuppr.innerHTML = "Supprimer une image"

        newForm.append(boutonSuppr)

        collection.dataset.index++

        let boutonAjout = collection.querySelector(".ajout-image")

        span.insertBefore(newForm, boutonAjout)

        boutonSuppr.addEventListener('click', function () {
            this.previousElementSibling.parentElement.remove()
        })
    }

    function addButtonVideo(collection, nouveauBouton) {
        let prototype = collection.dataset.prototype
        let index = collection.dataset.index
        prototype = prototype.replace(/__name__/g, index)

        let content = document.createElement('html')
        content.innerHTML = prototype
        let newForm = content.querySelector('div')

        let boutonSuppr = document.createElement('button')
        boutonSuppr.type = "button"
        boutonSuppr.className = "btn btn-danger mb-3"
        boutonSuppr.id = 'delete-video-' + index
        boutonSuppr.innerHTML = "Supprimer un lien vidéo"

        newForm.append(boutonSuppr)

        collection.dataset.index++

        let boutonAjout = collection.querySelector(".ajout-video")

        span_video.insertBefore(newForm, boutonAjout)

        boutonSuppr.addEventListener('click', function () {
            this.previousElementSibling.parentElement.remove()
        })
    }
}

