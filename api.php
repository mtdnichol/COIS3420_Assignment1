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
} else {
    response(400, "Invalid Request", NULL);
}

// privacy swap button
if(!empty($_GET['privateSwap'])){
    $listID = $_GET['privateSwap'];

    if (privacySwap($listID))
        response(200, "Privacy Swapped", NULL);
} else {
    response(400, "Invalid Request", NULL);
}
