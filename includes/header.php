<?php
/* Connect to DB */
$pdo = connectDB();

//get userID and title from database to use in file later.
$query = "SELECT id, title FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$userLists = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bucket List</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link rel="stylesheet" href="css/Slider.css">
    <link rel="stylesheet" href="css/List.css">
    <link rel="stylesheet" href="css/Modal.css">
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Lato:300,400,700|Roboto:300,400,700&display=swap" rel="stylesheet">
    <script defer src="./scripts/logout.js"></script>
    <script src="https://kit.fontawesome.com/1c8ee6a0f5.js" crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/jquery-3.5.0.min.js"
            integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
            crossorigin="anonymous"></script>
    <script src="scripts/Modal.js"></script>
    <!--Search bar script-->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            //grab search input and add event listener on release of key
            let input = document.getElementById("list-search");
            input.addEventListener("keyup", function(event) {
                // Number 13 is the "Enter" key on the keyboard
                if (event.key === "Enter") {
                    event.preventDefault();
                    if(event.target.value === "") {
                        document.location.href = "Search";
                    } else {
                        document.location.href = "Search?title=" + event.target.value;
                    }
                }
            });
            //im feeling lucky search, will display list with a random ID
            document.querySelector(".search i").addEventListener('click', (event) => {
                document.location.href = "DisplayList?id=random"
            });
        });
    </script>
</head>
<body>
<div class="navigation-bar">
    <div class="dropdown">
        <button class="dropbtn">My Bucket Lists</button>
        <div class="dropdown-content">
            <!-- Iterate through bucket list that the user has, and display it-->
            <?php foreach ($userLists as $list): ?>
                <a href="DisplayList?id=<?= $list['id'] ?>" value="<?= $list['id'] ?>"><?= $list['title'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="search">
        <input id="list-search" type="text" placeholder="&#xF002;    Search..." style="font-family:'Roboto', FontAwesome,serif">
        <i class="fas fa-magic" data-tippy-content="I'm Feeling Lucky"></i>
    </div>

    <div class="user-buttons">
        <a href="Logout" id="logout">Logout</a>
        <a href="Profile">Profile</a>
    </div>
</div>