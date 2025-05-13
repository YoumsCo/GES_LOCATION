import { createHTMLElement } from './tools.js';

const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');

registerLink.onclick = () => {
    wrapper.classList.add('active');
}

loginLink.onclick = () => {
    wrapper.classList.remove('active');
}

/**
 * ********************************************************************************************
 * Gestion des ecrans inférieurs ou égaux à 710px
 * ********************************************************************************************
 */

window.addEventListener("load", function () {
    if (window.innerWidth <= 710) {
        document.querySelectorAll(".form-box")[0].classList.contains("active-form") ? document.querySelectorAll(".form-box")[1].classList.add("form-display-none") : document.querySelectorAll(".form-box")[1].classList.remove("form-display-none");
        document.querySelector("#register-btn").addEventListener("click", function () {
            document.querySelectorAll(".form-box")[0].classList.remove("active-form");
            document.querySelectorAll(".form-box")[1].classList.add("active-form");
            document.querySelectorAll(".form-box")[1].classList.remove("form-display-none");
        });
        document.querySelector("#login-btn").addEventListener("click", function () {
            document.querySelectorAll(".form-box")[0].classList.add("active-form");
            document.querySelectorAll(".form-box")[1].classList.remove("active-form");
            document.querySelectorAll(".form-box")[1].classList.add("form-display-none");
        });
    }
});

window.addEventListener("resize", function () {
    if (window.innerWidth <= 710) {
        wrapper.classList.contains("active") && wrapper.classList.remove("active");
        document.querySelectorAll(".form-box")[0].classList.contains("active-form") ? document.querySelectorAll(".form-box")[1].classList.add("form-display-none") : document.querySelectorAll(".form-box")[1].classList.remove("form-display-none");
        document.querySelector("#register-btn").addEventListener("click", function () {
            document.querySelectorAll(".form-box")[0].classList.remove("active-form");
            document.querySelectorAll(".form-box")[1].classList.add("active-form");
            document.querySelectorAll(".form-box")[1].classList.remove("form-display-none");
        });
        document.querySelector("#login-btn").addEventListener("click", function () {
            document.querySelectorAll(".form-box")[0].classList.add("active-form");
            document.querySelectorAll(".form-box")[1].classList.remove("active-form");
            document.querySelectorAll(".form-box")[1].classList.add("form-display-none");
        });
    }
    else {
        document.querySelectorAll(".form-box")[1].classList.remove("form-display-none");
    }
});

/**
 * ********************************************************************************************
 * Gestion des icones de mot de passe
 * ********************************************************************************************
 */

const Cadenas1 = document.querySelectorAll(".cadenas")[0];
const Cadenas2 = document.querySelectorAll(".cadenas")[1];
const password1 = document.querySelectorAll("[type='password']")[0];
const password2 = document.querySelectorAll("[type='password']")[1];

Cadenas1.addEventListener("click", function () {
    if (password1.type === "password") {
        password1.type = "text";
        Cadenas1.classList.replace("bxs-lock-alt", "bxs-lock-open");
    } else {
        password1.type = "password";
        Cadenas1.classList.replace("bxs-lock-open", "bxs-lock-alt");
    }
});

Cadenas2.addEventListener("click", function () {
    if (password2.type === "password") {
        password2.type = "text";
        Cadenas2.classList.replace("bxs-lock-alt", "bxs-lock-open");
    } else {
        password2.type = "password";
        Cadenas2.classList.replace("bxs-lock-open", "bxs-lock-alt");
    }
});

window.addEventListener("wheel", function (e) {
    if (e.ctrlKey) {
        e.preventDefault();
    }

}, { passive: false });

window.addEventListener("load", function () {
    if (sessionStorage.getItem("register") && sessionStorage.getItem("register") === "true") {
        document.querySelector("#register-btn").dispatchEvent(new MouseEvent("click", {
            bubbles: true,
            cancelable: false,
            view: window
        }));
        setTimeout(function() {
            sessionStorage.setItem("register", false);
        }, 5000);
    }
});
