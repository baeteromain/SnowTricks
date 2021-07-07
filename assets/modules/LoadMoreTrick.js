export default class LoadMoreTrick {
    constructor(element) {
        if (element === null) {
            return
        }
        this.loadMore = element.querySelector('#load_more')
        this.trick = element.querySelector('#tricks')
        this.trickCard = element.querySelector('#tricks_cards')
        this.card_trick = document.querySelectorAll('#card_trick')
        console.log(this.card_trick.length)
        this.bindEvents()
    }

    bindEvents() {
        let nbTrick = 5
        let click = 0
        this.loadMore.querySelectorAll('button').forEach(b => {
            b.addEventListener('click', () => {
                click++
                let offset = nbTrick * click
                this.loadUrl('/ajax/' + offset )
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
            if(data.content === false){
                document.getElementById('spinner').style.display = "none"
                document.getElementById('load').style.display = "none"
            }else{
            this.trickCard.insertAdjacentHTML('beforeend', data.content)
            }
        } else {
            console.error(response)
        }
    }
}