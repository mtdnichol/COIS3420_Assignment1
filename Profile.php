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
        $query = "DELETE FROM `bucket_entries` WHERE fk_list=?";
        $statement = $pdo->prepare($query);
        $statement->execute([$list['id']]);
    }

    //Deletes all the lists the user is associated with
    $query = "DELETE FROM `bucket_lists` WHERE fk_userid=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_SESSION['userID']]);

    //Deletes the user
    $query = "DELETE FROM `bucket_users` WHERE id=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$_SESSION['userID']]);

    header('Location: Login.php');
    exit();
}

if (isset($_POST['deleteList'])){
    $listID = $_POST['listID'];

    /* Connect to DB */
    $pdo = connectDB();

    // query to delete list matching id
    $query = "DELETE FROM bucket_lists WHERE id=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$listID]); // fill with passed in id
}
?>

<head>
    <script src="scripts/Profile.js"></script>
</head>
<!-- HTML Starts -->
<?php include "./includes/header.php"; ?>
    <div class="main-box large">
        <h1><?php echo ucfirst($_SESSION['username'])?>'s Profile</h1>

        <div class="leftAlignText">
            <h3 class="profileFormat">Account Details</h3>
            <p><b>Username</b>: <?php echo $_SESSION['username']?></p>
            <p><b>E-mail</b>: <?php echo $email['email']?></p>
        </div>

        <h3 class="space profileFormat">Your Bucket Lists</h3>
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
                    <td><a href="<?php
                        $currPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        echo substr($currPath, 0, strrpos($currPath, '/')) . "/DisplayList?id=" . $list['id'];
                        ?>"><?php
                            $currPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            echo substr($currPath, 0, strrpos($currPath, '/')) . "/DisplayList?id=" . $list['id'];
                            ?></a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3 class="space profileFormat">Other Operations</h3>
        <form id="delete-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="getConfirmation()">
            <button id="submit" name="submit" class="delete">Delete Account</button>
        </form>


    </div>
<?php include "./includes/footer.php"; ?>