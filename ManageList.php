<?php
session_start();
require "./includes/library.php"; //Imports required database files
require "./includes/util.php";

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) { //Ensures the user has a valid session
    header("Location: Login.php"); //If not, redirect them to the login page
    exit();
}

if(!isset($_GET['id']) || !is_int($_GET['id'])) { //Checks that the list id passed is valid
    header('Location:');
}

/* Connect to DB */
$pdo = connectDB();

// current list id stored in the url
$curID = $_GET['id'];

//Gets the list information as well as the username by joining tables
$query = "SELECT title, username, description FROM `bucket_lists` INNER JOIN bucket_users ON bucket_lists.fk_userid = bucket_users.id WHERE bucket_lists.id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$list = $statement->fetch();

//Assigns returned values to variables
$username = $list['username'];
$title = $list['title'];
$description = $list['description'];

//Queries the bucket entries for all entries associated with the list
$query = "SELECT id, title, photo, description FROM `bucket_entries` WHERE fk_listid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$curID]);
$results = $statement->fetchAll();

//If the user wishes to exit, they are redirected back to the display list page
if (isset($_POST['exit'])) {
    header("Location: DisplayList");
    exit();
}

//If the user viewing the list in the manage list page isn't associated with the list, they are redirected and prompted to login as the proper user
if(!isOwner($curID)) {
    header("Location: Login");
    exit();
}
?>

<!--html file starts-->
<?php include "./includes/header.php"; ?>
    <link href="https://transloadit.edgly.net/releases/uppy/v1.13.2/uppy.min.css" rel="stylesheet">
    <div class="main-box">
        <h1><?php echo ucfirst($username) ?>'s Bucket List</h1> <!-- Shows the users username that was gathered from the database -->
        <div class="titleHeader">
            <h2><?php echo $title ?></h2> <!-- Shows the title of the list from the database -->
        </div>
        <div class="titleEdit hidden"> <!-- Allows the user to edit the title, is unhidden when the user selects the option to edit the title -->
            <input type="text">
            <button id="titleSubmit">Submit</button>
        </div>

        <div class="bucketDesc"> <!-- Shows the bucket list description from the database -->
            <p id="bucketDescription"><?php echo $description ?></p>
        </div>
        <div class="bucketEdit hidden"> <!-- Allows the user to edit the description of the list, hidden until the user clicks on the description to edit -->
            <input type="text">
            <button id="descSubmit">Submit</button>
        </div>


        <div class="bucketListNav"> <!-- Upper nav bar which contains buttons to interact with the list, displayed above the list items themselves -->
            <div class="leftButtons"> <!-- Refers to the buttons on the left side of the nav bar -->
                <div class="button-horizontal"> <!-- Ensures the buttons are displayed horizontally -->
                    <button id="addItem" data-open-modal="addItemModal" name="addItem" data-tippy-content="Add Item"><i class="fas fa-plus"></i></button> <!-- Add Item button -->
                    <div id="addItemModal" class="modal"> <!-- Modal that is created when the user clicks the add item button -->
                        <div class="modal-content">
                            <span class="close-btn">&times;</span> <!-- Exit button to get out of the modal, can also escape by clicking outside the modal -->
                            <div class="addModalContent">
                                <label for="nameEdit" class="addLabel">Item Name</label> <!-- Title section in modal -->
                                <input type="text" id="nameEdit">
                            </div>
                            <div class="addModalContent">
                                <label for=descEdit" class="addLabel">Description</label> <!-- Description section in modal -->
                                <textarea name="descEdit" id="descEdit" cols="30" rows="10"></textarea>
                            </div>
                            <div class="addModalContent">
