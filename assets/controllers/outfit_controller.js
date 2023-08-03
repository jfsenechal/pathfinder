import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    //static targets = ["name"]
    static values = {
        urlArmor: String,
        urlWeapon: String,
        type: String
    }

    async armor(event) {

        const element = event.currentTarget
        const id = element.dataset.id

        const response = await fetch(`${this.urlArmorValue}?id=${id}&type=${this.typeValue}`);

        const result = document.getElementById('result-' + id)
        result.innerHTML = await response.text();
    }

    async weapon(event) {

        const element = event.currentTarget
        const id = element.dataset.id

        const response = await fetch(`${this.urlWeaponValue}?id=${id}&type=${this.typeValue}`);

        const result = document.getElementById('result-' + id)
        result.innerHTML = await response.text();
    }
}
