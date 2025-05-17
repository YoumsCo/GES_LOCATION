import { createHTMLElement } from "../tools.js";

const iti_1 = window.intlTelInput(document.querySelector("#tel"), {
    loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/utils.js"),
    initialCountry: "cm",
});
const iti_2 = window.intlTelInput(document.querySelector("#whatsapp"), {
    loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/utils.js"),
    initialCountry: "cm",
});

document.querySelectorAll(".form-button")[0].addEventListener("click", function () {
    document.querySelectorAll(".numbers").forEach(input => {
        input.value = "";
    });
});

window.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        document.querySelectorAll(".form-button")[1].dispatchEvent(new MouseEvent("click", {
            bubbles: true,
            cancelable: false,
            view: window
        }));
    }
});

document.querySelector("#auth-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const parent = this.closest("form");

    if (!iti_1.isValidNumber() && document.querySelector("#whatsapp").value.trim() === "") {
        createHTMLElement("alert", "Veuillez respecter le format du numéro de téléphone en fonction de votre pays !!");
    }
    else if (!iti_1.isValidNumber() && document.querySelector("#whatsapp").value.trim() !== "" && iti_2.isValidNumber()) {
        createHTMLElement("alert", "Veuillez remplir les champs obligatoires !!");
    }
    else if (iti_1.isValidNumber() && document.querySelector("#whatsapp").value.trim() !== "" && !iti_2.isValidNumber()) {
        createHTMLElement("alert", "Veuillez respecter le format du numéro de téléphone en fonction de votre pays !!");
    }
    else {
        const xhr = new XMLHttpRequest();

        xhr.open("POST", parent.getAttribute("action"));
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = () => {
            const response = JSON.parse(xhr.responseText);
            if (response === "danger") {
                createHTMLElement("alert", "⚠️ Remplissez le formulaire vous-meme ⚠️");
                setTimeout(function() {
                    createHTMLElement("alert", "Astuce: Ne touchez pas aux suggestions du navigateur 🚫");
                    setTimeout(function() {
                        location.reload();
                    }, 6000);
                }, 6000);
            }
            else if (response === "admin") {
                createHTMLElement("alert", "❌ Vous êtes l'administrzateur) ❌");
            }
            else if (response === "preg_false") {
                createHTMLElement("alert", "❌ Numéro(s) de téléphone rejétté(s) ❌");
            }
            else if (response === "failure") {
                createHTMLElement("alert", "🥲 Une erreur est survenue 🥲");
            }
            else if (response === "success") {
                createHTMLElement("alert", "🎉 Félicitation vous etes désormais un proprietaire de logement chez nous 🎉");
                setTimeout(function() {
                    location.href = "./index.php";
                }, 3000);
            }
        }
        if (document.querySelector("#whatsapp").value.trim() !== "") {
            xhr.send(JSON.stringify({
                nom: new FormData(parent).get("nom"),
                email: new FormData(parent).get("email"),
                tel: iti_1.getNumber(),
                whatsapp: iti_2.getNumber(),
            }));
        }
        else {
            xhr.send(JSON.stringify({
                nom: new FormData(parent).get("nom"),
                email: new FormData(parent).get("email"),
                tel: iti_1.getNumber()
            }));
        }
    }
});
