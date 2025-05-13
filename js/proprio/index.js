if (window.matchMedia("(max-width: 750px)").matches) {
    const eyes = document.querySelectorAll(".eye");

    eyes.forEach(eye => {
        eye.addEventListener("click", function () {
            this.classList.contains("fa-eye") ? this.classList.replace("fa-eye", "fa-eye-slash") : this.classList.replace("fa-eye-slash", "fa-eye");
            const parent = this.closest(".logement");
            if (this.classList.contains("fa-eye")) {
                parent.querySelector(".desc-content").style.opacity = 0;
                parent.querySelector(".desc-icons").style.background = "initial";
                parent.querySelector(".location").style.opacity = 0;
                parent.querySelector(".montant").style.opacity = 0;
            }
            else {
                parent.querySelector(".desc-content").style.opacity = 1;
                parent.querySelector(".location").style.opacity = 1;
                parent.querySelector(".montant").style.opacity = 1;
                parent.querySelector(".desc-icons").style.background = "rgba(0, 0, 0, 0.669)";
            }
        });
    });
}
else {
    document.querySelectorAll(".location").forEach(child => {
        child.style.opacity = 1;
    });
    document.querySelectorAll(".montant").forEach(child => {
        child.style.opacity = 1;
    });
    document.querySelectorAll(".desc-content").forEach(item => {
        item.style.opacity = 1;
    });
    document.querySelectorAll(".desc-icons").forEach(child => {
        child.style.background = "transparent";
    });
}

window.addEventListener("resize", function () {
    if (window.matchMedia("(max-width: 750px)").matches) {
        document.querySelectorAll(".logement").forEach(child => {
            if (child.querySelector(".eye").classList.contains("fa-eye")) {
                child.querySelector(".desc-content").style.opacity = 0;
                child.querySelector(".desc-icons").style.background = "initial";
                child.querySelector(".location").style.opacity = 0;
                child.querySelector(".montant").style.opacity = 0;
            }
            else {
                child.querySelector(".desc-content").style.opacity = 1;
                child.querySelector(".location").style.opacity = 1;
                child.querySelector(".montant").style.opacity = 1;
                child.querySelector(".desc-icons").style.background = "rgba(0, 0, 0, 0.669)";
            }
        });
    }
    else {
        document.querySelectorAll(".location").forEach(child => {
            child.style.opacity = 1;
        });
        document.querySelectorAll(".montant").forEach(child => {
            child.style.opacity = 1;
        });
        document.querySelectorAll(".desc-content").forEach(item => {
            item.style.opacity = 1;
        });
        document.querySelectorAll(".desc-icons").forEach(child => {
            child.style.background = "transparent";
        });
    }

});

window.addEventListener("load", function () {
    const scrollButton = document.querySelector(".fa-chevron-circle-up");

    window.scrollY >= 80 ? scrollButton.classList.remove("scroll-icon-hide") : scrollButton.classList.add("scroll-icon-hide");
});

window.addEventListener("scroll", function () {
    const scrollButton = document.querySelector(".fa-chevron-circle-up");

    window.scrollY >= 80 ? scrollButton.classList.remove("scroll-icon-hide") : scrollButton.classList.add("scroll-icon-hide");
});

document.querySelector(".fa-chevron-circle-up").addEventListener("click", function () {
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth"
    });
});

document.querySelectorAll(".fa-chevron-circle-left").forEach(child => {
    child.addEventListener("click", function () {
        const parent = this.closest(".logement").querySelector(".images");
        parent.scrollTo({
            top: 0,
            left: parent.scrollLeft - parent.clientWidth,
            behavior: "smooth"
        });
    });
});

document.querySelectorAll(".fa-chevron-circle-right").forEach(child => {
    child.addEventListener("click", function () {
        const parent = this.closest(".logement").querySelector(".images");
        parent.scrollTo({
            top: 0,
            left: parent.scrollLeft + parent.clientWidth,
            behavior: "smooth"
        });
    });
});

window.addEventListener("load", function() {

    setTimeout(function() {
        document.querySelectorAll(".image-loader").forEach(loader => {
            loader.classList.add("end");
        });
    }, 2000);
});
