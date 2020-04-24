"use strict";

let uppy = Uppy.Core({
    autoProceed: false,
    restrictions: {
        maxFileSize: 1000000,
        maxNumberOfFiles: 1,
        minNumberOfFiles: 1,
        allowedFileTypes: ['image/*']
    }
})
    .use(Uppy.Dashboard, {
        autoProceed: false,
        height: 470,
        target: '#markItemModal',
        trigger: '#openUppy',
        replaceTargetContent: false,
        showProgressDetails: true,
        note: 'Max 1 Image',
        hideUploadButton: true
    })
    .use(Uppy.GoogleDrive, {target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
    .use(Uppy.Dropbox, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
    .use(Uppy.Instagram, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
    .use(Uppy.Facebook, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
    .use(Uppy.OneDrive, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
    .use(Uppy.XHRUpload, {
        endpoint: 'UploadImage.php',
        formData: true,
        fieldName: 'files[]'
    })
    .use(Uppy.Webcam, { target: Uppy.Dashboard });

// *****
// MARK ITEM COMPLETE FUNCTION
function markItemComplete(itemID) {
    let date = document.querySelector(".item[data-item-id=\"" + itemID + "\"] #completedDate").value;
    uppy.upload().then(result => {
        console.log(result);
    });
}

function resetUppy() {
    uppy.reset();
}

// *****
// DELETE ITEM FUNCTION
function deleteItem(itemID) {
    // delete on backend
    let url = "api.php?deleteEntry=" + itemID;

    fetch(url)
        .then(function (data) {
            // hide on front end
            let item = document.querySelector(".item[data-item-id=\"" + itemID + "\"]");
            item.style.display = "none";
        })
        .catch(function (error) {
            console.log(error);
        });

    return true;
}

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
    let xhttp = new XMLHttpRequest();
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

    let url = "api.php?privateSwap="+listID;

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
    let result = confirm("Want to Delete List?");
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
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "api.php?newDesc="+newDesc+"&oldDesc="+oldDesc, false);
    xhttp.send();

    // update title in page
    document.querySelector(".bucketDesc p").textContent = newDesc;

    // swap back headers
    document.querySelector(".bucketDesc").classList.toggle("hidden");
    document.querySelector(".bucketEdit").classList.toggle("hidden");
});

// add bucket list item
document.querySelector("#addTaskSubmit").addEventListener("click", function(){

});

function addTask(listID){
    let failed = false;
    console.log(listID);
    // get new task name
    let taskName = document.querySelector("#nameEdit").value;
    if (taskName == "") {
        document.querySelector("#nameEdit").classList.toggle("wrongSelection");
        failed = true;
    }
    // get description
    let taskDesc = document.querySelector("#descEdit").value;
    if (taskDesc == "") {
        document.querySelector("#descEdit").classList.toggle("wrongSelection");
        failed = true;
    }

    if (!failed){
        var url = "api.php?addTask="+listID;

        fetch(url, {
            method: 'post',
            body: JSON.stringify({
                taskName: taskName,
                taskDesc: taskDesc,
                addTask: true
            })
        })
            .then(function(data){
                console.log(data.text().then(text => console.log(text)));
            })
            .catch(function(error){
                console.log(error);
            });

        // redirect -- temporary
        window.location.href="ManageList.php?id="+listID;
    }
}

function luckyAdd(listID){
    // parse db for all entries

    // random number generate between 0 & max

    // select one and add it to this db table

    // display for user
}

function editTask(listID, taskID){
    let failed = false;
    console.log(listID);
    // get new task name
    let taskName = document.querySelector("#nameModify").value;
    console.log(taskName);
    if (taskName == "") {
        document.querySelector("#nameEdit").classList.toggle("wrongSelection");
        failed = true;
    }
    // get description
    let taskDesc = document.querySelector("#descModify").value;
    console.log(taskDesc);
    if (taskDesc == "") {
        document.querySelector("#descEdit").classList.toggle("wrongSelection");
        failed = true;
    }

    console.log(failed);
    if (!failed){
        var url = "api.php?editTask="+listID;

        fetch(url, {
            method: 'post',
            body: JSON.stringify({
                taskID: taskID,
                taskName: taskName,
                taskDesc: taskDesc,
                editTask: true
            })
        })
            .then(function(data){
                console.log(data.text().then(text => console.log(text)));
            })
            .catch(function(error){
                console.log(error);
            });

        // redirect -- temporary
        window.location.href="ManageList.php?id="+listID;
    }

}
