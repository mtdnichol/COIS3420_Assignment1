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

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)){
    if ($data['taskName'] != NULL && $data['taskDesc'] != NULL){

        // run add task function
        addTask($_GET['addTask'], $data['taskName'], $data['taskDesc']);
        // response say completed
        response(200, "task ran", NULL);
    }
}