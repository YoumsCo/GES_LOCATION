import { checkAllowCoookies, checkAuthentication, cookieParent, createCookie, createHTMLElement, handleAllowCookie } from "./tools.js";

const scrollTopButton = document.querySelector("#scroll-top-button");
const scrollLastPositionButton = document.querySelector("#scroll-last-position-button");

const filterButtons = document.querySelectorAll(".filter-buttons");
let filterTab = [];

const houses = document.querySelectorAll(".habitat");

const scrollAdLeft = document.querySelectorAll(".scroll-buttons-left");
const scrollAdRight = document.querySelectorAll(".scroll-buttons-right");

const images = document.querySelectorAll(".img");

const desc = document.querySelectorAll(".description");

window.addEventListener("scroll", function () {
    if (window.scrollY >= 50) {
        document.querySelector("#header").classList.add("blur");
    }
    else {
        document.querySelector("#header").classList.remove("blur");
    }
});

window.addEventListener("load", function () {
    if (window.scrollY >= 50) {
        document.querySelector("#header").classList.add("blur");
    }
    else {
        document.querySelector("#header").classList.remove("blur");
    }
});


window.addEventListener("scroll", function () {
    window.scrollY >= 600 ? scrollTopButton.classList.add("show-button") : scrollTopButton.classList.remove("show-button");
});

scrollTopButton.addEventListener("click", function () {
    scrollLastPositionButton.classList.add("show-button");
    localStorage.setItem("position", window.scrollY);
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth"
    });
});

scrollLastPositionButton.addEventListener("click", function () {
    window.scrollTo({
        top: localStorage.getItem("position"),
        left: 0,
        behavior: "smooth"
    });
    setTimeout(function () {
        scrollLastPositionButton.classList.remove("show-button");
    }, 3000);
});


filterButtons.forEach(button => {
    button.addEventListener("click", function () {
        const removeClass = () => {
            filterButtons.forEach(child => {
                child.classList.contains("active-choise") ? child.classList.remove("active-choise") : undefined;
            });
            return true;
        }
        if (removeClass()) {
            localStorage.setItem("filter-value", this.textContent);
            this.classList.add("active-choise");
        }
    });
});


const hideHouse = () => {
    houses.forEach(house => {
        house.classList.contains("no-choised") ? undefined : house.classList.add("no-choised");
    });
};

export const toggleAds = (toggle) => {
    const pubs = document.querySelectorAll(".ads");
    if (toggle) {
        pubs.forEach(item => {
            item.classList.add("ads-hidden");
        });
    }
    else {
        pubs.forEach(item => {
            item.classList.remove("ads-hidden");
        });
    }
};

export const showCategory = (one = null, exports = false) => {
    const aprts = document.querySelectorAll(".habitat");
    const buttons = document.querySelectorAll(".filter-buttons");

    if(localStorage.getItem("ads-hidden")) {
        toggleAds(true);
    }

    if (one == null) {
        aprts.forEach(house => {
            house.classList.contains("no-choised") ? house.classList.remove("no-choised") : undefined;
        });
    }
    else {
        hideHouse();
        aprts.forEach((house) => {
            if (house.classList.contains(buttons[one].textContent)) {
                house.classList.remove("no-choised");

            }
            else {
                house.classList.add("no-choised");
            }
        });
    }
    if (exports === true) {
        if (localStorage.getItem("filter-value")) {
            filterButtons.forEach((button, index) => {
                if (button.textContent === localStorage.getItem("filter-value")) {
                    aprts.forEach((house) => {
                        if (house.classList.contains(buttons[index].textContent)) {
                            house.classList.remove("no-choised");
                        }
                        else {
                            house.classList.add("no-choised");
                        }
                    });
                }
                if (index == 0 && button.textContent === localStorage.getItem("filter-value")) {
                    showCategory();
                    toggleAds(false);
                }
            });
        }
        else {
            showCategory();
            toggleAds(false);
        }
    }
    if (localStorage.getItem("position")) {
        setTimeout(function () {
            window.scrollTo({
                top: localStorage.getItem("position"),
                behavior: "smooth"
            });
        }, 1000);
    }
    else {
        window.scrollTo({
            top: 450,
            behavior: "smooth"
        });
        setTimeout(function () {
            window.scrollTo({
                top: 150,
                behavior: "smooth"
            });
        }, 500);
    }
};


const removeClass = () => {
    filterButtons.forEach(child => {
        for (let i = 0; i < filterTab.length; i++) {
            if (localStorage.getItem("filter-value") !== null) {
                child.classList.contains("active-choise") ? child.classList.remove("active-choise") : undefined;
            }
        }
    });
    return true;
};

filterButtons.forEach((button, index) => {
    button.addEventListener("click", function () {
        if (index === 0) {
            showCategory();
            toggleAds(false);
            localStorage.removeItem("ads-hidden");
        }
        else {
            showCategory(index);
            localStorage.setItem("ads-hidden", true);
            toggleAds(true);
        }
        if (document.querySelector("#result")) {
            document.querySelector("#content").removeChild(document.querySelector("#result"));
        }
    });
    setTimeout(function () {
        localStorage.getItem("position") && window.scrollTo({
            top: localStorage.getItem("position"),
            behavior: "smooth"
        });
    }, 3000);
});

