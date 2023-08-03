import {Controller} from '@hotwired/stimulus';
import {useClickOutside, useDebounce} from 'stimulus-use';

export default class extends Controller {

    static targets = ["output"]
    static debounces = ['run']
    static values = {
        url: String,
        uuid: String
    }

    connect() {
        useClickOutside(this);
        useDebounce(this, {wait: 1000});
    }

    run(event) {

    }

    async up(event) {
        this.run(event)//for debounce, not work :-(
        const element = event.currentTarget
        console.log(element)
        if (element) {
            const id = element.dataset.id
            const points = element.value
            const response = await fetch(`${this.urlValue}?id=${id}&uuid=${this.uuidValue}&points=${points}`);

            this.outputTarget.textContent =
                `Hello, ${await response.text()}!`

            //const result = document.getElementById('result-' + id)
            //result.innerHTML =
        }

    }

}
