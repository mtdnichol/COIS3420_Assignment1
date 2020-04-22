<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bucket List</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link rel="stylesheet" href="css/Slider.css">
    <link rel="stylesheet" href="css/List.css">
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Lato:300,400,700|Roboto:300,400,700&display=swap" rel="stylesheet">
    <script defer src="./scripts/logout.js"></script>
    <script src="https://kit.fontawesome.com/1c8ee6a0f5.js" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let input = document.getElementById("list-search");
            input.addEventListener("keyup", function(event) {
                // Number 13 is the "Enter" key on the keyboard
                console.log(event.key);
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.location.href = "Search?title=" + event.target.value;
                }
            });
        });
    </script>
</head>
<body>
<div class="navigation-bar">
    <div class="dropdown">
        <button class="dropbtn">My Bucket Lists</button>
        <div class="dropdown-content">
            <?php foreach ($userLists as $list): ?>
                <a href="DisplayList.php" value="<?= $list['id'] ?>"><?= $list['title'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <input id="list-search" type="text" placeholder="&#xF002;    Search..." style="font-family:'Roboto', FontAwesome,serif">
    <button>I'm Feeling Lucky</button>

    <div class="user-buttons">
        <a href="Login.php" id="logout">Logout</a>
        <a href="Profile.php">Profile</a>
    </div>
</div>