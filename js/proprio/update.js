import { createHTMLElement } from "../tools.js";

document.querySelectorAll(".form-button")[0].addEventListener("click", function () {
    document.querySelectorAll("input").forEach(input => {
        input.value = "";
    });
    document.querySelector("textarea").value = "";
});

// const handleSubmit = (elements) => {
//     let value = false;
//     elements.forEach((element) => {
//         if (element.value.trim() === "") {
//             value = true;
//         }
//     });

//     return value;
// }

// const button = document.querySelector("[type='submit']");

// button.addEventListener("click", function (e) {
//     e.preventDefault();
//     const parent = this.closest("form");

//     if (handleSubmit(document.querySelectorAll(".input-require")) || document.querySelector("textarea").value.trim() === "") {
//         createHTMLElement("alert", "Veuillez remplir tous les champs obligatoires !!", null, null);
//     }
// });