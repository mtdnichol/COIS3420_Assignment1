<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

// current list id
$curID = $_GET['id'];

/* Connect to DB */
$pdo = connectDB();

$query = "SELECT * FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$userLists = $statement->fetchAll();

$query = "SELECT title FROM `bucket_lists` WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([1]);
$title = $statement->fetch();

$query = "SELECT id, title, photo, description FROM `bucket_entries` WHERE fk_listid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$results = $statement->fetchAll();

if (isset($_POST['deleteItem'])) {
    echo $_POST['value'];
}

if (isset($_POST['exit'])) {
    header("Location: DisplayList.php");
    exit();
}
?>

<!--html file starts-->
<?php include "./includes/header.php"; ?>
    <div class="main-box">
        <h1><?php echo $_SESSION['username']?>'s Bucket List</h1>
        <div class="titleHeader">
            <h2><?php echo $title['title'] ?></h2>
        </div>
        <div class="titleEdit hidden">
            <input type="text">
            <button id="titleSubmit">Submit</button>
        </div>

        <div class="bucketListNav" style="">
            <div class="leftButtons">
                <div class="button-horizontal">
                    <button id="addItem" name="addItem" data-tippy-content="Add Item"><i class="fas fa-plus"></i></button>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <button id="editList" name="editList" onclick="titleSwap(); return false;" data-tippy-content="Edit List Title"><i class="fas fa-edit"></i></button>
                    </form>
                    <!--                Form to submit list id using get to profile page, allowing list to be deleted-->
                    <!--                currently points to login since no profile page-->
                    <form action="./Login.php" method="POST">
                        <input type="hidden" name="listID" value="<?php echo $_GET['id'] ?>">
                        <button id="deleteList" name="deleteList" data-tippy-content="Delete List" onclick="return confirmation()"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </div>
            </div>
            <div class="rightButtons">
                <div class="button-horizontal">
                    <button class="" id="privatize" name="privatize" data-tippy-content="Make Private"><i class="fa fa-lock"></i></button>
                    <form id="exit-form" action="<?php echo "DisplayList.php?id=".$_GET['id']?>" method="POST">
                        <button id="exit" name="exit"><i class="fas fa-sign-out-alt"></i> Exit</button>
                    </form>
                </div>

            </div>
        </div>

        <?php foreach ($results as $result): ?>
            <form id="item-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="value" value="<?php $result['id'] ?>">
                <div class ="item">
                    <div class="item-buttons">
                        <button class="markItem" name="markItem" data-tippy-content="Mark Completed"><i class="fas fa-check"></i></button>
                        <button class="editItem" name="editItem" data-tippy-content="Edit Item"><i class="fas fa-edit"></i></button>
                        <button class="deleteItem" name="deleteItem" data-tippy-content="Delete Item"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <img src="<?= $result['photo'] ?>" alt="TestImage">
                    <div class="bucket-content" value="<?= $result['id'] ?>">
                        <h3><?= $result['title'] ?></h3>
                        <p><?= $result['description'] ?></p>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <script>
        tippy('[data-tippy-content]');
    </script>
    <script defer src="./scripts/ManageList.js"></script>
<?php include "./includes/footer.php"; ?>

