<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

?>

<?php include "./includes/header.php"; ?>
<?php include "./includes/footer.php"; ?>
