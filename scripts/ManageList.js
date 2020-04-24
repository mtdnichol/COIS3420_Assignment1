"use strict";

//https://www.youtube.com/watch?v=gLWIYk0Sd38 Modal source
window.addEventListener('DOMContentLoaded', () => {
    const markCompleteButtons = document.querySelectorAll("button[name=markItem]");
    const deleteItemButtons = document.querySelectorAll("button[name=deleteItem]")

    for (const button of markCompleteButtons) {
        button.addEventListener('click', event => { //Event listener for paragraph on click
            button.parentElement.parentElement.style.backgroundColor = "#01D27F";
        });
    }

    for (const button of deleteItemButtons) {
        button.addEventListener('click', event => { //Event listener for paragraph on click
            button.parentElement.parentElement.classList.add("hidden");
        });
    }
});

// *****
// EDIT LIST FUNCTION

// ran on click of edit title button
function titleSwap(){
    // swap to text input, allowing them to input new title
    document.querySelector(".titleHeader").classList.toggle("hidden");
    document.querySelector(".titleEdit").classList.toggle("hidden");

    let prevTitle = document.querySelector(".titleHeader h2").textContent;

    // set previous title as placeholder of current input
    document.querySelector(".titleEdit input").placeholder = prevTitle;

    return false;
}

// on submit button for title swap
document.querySelector("#titleSubmit").addEventListener("click", function(){
    // grab text from input
    console.log(document.querySelector(".titleEdit input").value);
    let newTitle = document.querySelector(".titleEdit input").value;
    let oldTitle = document.querySelector(".titleHeader h2").textContent;

    // update with database -- ajax call to api
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "api.php?newTitle="+newTitle+"&oldTitle="+oldTitle, false);
    xhttp.send();

    // update title in page
    document.querySelector(".titleHeader h2").textContent = newTitle;

    // swap back headers
    document.querySelector(".titleEdit").classList.toggle("hidden");
    document.querySelector(".titleHeader").classList.toggle("hidden");
});

// *****
// PRIVACY SWAP FUNCTION
function privacySwap(listID){
    // swap to lock buttons
    document.querySelector("#privatize ").classList.toggle("hidden");
    document.querySelector("#privatize-lock").classList.toggle("hidden");

    var url = "api.php?privateSwap="+listID;

    fetch(url)
        .then(function(data){
            // do response
            console.log(data.text().then(text => console.log(text)));
        })
        .catch(function(error){
            console.log(error);
        });
    
    return false;
}


function confirmation(){
    var result = confirm("Want to Delete List?");
    if (!result){
        return false;
    }
}

// description swap
document.querySelector("#bucketDescription").addEventListener("click", function(){
    document.querySelector(".bucketDesc").classList.toggle("hidden");
    document.querySelector(".bucketEdit").classList.toggle("hidden");

    let prevDesc = document.querySelector(".bucketDesc p").textContent;

});

// on submit button for description swap
document.querySelector("#descSubmit").addEventListener("click", function(){
    // grab text from input
    console.log(document.querySelector(".bucketEdit input").value);
    let newDesc = document.querySelector(".bucketEdit input").value;
    let oldDesc = document.querySelector(".bucketDesc p").textContent;

    // update with database -- ajax call to api
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "api.php?newDesc="+newDesc+"&oldDesc="+oldDesc, false);
    xhttp.send();

    // update title in page
    document.querySelector(".bucketDesc p").textContent = newDesc;

    // swap back headers
    document.querySelector(".bucketDesc").classList.toggle("hidden");
    document.querySelector(".bucketEdit").classList.toggle("hidden");
});