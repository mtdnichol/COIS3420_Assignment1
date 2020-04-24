<?php
$pdo = connectDB();

// Check if the session owns the given bucket list ID
function isOwner($id) {
    // Check if username in DB matches username in session
    $username = $_SESSION['username'];
    $query = "SELECT bucket_users.username FROM bucket_lists INNER JOIN bucket_users ON bucket_lists.fk_userid = bucket_users.id WHERE bucket_lists.id = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$id]);
    $results = $statement->fetch();
    return !empty($results) && $results['username'] == $username;
}

// Bucket list given by the ID is private
function isPrivate($id) {
    // Check if private field is 1 in the db
    $query = "SELECT private FROM bucket_lists WHERE id = ?";
    $statement = $GLOBALS['pdo']->prepare($query);
    $statement->execute([$id]);
    $results = $statement->fetch();
    return !empty($results) && $results['private'] === 1;
}
