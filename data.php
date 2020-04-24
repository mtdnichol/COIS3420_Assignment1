<?php
require "./includes/library.php";
$pdo = connectDB();

// Return a JSON rsponse to the user
function response($status, $status_message, $data) {
    header("Content-Type:application/json");
    header("HTTP/1.1 ".$status);

    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;

    $json_response = json_encode($response);
    echo $json_response;
}

// title update function
function titleUpdate($oldTitle, $newTitle){

    // get catagories from db
    $query = "UPDATE bucket_lists SET title = ? WHERE title = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$newTitle, $oldTitle]);

    return true;
}

// description update function
function descUpdate($oldDesc, $newDesc){

    // get catagories from db
    $query = "UPDATE bucket_lists SET description = ? WHERE description = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$newDesc, $oldDesc]);

    return true;
}

// swap privacy of list
function privacySwap($listID){
    //get catagories from db
    $query = "UPDATE bucket_lists SET private = NOT private WHERE id=?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$listID]);

    return true;
}

//add task function
function addTask($listID, $taskName, $taskDesc){
    //get catagories from db
    $query = "INSERT INTO bucket_entries(fk_listid, title, description) VALUES ('$listID', '$taskName', '$taskDesc')";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([]);

    return true;
}

function deleteEntry($entryID) {
    $query = "DELETE FROM bucket_entries WHERE id = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$entryID]);

    return true;
}

function completeEntry($entryID, $data) {
    var_dump($_FILES);

    return true;
}