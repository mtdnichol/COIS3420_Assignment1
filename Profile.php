<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

$errors = [];

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

if (isset($_POST['completeCreation'])) {
    $date = date("Y-m-d");
    $private = 0;
    if (!empty($_POST['privacy'])) {
        $private = 1;
    }

    $query="INSERT INTO `bucket_lists`(`title`, `fk_userid`, `created`, `description`, `private`) VALUES (?,?,?,?,?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$_POST['title'], $_SESSION['userID'], $date, $_POST['description'], $private]);
}
?>

<head>
    <script src="scripts/Profile.js"></script>
    <link rel="stylesheet" href="css/createList.css">
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

        <div class="bucketListNav">
            <button id="createList" data-open-modal="createListModal" name="createList" data-tippy-content="Create A List"><i class="fas fa-plus"></i></button>
            <div id="createListModal" class="modal">
                <div class="modal-content login-box">
                    <span class="close-btn">&times;</span>

                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div>
                            <label for="title"></i>List Title</label>
                            <input id="title" name="title" type="text" placeholder="Title" required>
                        </div>
                        <div>
                            <label for="description"></i>List Description</label>
                            <input id="description" name="description" type="text" placeholder="Description" required>
                        </div>
                        <div>
                            <label for="privacy"></i>Private</label>
                            <input id="privacy" name="privacy" type="checkbox">
                        </div>

                        <button id="completeCreation" name="completeCreation" class="centered createButton">Submit</button>
                    </form>
                </div>
            </div>
        </div>

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