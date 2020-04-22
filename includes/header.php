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