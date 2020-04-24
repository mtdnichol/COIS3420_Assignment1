<?php
require "./includes/library.php";
// Global var for connecting to db
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

    // Find title from $oldTitle, replace in db with $newTitle
    $query = "UPDATE bucket_lists SET title = ? WHERE title = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$newTitle, $oldTitle]);

    return true;
}

// description update function
function descUpdate($oldDesc, $newDesc){

    // Find desc from $oldDesc, replace in db with $newDesc
    $query = "UPDATE bucket_lists SET description = ? WHERE description = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$newDesc, $oldDesc]);

    return true;
}

// swap privacy of list
function privacySwap($listID){
    // Find list by list ID, inverse private field with NOT keyword
    $query = "UPDATE bucket_lists SET private = NOT private WHERE id=?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$listID]);

    return true;
}

//add task function
function addTask($listID, $taskName, $taskDesc){
    // Insert a bucket list entry into the DB via given info
    $query = "INSERT INTO bucket_entries(fk_listid, title, description) VALUES ('$listID', '$taskName', '$taskDesc')";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([]);

    return true;
}

// Delete an entry from the DB via a given entry ID
function deleteEntry($entryID) {
    $query = "DELETE FROM bucket_entries WHERE id = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$entryID]);

    return true;
}

// Edit a bucket list entry and replace its contents given by the params
function editTask($taskID, $taskName, $taskDesc){
    $query = "UPDATE bucket_entries SET title=?, description=? WHERE id = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$taskName, $taskDesc, $taskID]);

    return true;
}

// would be used to mark an entry complete but image upload is broken
function completeEntry($entryID, $data) {
    var_dump($_FILES);

    return true;
}