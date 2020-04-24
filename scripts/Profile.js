// file used strictly for the profile button
// on click will redirect them to their profile url based on current session id
// also ability to delete account, so js confirmation is found here
"use strict";

// delete account confirmation
function getConfirmation() {
    var retVal = confirm("Are you sure you want to delete your account?"); //Prompts the user to answer yes/no to delete their account
    if( retVal == true ) { //Returns true or false based on the answer they provide
        return true;
    } else {
        return false;
    }
}

function getLink(listID) {
    let dir = window.location.href.substring(0, window.location.href.lastIndexOf('/'));
    dir + "/DisplayList?id=1"
}