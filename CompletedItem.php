<?php
// start session and include db
session_start();
require "./includes/library.php";

// verify login
if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login");
    exit();
}

// connect to db and get current ID from passthrough
$pdo = connectDB();
$curID = $_GET['id'];

// retrieve bucket items with matching ID
$query = "SELECT * FROM `bucket_entries` WHERE id=?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$result = $statement->fetch();
?>
    <!-- This page displays data of a completed item -->
<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <a class="toButton" href="<?= "DisplayList?id=".$result['fk_listid'] ?>">Back to List</a> <!-- Link back to display list page -->

        <h2>Congratulations on your completed item!</h2>
        <h3>Completed on: <?= $result['dateCompleted'] ?></h3> <!-- All information from a completed item is displayed to the user -->
        <img src="<?= $result['photo'] ?>" alt="TestImage">
        <h3><?= $result['title'] ?></h3>
        <p><?= $result['description'] ?></p>
    </div>
<?php include "./includes/footer.php"; ?>