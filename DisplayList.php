<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

// Current List ID
$curID = $_GET['id'];


/* Connect to DB */
$pdo = connectDB();

$query = "SELECT * FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$userLists = $statement->fetchAll();

$query = "SELECT title FROM `bucket_lists` WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$title = $statement->fetch();

$query = "SELECT id, title, photo, description FROM `bucket_entries` WHERE fk_listid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$results = $statement->fetchAll();
?>

<!--html starts-->
<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <h1><?php echo $_SESSION['username']?>'s Bucket List</h1>
        <h2><?php echo $title['title'] ?></h2>
        <div class="bucket-list-nav">
            <a href="<?php echo "ManageList.php?id=".$_GET['id']?>" class="right"><i class="fas fa-tasks"></i> Manage</a>
        </div>

        <?php foreach ($results as $result): ?>
            <div class ="item">
                <img src="<?= $result['photo'] ?>" alt="TestImage">
                <div class="bucket-content">
                    <h3><?= $result['title'] ?></h3>
                    <p><?= $result['description'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php include "./includes/footer.php"; ?>