import { createHTMLElement } from './tools.js';

window.addEventListener("scroll", function () {
    const scrollTop = document.querySelector("#scroll-top");
    if (window.scrollY >= 30) {
        scrollTop.classList.add("show-button");
    }
    else {
        scrollTop.classList.remove("show-button");
    }
});

window.addEventListener("load", function () {
    const scrollTop = document.querySelector("#scroll-top");
    if (window.scrollY >= 30) {
        scrollTop.classList.add("show-button");
    }
    else {
        scrollTop.classList.remove("show-button");
    }

    if (localStorage.getItem("review-position")) {
        setTimeout(function () {
            window.scrollTo({
                top: localStorage.getItem("review-position"),
                left: 0,
                behavior: "smooth"
            });
        }, 2000);
    }
});

window.addEventListener("resize", function () {
    const scrollTop = document.querySelector("#scroll-top");
    const header = document.querySelector("#header");
    if (window.scrollY >= 30) {

        header.classList.add("blur");
        scrollTop.classList.add("show-button");
    }
    else {
        // header.classList.remove("blur");
        scrollTop.classList.remove("show-button");
    }
});

const scrollTop = document.querySelector("#scroll-top");
const scrollLastPosition = document.querySelector("#scroll-last-position");

scrollTop.addEventListener("click", function () {
    scrollLastPosition.classList.add("show-button");
    localStorage.setItem("review-position", window.scrollY);
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth"
    });
});

scrollLastPosition.addEventListener("click", function () {
    window.scrollTo({
        top: localStorage.getItem("review-position"),
        left: 0,
        behavior: "smooth"
    });
    setTimeout(function () {
        scrollLastPosition.classList.remove("show-button");
    }, 3000);
});

window.addEventListener("load", function() {
    if (localStorage.getItem("login")) {
        document.querySelector("#key").value = localStorage.getItem("login");
    }
});

const submitReviewButton = document.querySelector("#review-submit");

submitReviewButton.addEventListener("click", function(e) {
    e.preventDefault();
    const fieldComment = this.closest("form").querySelector("textarea").value.trim();
    const fieldRate = this.closest("form").querySelector("[type='number']").value.trim();
    
    if (localStorage.getItem("login")) {
        if (fieldComment === "" || fieldRate === "") {
            createHTMLElement("alert", "Vueillez remplir touts les champs.", null, null);
        }
        else {
            this.closest("form").submit();
        }
    }
    else {
        createHTMLElement("warning", "Vous n'etes pas connecté !!\nVous devez vous connecter pour poursuivre.\nVoulez-vous allez à la page de connexion ?", ["Non", "Oui"], "./connexion.php");
    }
});

window.addEventListener("load", function() {
    if (localStorage.getItem("login")) {
        document.querySelectorAll(".space-buttons").forEach(button => {
            button.style.display = "none";
        });
    }
});


const showRegisterForm = document.querySelectorAll(".space-buttons")[1];

showRegisterForm.addEventListener("click", function() {
    sessionStorage.setItem("register", "true");
});

const authenticationButtons = document.querySelectorAll(".space-buttons");

authenticationButtons.forEach(button => {
    button.addEventListener("click", function() {
        sessionStorage.setItem("referer", location.href);
    });
}); 