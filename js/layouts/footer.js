import { onLinkClick } from "./side.js";

const footerLinks = document.querySelector("#footer-links").querySelectorAll(".links");

footerLinks.forEach(link => {
    link.addEventListener("click", function(e) {
        onLinkClick(link, e);
    });
});