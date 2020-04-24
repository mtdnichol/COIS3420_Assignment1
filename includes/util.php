<?php

function isOwner($id) {
    $pdo = connectDB();
    $username = $_SESSION['username'];
    $query = "SELECT bucket_users.username FROM bucket_lists INNER JOIN bucket_users ON bucket_lists.fk_userid = bucket_users.id WHERE bucket_lists.id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$id]);
    $results = $statement->fetch();
    return !empty($results) && $results['username'] == $username;
}

function isPrivate($id) {
    $pdo = connectDB();
    $query = "SELECT private FROM bucket_lists WHERE id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$id]);
    $results = $statement->fetch();
    return !empty($results) && $results['private'] === 1;
}
