import { checkAllowCoookies, checkAuthentication, createHTMLElement } from "./tools.js";

const containScrollLeft = document.querySelectorAll(".contain-scroll")[0];
const containScrollRight = document.querySelectorAll(".contain-scroll")[1];
const reservationForm = document.querySelector("#contain-all");
const buyButton = document.querySelector("#buy-button");
const resetReserve = document.querySelector("#reset");
const reserve = document.querySelector("#reserve");

window.addEventListener("resize", function () {
    const containImg = document.querySelector("#contain-img");
    containImg.scrollTo({
        left: 0,
        behavior: "smooth"
    });
});

const scrollFunction = () => {
    const containImg = document.querySelector("#contain-img");
    if (containImg.scrollLeft < (containImg.clientWidth * containImg.childElementCount - containImg.clientWidth)) {
        containImg.scrollTo({
            left: containImg.scrollLeft + containImg.clientWidth,
            behavior: "smooth"
        });
    }
    else {
        containImg.scrollTo({
            left: 0,
            behavior: "smooth"
        });
    }
}

const interval = () => {
    setInterval(() => {
        scrollFunction();
    }, 2000);
};


window.addEventListener("load", function () {
    interval();
});



containScrollLeft.addEventListener("click", function () {
    const containImg = document.querySelector("#contain-img");
    containImg.scrollTo({
        left: containImg.scrollLeft - containImg.clientWidth,
        behavior: "smooth"
    });
});

containScrollRight.addEventListener("click", function () {
    const containImg = document.querySelector("#contain-img");
    containImg.scrollTo({
        left: containImg.scrollLeft + containImg.clientWidth,
        behavior: "smooth"
    });
});


window.addEventListener("load", function () {
    checkAllowCoookies()
    checkAuthentication();
});

buyButton.addEventListener("click", function () {
    if (localStorage.getItem("login")) {
        reservationForm.classList.remove("contain-hide");
    }
    else {
        createHTMLElement("warning", "Vous n'etes pas connecté !!\nVous devez etre connecté pour poursuivre cette opération.\nVoulez-vous allez sur la page de connexion ?", ["Refuser", "Aller"], "./connexion.php");
    }
});

resetReserve.addEventListener("click", function () {
    reservationForm.classList.add("contain-hide");
});

// window.addEventListener("load", function () {
//     reservationForm.classList.remove("contain-hide");
// });

const checkValidity = (list) => {
    for (let i = 0; i < list.length; i++) {
        if (list[i].hasAttribute("required")) {
            if (list[i].value.trim() === "") {
                return false;
            }
        }
    }

    return true;
}

flatpickr("#begin", {
    dateFormat: "d-m-Y",
    altInput: true,
    altFormat: "F j, Y",
    minDate: "today",
    maxDate: new Date().fp_incr(186),
    weekNumbers: true
});

flatpickr("#deadline", {
    dateFormat: "d-m-Y",
    altInput: true,
    altFormat: "F j, Y",
    minDate: "today",
    weekNumbers: true
});

reserve.addEventListener("click", function (e) {
    e.preventDefault();
    const parent = this.closest("form");
    if (checkValidity(parent.querySelectorAll("input"))) {
        if (parent.querySelector("#deadline").value.trim() !== "") {
            const beginValue = parent.querySelector("#begin").value;
            const deadlineValue = parent.querySelector("#deadline").value;
            const [beginDate, beginMonth, beginYear] = beginValue.split("-");
            const [deadDate, deadMonth, deadYear] = deadlineValue.split("-");

            if (beginYear === deadYear && beginMonth === deadMonth && beginDate > deadDate) {
                createHTMLElement("alert", "Veuillez entrer une date de fin valide !!", [], null);
            }
            else if (beginYear === deadYear && beginMonth > deadMonth) {
                createHTMLElement("alert", "Veuillez entrer une date de fin valide !!", [], null);
            }
            else {
                parent.submit();
            }
        }
        else {
            parent.submit();
        }
        parent.submit();
    }
    else {
        createHTMLElement("alert", "Veuillez remplir tous les champs vides", [], null);
    }
});

window.addEventListener("wheel", function (e) {
    if (e.ctrlKey) {
        e.preventDefault();
    }

}, { passive: false });

const showRegisterForm = document.querySelectorAll(".space-buttons")[1];

showRegisterForm.addEventListener("click", function () {
    sessionStorage.setItem("register", "true");
});


const authenticationButtons = document.querySelectorAll(".space-buttons");

authenticationButtons.forEach(button => {
    button.addEventListener("click", function () {
        sessionStorage.setItem("referer", location.href);
    });
});

window.addEventListener("load", function () {
    const whatsapp = document.querySelector("#whatsapp");
    const [charLeft, charRight] = whatsapp.getAttribute("href").split("?");

    const hour = new Date().getHours();

    if (hour < 0 || hour > 15) {
        if (whatsapp.getAttribute("href").indexOf("Bonjour") !== -1) {
            const str = charRight.replace(charRight.substr(charRight.indexOf("Bonjour"), 7), "Bonsoir");
            whatsapp.setAttribute("href", charLeft + "?" + str);
        }
    }
});

const contacts = document.querySelectorAll(".contact");

contacts.forEach(contact => {
    contact.addEventListener("click", function (e) {
        e.preventDefault();
        const link = this.getAttribute("href");
        if (localStorage.getItem("login")) {
            window.open(link, "_blank");
        }
        else {
            createHTMLElement("warning", "Vous n'etes pas connecté !!\nConnectez vous pour pouvoir y accéder.", ["Refuser", "Poursuivre"], "./connexion.php");
        }
    });
});
