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

if (isset($_POST['submit'])) {
    //Gets all the lists the user is associated with
    $query = "SELECT id FROM `bucket_lists` WHERE fk_userid=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_SESSION['userID']]);
    $userLists = $statement->fetchAll();

    //Deletes all of the entries in the lists the user is associated with
    foreach ($userLists as $list) {
        $query = "DELETE * FROM `bucket_entries` WHERE fk_list=?";
        $statement = $pdo->prepare($query);
        $statement->execute([$list['id']]);
    }

    //Deletes all the lists the user is associated with
    $query = "DELETE * FROM `bucket_lists` WHERE fk_userid=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_SESSION['userID']]);

    //Deletes the user
    $query = "DELETE * FROM `bucket_users` WHERE id=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_SESSION['userID']]);
}

?>

<head>
    <script src="./scripts/deleteConfirmation.js"></script>
</head>
<!-- HTML Starts -->
<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <h1><?php echo $_SESSION['username']?>'s Profile</h1>

        <div class="leftAlignText">
            <h3>Account Details</h3>
            <p><b>Username</b>: <?php echo $_SESSION['username']?></p>
            <p><b>E-mail</b>: <?php echo $email['email']?></p>
        </div>

        <h3 class="space">Your Bucket Lists</h3>
        <table class="profile">
            <tr>
                <th>List Name</th>
                <th>Privacy Setting</th>
                <th>Description</th>
                <th>Date Created</th>
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

                    <td><?= $list['description'] ?></td>
                    <td><?= $list['created'] ?></td>
                    <td>INSERT LINK</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3 class="space">Other Operations</h3>

        <form id="delete-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <input id="submit" name="submit" type ="button" value="Delete Account" onclick = "getConfirmation();">
        </form>

        <button class="delete">Delete Account</button>
    </div>
<?php include "./includes/footer.php"; ?>