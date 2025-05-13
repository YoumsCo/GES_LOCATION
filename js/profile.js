import { createHTMLElement } from './tools.js';

document.querySelector("[type='file']").addEventListener("change", function () {
    const file = this.files[0];
    const accept = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
    if (accept.includes(file.type)) {
        if (file.size <= 1000000) {
            this.closest("form").querySelector("[type='submit']").dispatchEvent(new MouseEvent("click", {
                bubbles: true,
                cancelable: false,
                view: window
            }));
        }
        else {
            createHTMLElement("alert", "Fichier trop volumineux");
        }
    }
});