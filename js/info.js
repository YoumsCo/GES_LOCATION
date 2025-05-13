import { checkAllowCoookies, checkAuthentication } from "./tools.js";

window.addEventListener("load", function () {
    checkAllowCoookies()
    checkAuthentication();
});
