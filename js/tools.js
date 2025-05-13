export const createHTMLElement = (type, message = "OK", tabText = ["Anuuler", "Continuer"], link) => {
    switch (type) {
        case "warning":
            return handleWarning(message, tabText, link);
        case "alert":
            return handleAlert(message, link = null);
        case "box":
            return handleBox(message, tabText);
        case "notification":
            return notification(message);

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
            if (document.querySelector("#contain-box")) {
                document.querySelector("#container").removeChild(parent);
            }
        }, 3000);
    });

    lastChildButton.addEventListener("click", function () {
        document.location.href = link;
    });
};


const handleAlert = (message, link) => {
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
        if (link !== null) {
            location.href = link;
        }
        else if (!parent.classList.contains("alertHide")) {
            parent.classList.add("alertHide");
            setTimeout(function () {
                parent.classList.add("alertHidden");
            }, 1000);

            setTimeout(function () {
                if (document.querySelector("#alertBox")) {
                    document.querySelector("#container").removeChild(parent);
                }
            }, 2500);
        }
    });

    if (!parent.classList.contains("alertHide")) {
        setTimeout(function () {
            parent.classList.add("alertHide");
        }, 4000);
        setTimeout(function () {
            parent.classList.add("alertHidden");
        }, 5000);

        setTimeout(function () {
            if (document.querySelector("#alertBox")) {
                document.querySelector("#container").removeChild(parent);
            }
        }, 7000);
    }
};

const notification = (message) => {
    const parent = document.createElement("div");
    const content = document.createElement("p");
    const closeButton = document.createElement("i")

    parent.setAttribute("id", "notification");
    content.setAttribute("id", "content");
    closeButton.setAttribute("class", "fa fa-close");
    closeButton.setAttribute("id", "notif-closed");

    content.innerText = message;
    parent.appendChild(content);
    parent.appendChild(closeButton);
    document.querySelector("#container").appendChild(parent);

    closeButton.addEventListener("click", function () {
        parent.classList.add("box-hidden");
        setTimeout(() => {
            if (document.querySelector("#notification")) {
                document.querySelector("#container").removeChild(parent);
            }
        }, 2000);

    });

    setTimeout(() => {
        parent.classList.add("box-hidden");
        setTimeout(() => {
            if (document.querySelector("#notification")) {
                document.querySelector("#container").removeChild(parent);
            }
        }, 2000);
    }, 15000);
}

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
                if (document.querySelector("#messageBox")) {
                    document.querySelector("#container").removeChild(parent);
                }
            }, 2000);
        }
        else {
            throw new Error("Erreur lors de la création du localStorage");
        }
    });

    lastChildButton.addEventListener("click", function () {
        const cookie = document.cookie = cookieParent.name + "=" + cookieParent.value + ";expires=" + cookieParent.expires + ";path=/";
        cookie;
        if (document.cookie) {
            parent.classList.add("box-hidden");
            setTimeout(() => {
                if (document.querySelector("#messageBox")) {
                    document.querySelector("#container").removeChild(parent);
                }
            }, 2000);
        }
    });
};

export const handleAllowCookie = () => {
    let returnValue = false;
    if (document.cookie) {
        const cookiesList = document.cookie.split(";");

        for (let i = 0; i < cookiesList.length; i++) {
            const [cookieName, cookieValue] = cookiesList[i].split("=");
            if (cookieName.trim() === cookieParent.name && cookieValue.trim() === cookieParent.value) {
                // return cookieValue;
                returnValue = cookieValue;
            }
            else {
                returnValue = false;
            }
        }
    }
    else {
        returnValue = false;
    }

    return returnValue;
}

export const createCookie = (text = "Ce site utilise des cookies pour une meilleure gestion des données.\nPermettez-vous à ce site d'utiliser les cookies ?") => {
    setTimeout(function () {
        createHTMLElement("box", text, ["Refuser", "Accepter"]);
    }, 3000);
}

export const checkAllowCoookies = () => {
    if (handleAllowCookie() !== false) {
        console.log(handleAllowCookie());
    }
    else if (!handleAllowCookie() && !localStorage.getItem(cookieParent.name)) {
        // createCookie();
    }
}

export const checkAuthentication = () => {
    if (localStorage.getItem("login")) {
        const authenticationButtons = document.querySelectorAll(".space-buttons");

        authenticationButtons.forEach(button => {
            button.style.display = "none";
        });
    }
}