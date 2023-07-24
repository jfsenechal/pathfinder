import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    //static targets = ["name"]
    static values = {url: String, type: String}

    async greet(event) {

        const element = event.currentTarget
        const id = element.dataset.id

        const response = await fetch(`${this.urlValue}?id=${id}&type=${this.typeValue}`);

        const result = document.getElementById('result-' + id)
        result.innerHTML = await response.text();
    }
}
