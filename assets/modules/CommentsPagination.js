let navbar_comment, comments, nbPage, prev, next, total

window.onload = () => {



    navbar_comment = document.querySelector('#nav_comments');
    $links = navbar_comment.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            nbPage = link.dataset.page
            total = link.dataset.total
            let slug = link.dataset.slug
            if (parseInt(nbPage) != 0) {
                prev = document.querySelector('#prev')
                prev.dataset.page = parseInt(nbPage) - 1
            }
            if (parseInt(nbPage) != 0 && parseInt(nbPage) < total) {
                next = document.querySelector('#next')
                next.dataset.page = parseInt(nbPage) + 1
            }

            loadURL('/trick/' + slug + '/comments/' + nbPage)
        })
    })

    // SeeMedia button management

    let  buttonShow = document.querySelector('#show_media');
    buttonShow.addEventListener('click', () => {
        let media = document.querySelector('#trick_media');
        media.classList.toggle('hideP');
    })

}

async function loadURL(url) {
    const response = await fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    if (response.status >= 200 && response.status < 300) {
        const data = await response.json()
        if (parseInt(nbPage) == 1) {
            prev.parentNode.classList.add('disabled')
        } else {
            prev.parentNode.classList.remove('disabled')
        }
        if (parseInt(nbPage) == total) {
            next.parentNode.classList.add('disabled')
        } else {
            next.parentNode.classList.remove('disabled')
        }

        comments = document.querySelector('#comments')
        comments.innerHTML = data.content
    } else {
        console.error(response)
    }


}
