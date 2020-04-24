<?php
require "data.php";

// Rename list route
if(!empty($_GET['newTitle']) && !empty($_GET['oldTitle'])){
    // store listID passed in with deleteList
    $newTitle=$_GET['newTitle'];
    $oldTitle=$_GET['oldTitle'];

    if (titleUpdate($oldTitle, $newTitle)){
        response(200, "Title Updated", NULL);
    }
}

// privacy swap button
if(!empty($_GET['privateSwap'])){
    $listID = $_GET['privateSwap'];

    if (privacySwap($listID)) {
        response(200, "Privacy Swapped", NULL);
    }
}

//description swap route

// Rename list route
if(!empty($_GET['newDesc']) && !empty($_GET['oldDesc'])){
    // store listID passed in with deleteList
    $newDesc=$_GET['newDesc'];
    $oldDesc=$_GET['oldDesc'];

    if (descUpdate($oldDesc, $newDesc)){
        response(200, "Description Updated", NULL);
    }
}

// Delete item route
if(!empty($_GET['deleteEntry'])){
    if (deleteEntry($_GET['deleteEntry'])){
        response(200, "Entry Deleted", NULL);
    }
}

if(!empty($_GET['completeEntry'])) {
    if(completeEntry($_GET['completeEntry'], $_POST)) response(200, "Entry Marked Complete", NULL);
}