<!--                            temporarily refreshes instead of just adding to screen-->
                                <a class="addSubmit" onclick="addTask(<?php echo $_GET['id'] ?>)">Submit</a> <!-- User can submit the modal, which calls JS to validate and submit through an API, therefore not refreshing the page -->
                            </div>
                        </div>
                    </div>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- Edit list button -->
                        <button id="editList" name="editList" onclick="titleSwap(); return false;" data-tippy-content="Edit List Title"><i class="fas fa-edit"></i></button> <!-- Uses JS and AJAX to swap the title on page -->
                    </form>
                    <form action="Profile" method="POST"> <!-- Delete List button -->
                        <input type="hidden" name="listID" value="<?php echo $_GET['id'] ?>"> <!-- Passes the list ID through the POST as a hidden input -->
                        <button id="deleteList" name="deleteList" data-tippy-content="Delete List" onclick="return confirmation()"><i class="fas fa-trash-alt"></i></button> <!-- Validates the users request, deletes the database -->
                    </form>
                </div>
            </div>
            <div class="rightButtons"> <!-- Right buttons on the nav bar -->
                <div class="button-horizontal">
                    <button class="<?php echo isPrivate($_GET['id']) ? "" : "hidden"?>" id="privatize" name="privatize" onclick="return privacySwap('<?php echo $_GET['id'] ?>');" data-tippy-content="Make Public"><i class="fa fa-lock"></i></button> <!-- Swaps the privacy button on page using JS/AJAX while using API backend to make calls to DB -->
                    <button class="<?php echo isPrivate($_GET['id']) ? "hidden" : ""?>" id="privatize-lock" name="privatize-lock" onclick="return privacySwap('<?php echo $_GET['id'] ?>');" data-tippy-content="Make Private"><i class="fa fa-unlock"></i></button>
                    <form id="exit-form" action="<?php echo "DisplayList?id=".$_GET['id']?>" method="POST"> <!-- User can exit the page, returning to the DisplayList.php page -->
                        <button id="exit" name="exit"><i class="fas fa-sign-out-alt"></i> Exit</button>
                    </form>
                </div>

            </div>
        </div>

        <?php foreach ($results as $result): ?> <!-- Iterates over each returned List entry, creates an element and displays it -->
            <input id="dbid" type="hidden" name="value"> <!-- Holds the id of the entry in a hidden input for later use -->

            <div class="item" data-item-id="<?php echo $result['id'] ?>"> <!-- Creates a item -->
                <div class="item-buttons"> <!-- Allows the user to perform actions on individual items -->
                    <button id="markItem" onclick="resetUppy()" class="markItem" data-open-modal="markItemModal" name="markItem" data-tippy-content="Mark Completed"><i class="fas fa-check"></i></button> <!-- Mark item complete -->
                    <div id="markItemModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn">&times;</span>
                            <label> Completed Date: <input id="completedDate" type="date"></label> <!-- Allows the user to input dates/images associated with the item before completion -->
                            <button id="openUppy">Upload Image</button>
                            <button onclick="return markItemComplete('<?php echo $result['id'] ?>');">Mark Completed</button> <!-- Makes an API call to mark an item complete -->
                            <p id="uploadErrors"></p> <!-- Presents errors, if any -->
                        </div>
                    </div>
                    <button data-open-modal="editItemModal" id="editItem" class="editItem" name="editItem" data-tippy-content="Edit Item"><i class="fas fa-edit"></i></button>
                    <div id="editItemModal" class="modal"> <!-- Allows the user to edit the item -->
                        <div class="modal-content">
                            <span class="close-btn">&times;</span>
                            <div class="addModalContent">
                                <label for="nameModify" class="editLabel">New Name</label> <!-- Enter a new name -->
                                <input type="text" id="nameModify">
                            </div>
                            <div class="addModalContent">
                                <label for=descModify" class="editLabel">Description</label> <!-- Enter a new/revised description -->
                                <textarea name="descEdit" id="descModify" cols="30" rows="10"></textarea>
                            </div>
                            <div class="addModalContent">
                                <!--                            temporarily refreshes instead of just adding to screen-->
                                <a class="editSubmit" onclick="editTask(<?php echo $_GET['id'] ?>, <?php echo $result['id'] ?>)">Submit</a>
                            </div>
                        </div>
                    </div>
                    <button id="deleteItem" class="deleteItem" name="deleteItem" data-tippy-content="Delete Item" onclick="return deleteItem('<?php echo $result['id'] ?>');"><i class="fas fa-trash-alt"></i></button> <!-- Allows the user to delete an item, uses JS/AJAX to delete with a API call -->
                </div>
                <div class="bucket-content" id="<?= $result['id'] ?>"> <!-- Displays item title and content pulled from DB in current item -->
                    <h3><?= $result['title'] ?></h3>
                    <p><?= $result['description'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://transloadit.edgly.net/releases/uppy/v1.13.2/uppy.min.js"></script> <!-- Uploader plugin -->
    <script defer src="./scripts/ManageList.js"></script> <!-- Scripts associated with page loaded after completion -->
<?php include "./includes/footer.php"; ?> <!-- Footer defined in includes folder -->