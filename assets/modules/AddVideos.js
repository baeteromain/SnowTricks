let collection, boutonAjout, span
window.onload = () => {
    collection = document.querySelector('#video')
    span = collection.querySelector("span")
    boutonAjout = document.createElement("button")
    boutonAjout.className = "ajout-video btn btn-secondary"
    boutonAjout.innerText = "Ajouter un lien vidéo"

    let nouveauBouton = span.append(boutonAjout)

    collection.dataset.index = collection.querySelectorAll('input').length

    boutonAjout.addEventListener('click', function (){
        addButton(collection, nouveauBouton)
    })
}

function addButton(collection, nouveauBouton){
    let prototype = collection.dataset.prototype
    let index = collection.dataset.index
    prototype = prototype.replace(/__name__/g, index)

    let content = document.createElement('html')
    content.innerHTML = prototype
    let newForm = content.querySelector('div')

    let boutonSuppr = document.createElement('button')
    boutonSuppr.type = "button"
    boutonSuppr.className = "btn btn-danger"
    boutonSuppr.id = 'delete-video-' + index
    boutonSuppr.innerHTML = "Supprimer un lien vidéo"

    newForm.append(boutonSuppr)

    collection.dataset.index++

    let boutonAjout = collection.querySelector(".ajout-video")

    span.insertBefore(newForm, boutonAjout)

    boutonSuppr.addEventListener('click', function (){
        this.previousElementSibling.parentElement.remove()
    })
}