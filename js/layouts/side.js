import { createHTMLElement } from "../tools.js";

let menu = document.querySelector("#menu");
let sideContainer = document.querySelector("#side-container");
let sideHeader = document.querySelector("#side-header");
let out = document.querySelector("#out");
let root = document.documentElement;

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
});

const links = document.querySelector("#side-body").querySelectorAll(".links");
const linksChild = document.querySelector("#side-body").querySelectorAll(".links-child");

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
        setTimeout(function() {
            parent.classList.remove("active-child");
        }, 300);
    });
});

export const onLinkClick = (links) => {
    links.forEach(item => {
        item.addEventListener("click", function (e) {
            if (item.querySelector(".links-child")) {
                undefined;
            }
            else {
                if (item.querySelector("a")) {
                    const a = item.querySelector("a");
                    const a_tab = a.getAttribute("href").split("/");
                    if (document.location.href.indexOf(a_tab[a_tab.length - 1]) !== -1) {
                        e.preventDefault();
                        createHTMLElement("warning", "Vous ètes déjà sur cette page.\nVoulez-vous l'actualiser de nouveau ?", ["Annuler", "Continuer"], document.location.href);
                    }
                }
            }
        });
    });
};

onLinkClick(links);
