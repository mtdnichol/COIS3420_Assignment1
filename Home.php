<?php
session_start();
require "./includes/library.php";

/* Connect to DB */
$pdo = connectDB();

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

$page = 1;
if(isset($_GET['page']) && is_int($_GET['page']) && (int)$_GET['page'] > 0) {
    $page = (int)$_GET['page'];
}
$per_page = 10;
if(isset($_GET['per_page']) && is_int($_GET['per_page']) && (int)$_GET['per_page'] > 0) {
    $per_page = (int)$_GET['per_page'];
}

$min_limit = ($page - 1) * $per_page;
$max_limit = $page * $per_page;

$title = "";

$query = "SELECT bucket_lists.*, bucket_users.username FROM bucket_lists INNER JOIN bucket_users ON bucket_lists.fk_userid = bucket_users.id WHERE private = 0 AND title LIKE ? LIMIT ?,?";
$statement = $pdo->prepare($query);
$statement->execute(['%'.$title.'%', $min_limit, $max_limit]);
$searchLists = $statement->fetchAll();

?>

<?php include "./includes/header.php"; ?>
<div class="main-box">
    <h1><?php echo ucfirst($_SESSION['username'])?>'s Home Page</h1>
    <h2>-- All Lists --</h2>
    <?php foreach($searchLists as $key=>$value): ?>
        <div class="list-container">
            <div class="list-info" id="<?php echo $value['id'] ?>">
                <p class="list-title"><?php echo $value['title']." By ".$value['username']; ?></p>
                <p class="list-description"><?php echo $value['description']; ?></p>
                <p class="list-date"><?php echo $value['created']; ?></p>
            </div>
            <div class="list-properties">
                <div class="list-status">
                    <?php
                    if($value['private'] == 0) {
                        echo '<p class="list-public public">Public <i class="fas fa-lock-open"></i></p>';
                    } else {
                        echo '<p class="list-public private">Private <i class="fas fa-lock-closed"></i></p>';
                    }
                    ?>
                </div>
                <div class="list-links">
                    <a class="list-copy" href="#" id="<?php echo $value['id'] ?>">Copy Link <i class="fas fa-clone"></i></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php include "./includes/footer.php"; ?>
