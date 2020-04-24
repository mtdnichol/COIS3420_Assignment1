<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login");
    exit();
}

$pdo = connectDB();
$curID = $_GET['id'];

$query = "SELECT * FROM `bucket_entries` WHERE id=?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$result = $statement->fetch();
?>

<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <a class="toButton" href="<?= "DisplayList?id=".$result['fk_listid'] ?>">Back to List</a>

        <h2>Congratulations on your completed item!</h2>
        <h3>Completed on: <?= $result['dateCompleted'] ?></h3>
        <img src="<?= $result['photo'] ?>" alt="TestImage">
        <h3><?= $result['title'] ?></h3>
        <p><?= $result['description'] ?></p>
    </div>
<?php include "./includes/footer.php"; ?>