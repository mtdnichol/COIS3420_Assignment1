<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bucket List</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link rel="stylesheet" href="css/Slider.css">
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