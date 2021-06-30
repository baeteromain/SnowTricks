export default class LoadMoreTrick {
    constructor(element) {
        if (element === null) {
            return
        }
        this.loadMore = element.querySelector('#load_more')
        this.trick = element.querySelector('#tricks')
        this.trickCard = element.querySelector('#tricks_cards')
        this.bindEvents()
    }

    bindEvents() {
        this.loadMore.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault()
                this.loadUrl(a.getAttribute('href') )
            })
        })
    }

    async loadUrl(url) {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With' : 'XMLHttpRequest'
            }
        })
        if(response.status >= 200 && response.status < 300){
            const data = await response.json()
            this.trickCard.insertAdjacentHTML('beforeend', data.content)
        } else {
            console.error(response)
        }
    }
}