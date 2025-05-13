import { cookieParent, createCookie, handleAllowCookie } from "./tools.js";

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


const containScrollLeft = document.querySelectorAll(".contain-scroll")[0];
const containScrollRight = document.querySelectorAll(".contain-scroll")[1];

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
    if (handleAllowCookie() !== false) {
        alert(handleAllowCookie());
    }
    else if (!handleAllowCookie() && !localStorage.getItem(cookieParent.name)) {
        createCookie();
    }
});