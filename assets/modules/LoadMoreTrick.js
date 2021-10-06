export default class LoadMoreTrick {
    constructor(element) {
        if (element === null) {
            return;
        }
        this.loadMore = element.querySelector("#load_more");
        this.trick = element.querySelector("#tricks");
        this.trickCard = element.querySelector("#tricks_cards");
        this.card_trick = document.querySelectorAll("#card_trick");
        this.arrow_up = element.querySelector("#arrow_up");
        this.bindEvents();
    }

    bindEvents() {
        document.getElementById("arrow_up").style.visibility = "hidden";
        let nbTrick = this.trickCard.dataset.limit;
        let click = 0;
        this.loadMore.querySelectorAll("button").forEach((b) => {
            b.addEventListener("click", () => {
                document.getElementById('spinner').style.display = 'block';
                click++;
                let offset = nbTrick * click;
                this.loadUrl("/ajax/" + offset + "/" + nbTrick);
                if(offset >= nbTrick){
                    document.getElementById("arrow_up").style.visibility = "visible";
                }
            })
        })
    }

    async loadUrl(url) {
        const response = await fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();
            if (response) {
                this.hideSpinner();
            }
            if (data.content === false) {
                document.getElementById("spinner").style.display = "none";
                document.getElementById("load").style.display = "none";
            } else {
                this.trickCard.insertAdjacentHTML("beforeend", data.content);
            }
        } else {
            console.log(response);
        }
    }

    hideSpinner() {
        document.getElementById('spinner')
            .style.display = 'none';
    }
}