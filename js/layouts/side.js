import { createHTMLElement } from "../tools.js";

let menu = document.querySelector("#menu");
let sideContainer = document.querySelector("#side-container");
let sideHeader = document.querySelector("#side-header");
let out = document.querySelector("#out");
let root = document.documentElement;
const links = document.querySelector("#side-body").querySelectorAll(".links");
const linksChild = document.querySelector("#side-body").querySelectorAll(".links-child");

menu.addEventListener("click", function () {
    menu.classList.toggle("active-menu");

    if (menu.classList.contains("active-menu")) {
        sideContainer.focus();
        root.style.setProperty("--sidebar-width", "250px");
        window.matchMedia("(max-width: 800px)").matches ? root.style.setProperty("--menu-left", "210px") : root.style.setProperty("--menu-left", "190px");
        sideHeader.classList.remove("hide-side-header");
        document.body.style.overflow = "hidden";
    }
    else {
        root.style.setProperty("--sidebar-width", 0);
        root.style.setProperty("--menu-left", "10px");
        sideHeader.classList.add("hide-side-header");
        document.body.style.overflowX = "hidden";
        document.body.style.overflowY = "auto";
    }
});

window.addEventListener("resize", function () {
    if (menu.classList.contains("active-menu")) {
        sideContainer.focus();
        root.style.setProperty("--sidebar-width", "250px");
        window.matchMedia("(max-width: 800px)").matches ? root.style.setProperty("--menu-left", "210px") : root.style.setProperty("--menu-left", "190px");
        sideHeader.classList.remove("hide-side-header");
        document.body.style.overflow = "hidden";
    }
    else {
        root.style.setProperty("--sidebar-width", 0);
        root.style.setProperty("--menu-left", "10px");
        sideHeader.classList.add("hide-side-header");
        document.body.style.overflowX = "hidden";
        document.body.style.overflowY = "auto";
    }
});


out.addEventListener("click", function () {
    menu.classList.toggle("active-menu");
    root.style.setProperty("--sidebar-width", 0);
    root.style.setProperty("--menu-left", "10px");
    sideHeader.classList.add("hide-side-header");
    document.body.style.overflowX = "hidden";
    document.body.style.overflowY = "auto";
    links.forEach(item => {
        item.classList.remove("active-child");
    });
});


links.forEach(item => {
    item.addEventListener("click", function () {
        if (item.querySelector(".links-child")) {
            item.classList.toggle("active-child");
        }
    });
});

linksChild.forEach(item => {
    item.addEventListener("mouseleave", function () {
        const parent = item.closest(".links");
        setTimeout(function () {
            parent.classList.remove("active-child");
        }, 300);
    });
});

export const onLinkClick = (item, e) => {
    if (item.querySelector(".links-child")) {
        undefined;
    }
    else if (item.querySelector("a")) {
        const a = item.querySelector("a");
        const a_tab = a.getAttribute("href").split("/");
        const pattern = a_tab[a_tab.length - 2] + "/" + a_tab[a_tab.length - 1];
        if (document.location.href.indexOf(pattern) !== -1) {
            e.preventDefault();
            createHTMLElement("warning", "Vous ètes déjà sur cette page.\nVoulez-vous l'actualiser de nouveau ?", ["Annuler", "Continuer"], document.location.href);
        }
    }
};

links.forEach(item => {
    item.addEventListener("click", function (e) {
        onLinkClick(item, e);
    });
});

window.addEventListener("load", function () {
    if (!document.querySelector("#e_value").value.match(/^[a-zA-Z]+\d{0,5}[.]{0,2}[a-zA-Z]*\d{0,5}[\@](gmail|yahoo|outlook)(\.)(com|fr)$/)) {
        localStorage.removeItem("login");
        document.cookie = "login=null;expires=" + new Date(2024, 12, 12).toUTCString() + ";path=/";
    }
});

const deleteCookies = () => {
    const cookies = document.cookie.split(",");
    const cookieTab = cookies[0].split(";");

    for (let i = 0; i < cookieTab.length; i++) {
        let cookies = cookieTab[i].trim();
        let [cookieName, cookieValue] = cookies.split("=");
        document.cookie = `${cookieName}=${cookieValue};expires=${new Date(2024, 12, 31).toUTCString()};path=/`;
    }
    return true;
}

document.querySelector("#sign-out-icon").addEventListener("click", function () {
    if (deleteCookies()) {
        location.reload();
    }
});