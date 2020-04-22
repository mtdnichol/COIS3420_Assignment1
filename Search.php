<?php
session_start();
require "./includes/library.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
    header("Location: Login.php");
    exit();
}

/* Connect to DB */
$pdo = connectDB();

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

$title = $_GET['title'] ?? "";

$query = "SELECT bucket_lists.*, bucket_users.username FROM bucket_lists INNER JOIN bucket_users ON bucket_lists.fk_userid = bucket_users.id WHERE private = 0 AND title LIKE ? LIMIT ?,?";
$statement = $pdo->prepare($query);
$statement->execute(['%'.$title.'%', $min_limit, $max_limit]);
$searchLists = $statement->fetchAll();
var_dump($searchLists);
?>

<?php include "./includes/header.php"; ?>
<div>
    <?php foreach($searchLists as $key=>$value): ?>
    <div class="searchList">
        <p><b>List:</b> <?php echo $value['title']?></p>
        <p><b>Created By:</b> <?php echo $value['username']?></p>
    </div>
    <?php endforeach; ?>
</div>
<?php include "./includes/footer.php"; ?>