window.addEventListener("resize", function () {
    const ads = document.querySelectorAll(".ads");

    ads.forEach(ad => {
        const adsChild = ad.querySelector(".ads-img");
        adsChild.scrollTo({
            left: 0,
            behavior: "smooth"
        });
    });
});

const adsScroll = () => {
    const ads = document.querySelectorAll(".ads");

    ads.forEach(ad => {
        const adsChild = ad.querySelector(".ads-img");
        if (Math.floor(adsChild.scrollLeft) < Math.floor(adsChild.scrollWidth - adsChild.clientWidth) - 1) {
            adsChild.scrollTo({
                left: adsChild.scrollLeft + (adsChild.clientWidth / 2),
                behavior: "smooth"
            });
        }
        else {
            adsChild.scrollTo({
                left: 0,
                behavior: "smooth"
            });
        }
    });
};

const scrollInterval = () => {
    setInterval(function () {
        adsScroll();
    }, 2000);
}

scrollInterval();


scrollAdLeft.forEach(item => {
    item.addEventListener("click", function () {
        const ad = this.closest(".ads").querySelector(".ads-img");
        ad.scrollTo({
            left: ad.scrollLeft - (ad.clientWidth / 2),
            behavior: "smooth"
        });
    });
});

scrollAdRight.forEach(item => {
    item.addEventListener("click", function () {
        const ad = this.closest(".ads").querySelector(".ads-img");
        ad.scrollTo({
            left: ad.scrollLeft + (ad.clientWidth / 2),
            behavior: "smooth"
        });
    });
});

window.addEventListener("load", function () {
    filterButtons.forEach(child => {
        filterTab.push(child.textContent);
    });

    if (removeClass()) {
        filterButtons.forEach(child => {
            if (child.textContent === localStorage.getItem("filter-value")) {
                child.classList.add("active-choise");
            }
        });
    }

    if (localStorage.getItem("filter-value") === null) {
        showCategory();
    }
    else {
        toggleAds(false);
        if (localStorage.getItem("filter-value") === filterButtons[0].textContent) {
            showCategory();
        }
        else {
            filterButtons.forEach((button, index) => {
                if (button.textContent === localStorage.getItem("filter-value")) {
                    showCategory(index);
                }
            });
        }
    }

    setTimeout(function () {
        const loaders = document.querySelectorAll(".image-loader");
        loaders.forEach(loader => {
            loader.classList.add("loader-hidden");
        });
    }, 2500);

});

for (let i = 0; i < images.length; i++) {
    images[i].addEventListener("load", function () {
        const loader = images[i].nextElementSibling;
        loader.classList.add("loader-hidden");
    });
}


export default function spaceProfilePicture(url = null) {
    const spaceProfile = document.querySelector("#space-profile");
    if (url !== null && document.location.href.indexOf(url + ".php") !== -1) {
        spaceProfile.style.display = "none";
    }
}

spaceProfilePicture("index");


desc.forEach((child) => {
    const descText = child.querySelector(".text");
    if (window.matchMedia("(max-width: 700px)").matches) {
        if (descText.textContent.length > descText.clientHeight - 50) {
            const text = descText.textContent;
            const extractString = text.substr(0, 500);
            const newValue = extractString + "...";
            descText.textContent = newValue;
        }
    }
    else {
        if (descText.textContent.length > descText.clientHeight - 50) {
            const text = descText.textContent;
            localStorage.setItem("textValue", text);
            const extractString = text.substr(0, 1096);
            const newValue = extractString + "...";
            descText.textContent = newValue;
        }
    }
});

window.addEventListener("resize", function () {
    desc.forEach((child) => {
        const descText = child.querySelector(".text");
        if (window.matchMedia("(max-width: 700px)").matches) {
            if (descText.textContent.length > descText.clientHeight - 50) {
                const text = descText.textContent;
                const extractString = text.substr(0, 500);
                const newValue = extractString + "...";
                descText.textContent = newValue;
            }
        }
        else {
            if (localStorage.getItem("textValue")) {
                descText.textContent = localStorage.getItem("textValue");
                if (descText.textContent.length > descText.clientHeight - 50) {
                    const text = descText.textContent;
                    const extractString = text.substr(0, 1096);
                    const newValue = extractString + "...";
                    descText.textContent = newValue;
                }
            }
        }
    });
});

window.addEventListener("load", function () {
    checkAllowCoookies()
    checkAuthentication();
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

window.addEventListener("load", function() {
    if (sessionStorage.getItem("notification")) {
        const str = sessionStorage.getItem("notification").split(".");
        let string = "";
        str.forEach(element => {
            string += `${element}.\n`;
        });
        createHTMLElement("notification", string);
        setTimeout(function() {
            sessionStorage.removeItem("notification");
        }, 5000);
    }
});