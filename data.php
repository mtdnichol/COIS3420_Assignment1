<?php
require "./includes/library.php";

// title update function
function titleUpdate($oldTitle, $newTitle){
    $pdo = connectDB();

    // get catagories from db
    $query = "UPDATE bucket_lists SET title = ? WHERE title = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$newTitle, $oldTitle]);

    return true;
}

// swap privacy of list
function privacySwap($listID){
    console.log("Privacy Swap");
    $pdo = connectDB();

    // get catagories from db
    $query = "UPDATE bucket_lists SET private = NOT private WHERE id=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$listID]);

    return true;
}