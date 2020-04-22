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
$statement->execute([$curID]);
$title = $statement->fetch();

$query = "SELECT id, title, photo, description FROM `bucket_entries` WHERE fk_listid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$results = $statement->fetchAll();

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

        <div class="bucket-list-nav">
            <div class="button-horizontal">
                <button id="addItem" name="addItem" data-tippy-content="Add Item"><i class="fas fa-plus"></i></button>
                <button id="editList" name="editList" onclick="titleSwap(); return false;" data-tippy-content="Edit List Title"><i class="fas fa-edit"></i></button>
<!--                Form to submit list id using get to profile page, allowing list to be deleted-->
<!--                currently points to login since no profile page-->
                <form action="./Login.php" method="POST">
                    <input type="hidden" name="listID" value="<?php echo $_GET['id'] ?>">
                    <button id="deleteList" name="deleteList" data-tippy-content="Delete List"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
            <div class="exit-buttons button-horizontal">
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"><span class="on">Private</span><span class="off">Public</span></span>
                </label>
                <form id="exit-form" action="<?php echo "Login.php?id=".$_GET['id']?>" method="POST">
                    <button id="exit" name="exit"><i class="fas fa-sign-out-alt"></i> Exit</button>
                </form>
<!--                <a href="DisplayList.php"><i class="fas fa-sign-out-alt"></i> Exit</a>-->
            </div>
        </div>

        <?php foreach ($results as $result): ?>
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
        <?php endforeach; ?>

        <div class ="item">
            <div class="item-buttons">
                <button class="markItem" name="markItem" data-tippy-content="Mark Completed"><i class="fas fa-check"></i></button>
                <button class="editItem" name="editItem" data-tippy-content="Edit Item"><i class="fas fa-edit"></i></button>
                <button class="deleteItem" name="deleteItem" data-tippy-content="Delete Item"><i class="fas fa-trash-alt"></i></button>
            </div>
            <img src="https://i.pinimg.com/280x280_RS/d2/29/97/d229972ff3e0a850cbd0e90985b853df.jpg" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Rome</h3>
                <p>Rome has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
        <div class ="item">
            <div class="item-buttons">
                <button class="markItem" name="markItem" data-tippy-content="Mark Completed"><i class="fas fa-check"></i></button>
                <button class="editItem" name="editItem" data-tippy-content="Edit Item"><i class="fas fa-edit"></i></button>
                <button class="deleteItem" name="deleteItem" data-tippy-content="Delete Item"><i class="fas fa-trash-alt"></i></button>
            </div>
            <img src="https://www.globalizationpartners.com/wp-content/uploads/2018/07/brazil.jpg" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Brazil</h3>
                <p>Brazil has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
        <div class ="item">
            <div class="item-buttons">
                <button class="markItem" name="markItem" data-tippy-content="Mark Completed"><i class="fas fa-check"></i></button>
                <button class="editItem" name="editItem" data-tippy-content="Edit Item"><i class="fas fa-edit"></i></button>
                <button class="deleteItem" name="deleteItem" data-tippy-content="Delete Item"><i class="fas fa-trash-alt"></i></button>
            </div>
            <img src="https://img.thedailybeast.com/image/upload/dpr_2.0/c_crop,h_1440,w_1440,x_485,y_0/c_limit,w_128/d_placeholder_euli9k,fl_lossy,q_auto/v1529617465/180621-Kemper-Spain-fairy-tale-castle-03_eavjvs" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Spain</h3>
                <p>Spain has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <script>
        tippy('[data-tippy-content]');
    </script>
    <script defer src="./scripts/ManageList.js"></script>
<?php include "./includes/footer.php"; ?>

