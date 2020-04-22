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

        <div class ="item">
            <img src="https://i.pinimg.com/280x280_RS/d2/29/97/d229972ff3e0a850cbd0e90985b853df.jpg" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Rome</h3>
                <p>Rome has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
        <div class ="item">
            <img src="https://www.globalizationpartners.com/wp-content/uploads/2018/07/brazil.jpg" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Brazil</h3>
                <p>Brazil has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
        <div class ="item">
            <img src="https://img.thedailybeast.com/image/upload/dpr_2.0/c_crop,h_1440,w_1440,x_485,y_0/c_limit,w_128/d_placeholder_euli9k,fl_lossy,q_auto/v1529617465/180621-Kemper-Spain-fairy-tale-castle-03_eavjvs" alt="TestImage">
            <div class="bucket-content">
                <h3>Travel to Spain</h3>
                <p>Spain has always been a place I've dreamed of visiting. It's a place with rich culture, amazing food, and spectacular views. I can't wait to experience all it has to offer.</p>
            </div>
        </div>
    </div>
<?php include "./includes/footer.php"; ?>