"use strict";
//https://www.youtube.com/watch?v=gLWIYk0Sd38 Modal source
window.addEventListener('DOMContentLoaded', () => {
    const addItemButton = document.querySelector('#addItem');
    const editListButton = document.querySelector('#editList')
    const close = document.querySelector('.close');

    addItemButton.addEventListener('click', event => {
        console.log("Pears");
        document.querySelector('#addItemModal').style.display = "flex";
    });

    editListButton.addEventListener('click', event => {
        console.log("Pineapples");
        document.querySelector('#editListModal').style.display = "flex";
    });



    close.addEventListener('click', event => {
        close.parentElement.parentElement.style.display = "none";
    });

});