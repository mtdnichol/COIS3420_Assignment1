<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

/* Connect to DB */
$pdo = connectDB();

$query = "SELECT * FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$userLists = $statement->fetchAll();

$query = "SELECT title FROM `bucket_lists` WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([1]); //Replace with list number, check if first time loading
$title = $statement->fetch();

$query = "SELECT id, title, photo, description FROM `bucket_entries` WHERE fk_listid = ?";
$statement = $pdo->prepare($query);

$statement->execute([$_GET['id']]);
$results = $statement->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bucket List</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Lato:300,400,700|Roboto:300,400,700&display=swap" rel="stylesheet">
    <script defer src="./scripts/logout.js"></script>
    <script src="https://kit.fontawesome.com/1c8ee6a0f5.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="navigation-bar">
        <div class="dropdown">
            <button class="dropbtn">My Bucket Lists</button>
            <div class="dropdown-content">
                <?php foreach ($userLists as $list): ?>
                    <a href="DisplayList.php" value="<?= $list['id'] ?>"><?= $list['title'] ?></a>
                <?php endforeach; ?>
<!--                <a href="DisplayList.php">Bobby's Bucket List</a>-->
<!--                <a href="DisplayList.php">Bucket List 2</a>-->
            </div>
        </div>
        <input type="text" placeholder="&#xF002;    Search..." style="font-family:'Roboto', FontAwesome,serif">

        <div class="user-buttons">
            <a href="Login.php" id="logout">Logout</a>
            <a>Profile</a>
        </div>
    </div>

    <div class="main-box">
        <h1><?php echo $_SESSION['username']?>'s Bucket List</h1>
        <h2><?php echo $title['title'] ?></h2>
        <div class="bucket-list-nav">
            <a href="ManageList.php" class="right"><i class="fas fa-tasks"></i> Manage</a>
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
</body>
</html>