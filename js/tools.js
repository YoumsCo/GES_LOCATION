export const createHTMLElement = (type, message = "OK", tabText = ["Anuuler", "Continuer"], link) => {
    switch (type) {
        case "warning":
            return handleWarning(message, tabText, link);
        case "alert":
            return handleAlert(message);
        case "box":
            return handleBox(message, tabText);

        default: undefined;
    }
};

const handleWarning = (message, buttonText, link) => {
    const parent = document.createElement("div");
    const childParent = document.createElement("div");
    const firstChild = document.createElement("p");
    const lastChild = document.createElement("div");
    const firstChildButton = document.createElement("button");
    const lastChildButton = document.createElement("button");

    parent.setAttribute("id", "contain-box");
    childParent.setAttribute("id", "box");
    firstChild.setAttribute("id", "box-message");
    lastChild.setAttribute("id", "contain-box-buttons");
    firstChildButton.setAttribute("class", "button-box");
    lastChildButton.setAttribute("class", "button-box");

    firstChildButton.innerText = buttonText[0];
    lastChildButton.innerText = buttonText[1];
    firstChild.innerText = message;
    lastChild.appendChild(firstChildButton);
    lastChild.appendChild(lastChildButton);
    childParent.appendChild(firstChild);
    childParent.appendChild(lastChild);
    parent.appendChild(childParent);
    document.querySelector("#container").appendChild(parent);
    firstChildButton.addEventListener("click", function () {
        parent.classList.add("box-hidden");
        setTimeout(() => {
            document.querySelector("#container").removeChild(parent);
        }, 3000);
    });

    lastChildButton.addEventListener("click", function () {
        document.location.href = link;
    });
};


const handleAlert = (message) => {
    const parent = document.createElement("div");
    const firstChild = document.createElement("p");
    const lastChild = document.createElement("i");

    parent.setAttribute("id", "alertBox");
    firstChild.setAttribute("id", "alertBox-text");
    lastChild.setAttribute("class", "fa fa-close");
    lastChild.setAttribute("id", "alert-close");

    firstChild.innerText = message;
    parent.appendChild(firstChild);
    parent.appendChild(lastChild);
    document.querySelector("#container").appendChild(parent);

    lastChild.addEventListener("click", function () {
        parent.classList.add("alertHide");
        setTimeout(function () {
            parent.classList.add("alertHidden");
        }, 1000);
        setTimeout(function () {
            document.querySelector("#container").removeChild(parent);
        }, 2000);
    });
    
    setTimeout(function () {
        parent.classList.add("alertHide");
    }, 5000);
    
    setTimeout(function () {
        parent.classList.add("alertHidden");
    }, 6000);

    setTimeout(function () {
        document.querySelector("#container").removeChild(parent);
    }, 7000);
};

export const cookieParent = {
    "name": "cookiesAllowed",
    "value": "allowed",
    "expires": new Date("3000-12-31").toUTCString(),
    "not": "notAllowed",
};

const handleBox = (message, buttonText) => {
    const parent = document.createElement("div");
    const firstChild = document.createElement("p");
    const lastChild = document.createElement("div");
    const firstChildButton = document.createElement("button");
    const lastChildButton = document.createElement("button");

    parent.setAttribute("id", "messageBox");
    firstChild.setAttribute("id", "messageBox-text");
    lastChild.setAttribute("id", "messageBox-buttons");
    firstChildButton.setAttribute("class", "box-buttons");
    lastChildButton.setAttribute("class", "box-buttons");

    firstChildButton.innerText = buttonText[0];
    lastChildButton.innerText = buttonText[1];
    firstChild.innerText = message;
    lastChild.appendChild(firstChildButton);
    lastChild.appendChild(lastChildButton);
    parent.appendChild(firstChild);
    parent.appendChild(lastChild);
    document.querySelector("#container").appendChild(parent);

    firstChildButton.addEventListener("click", function () {
        localStorage.setItem(cookieParent.name, cookieParent.not);
        if (localStorage.getItem(cookieParent.name)) {
            parent.classList.add("box-hidden");
            setTimeout(() => {
                document.querySelector("#container").removeChild(parent);
            }, 2000);
        }
        else {
            throw new Error("Erreur lors de la création du localStorage");
        }
    });

    lastChildButton.addEventListener("click", function () {
        const cookie = document.cookie = cookieParent.name + "=" + cookieParent.value + ";expires=" + cookieParent.expires;
        cookie;
        if (document.cookie) {
            parent.classList.add("box-hidden");
            setTimeout(() => {
                document.querySelector("#container").removeChild(parent);
            }, 2000);
        }
    });
};

export const handleAllowCookie = () => {
    if (document.cookie) {
        const cookiesList = document.cookie.split(";");

        for (let i = 0; i < cookiesList.length; i++) {
            const [cookieName, cookieValue] = cookiesList[i].split("=");
            if (cookieName.trim() === cookieParent.name && cookieValue.trim() === cookieParent.value) {
                return cookieValue;
            }
        }
    }
    else {
        return false;
    }
}

export const createCookie = (text = "Ce site utilise des cookies pour une meilleure gestion des données.\nPermettez-vous à ce site d'utiliser les cookies ?") => {
    setTimeout(function () {
        createHTMLElement("box", text, ["Refuser", "Accepter"]);
    }, 3000);
}