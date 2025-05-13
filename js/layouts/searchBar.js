import { showCategory, toggleAds } from "../home.js";

const search = document.querySelector("#searchBar");
const searchReset = document.querySelector("#reset-icon");
const searchButton = document.querySelector("#search-icon");

function checkIfSearchTrue() {
    const sections = document.querySelectorAll(".habitat");

    for (let i = 0; i < sections.length; i++) {
        if (sections[i].classList.contains('no-choised') == false) {
            return true;
        }
    };
    return false;
}

function Search(page) {
    if (document.location.href.indexOf(page) !== -1 && page === "index.php") {
        const searchValue = search.value.toLowerCase();
        const texts = document.querySelectorAll(".text");
        let result;

        texts.forEach(text => {
            if (text.textContent.toLowerCase().trim().indexOf(searchValue) !== -1) {
                const textParent = text.closest(".habitat");
                textParent.classList.contains("no-choised") && textParent.classList.remove("no-choised");
                result = true;
            }
            else {
                const textParent = text.closest(".habitat");
                textParent.classList.add("no-choised");
            }
        });
        document.querySelector("#reviews").style.display = "none";
        
        return result;
    }
};

function conditionList(e = null) {
    if (e !== null) {
        if (search.value.trim() !== "") {
            searchReset.classList.add("show");
            if (Search("index.php") == true) {
                if (document.querySelector("#result")) {
                    document.querySelector("#content").removeChild(document.querySelector("#result"));
                }
            }
        }
        if (e.key === "Backspace" && search.value.trim() === "") {
            searchReset.classList.remove("show");
        }
        else if (e.key === "Backspace" && search.value.trim() !== "") {
            if (Search("index.php") == true) {
                if (document.querySelector("#result")) {
                    document.querySelector("#content").removeChild(document.querySelector("#result"));
                }
            }
        }

        if (!checkIfSearchTrue()) {
            if (document.querySelector("#result")) {
                undefined;
            }
            else {
                toggleAds(true);
                const text = document.createElement("span");
                text.setAttribute("id", "result");
                text.innerText = "Aucun resultat 🥲";
                text.style.fontSize = "15pt";
                text.style.margin = "50px 0";
                document.querySelector("#content").appendChild(text);
            }
        }
    }
    else {
        if (search.value.trim() !== "") {
            searchReset.classList.add("show");
            if (Search("index.php") == true) {
                if (document.querySelector("#result")) {
                    document.querySelector("#content").removeChild(document.querySelector("#result"));
                }
            }
        }
        if (!checkIfSearchTrue()) {
            if (document.querySelector("#result")) {
                undefined;
            }
            else {
                toggleAds(true);
                const text = document.createElement("span");
                text.setAttribute("id", "result");
                text.innerText = "Aucun resultat 🥲";
                text.style.fontSize = "18pt";
                text.style.margin = "50px 0";
                document.querySelector("#content").appendChild(text);
            }
        }
    }
}

search.addEventListener("keyup", function (e) {
    conditionList(e);
});

searchReset.addEventListener("click", function () {
    const sections = document.querySelectorAll(".habitat");
    const buttons = document.querySelectorAll(".filter-buttons");
    
    document.querySelector("#reviews").style.display = "initial";
    search.value = "";
    searchReset.classList.remove("show");
    
    sections.forEach(section => {
        section.classList.contains("no-choised") && section.classList.remove("no-choised");
    });
    
    if (document.querySelector("#result")) {
        document.querySelector("#content").removeChild(document.querySelector("#result"));
    }
    showCategory(null, true);
    if (localStorage.getItem("filter-value") && localStorage.getItem("filter-value") === buttons[0].textContent) {
        toggleAds(false);
    }
});

searchButton.addEventListener("click", function () {
    conditionList();
});
