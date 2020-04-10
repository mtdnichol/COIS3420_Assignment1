"use strict";

window.addEventListener('DOMContentLoaded', () => {
    const logoutLink = document.querySelector("#logout");

    logoutLink.addEventListener('click', event => {
        sessionStorage.clear();
    });
});