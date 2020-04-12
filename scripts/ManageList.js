"use strict";

//https://www.youtube.com/watch?v=gLWIYk0Sd38 Modal source
window.addEventListener('DOMContentLoaded', () => {
    const markCompleteButtons = document.querySelectorAll("button[name=markItem]");

    for (const button of markCompleteButtons) {
        button.addEventListener('click', event => { //Event listener for paragraph on click
            button.parentElement.parentElement.style.backgroundColor = "green";
        });
    }


});