<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

/* Connect to DB */
$pdo = connectDB();

$query = "SELECT email FROM `bucket_users` WHERE id=?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$email = $statement->fetch();

$query = "SELECT * FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$lists = $statement->fetchAll();

?>

<!-- HTML Starts -->
<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <h1><?php echo $_SESSION['username']?>'s Profile</h1>

        <div class="leftAlignText">
            <h2>Account Details</h2>
            <p><b>Username</b>: <?php echo $_SESSION['username']?></p>
            <p><b>E-mail</b>: <?php echo $email['email']?></p>
        </div>

        <h2>Your Bucket Lists</h2>
        <table class="profile">
            <tr>
                <th>List Name</th>
                <th>Privacy Setting</th>
                <th>Link</th>
            </tr>

            <?php foreach ($lists as $list): ?>
                <tr>
                    <td><?= $list['title'] ?></td>

                    <?php if ($list['private'] == 0): ?>
                        <td>Public</td>
                    <?php else: ?>
                        <td>Private</td>
                    <?php endif ?>

                    <td>INSERT LINK</td>
                </tr>
            <?php endforeach; ?>
        </table>


    </div>
<?php include "./includes/footer.php"; ?>