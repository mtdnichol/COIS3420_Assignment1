//File used to deal with logout button, clears session and moves them to logout page

"use strict";

window.addEventListener('DOMContentLoaded', () => {
    const logoutLink = document.querySelector("#logout");

    logoutLink.addEventListener('click', event => {
        sessionStorage.clear();
    });
});