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
$searchLine = empty($title) ? "Find all lists" : "Find lists associated with ".$title;
?>

<?php include "./includes/header.php"; ?>
<div class="main-box">
    <h1>Search Results</h1>
    <h3><?php echo $searchLine ?></h3>
    <?php foreach($searchLists as $key=>$value): ?>
    <div class="list-container">
        <div class="list-info">
            <p class="list-title">List Title by Dctr</p>
            <p class="list-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc maximus, nulla ut commodo sagittis, sapien dui mattis dui, non pulvinar lorem felis nec erat</p>
            <p class="list-date">Dec 21. 2019</p>
        </div>
        <div class="list-properties">
            <div class="list-status">
                <p class="list-public">Public <i class="fas fa-lock-open"></i></p>
            </div>
            <div class="list-links">
                <p class="list-copy">Copy Link <i class="fas fa-clone"></i></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include "./includes/footer.php"; ?>

