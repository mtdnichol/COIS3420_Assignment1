"use strict";

function getConfirmation() {
    var retVal = confirm("Are you sure you want to delete your account?"); //Prompts the user to answer yes/no to delete their account
    if( retVal == true ) { //Returns true or false based on the answer they provide
        return true;
    } else {
        return false;
    }
}