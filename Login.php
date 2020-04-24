<?php
//https://www.idiotinside.com/2016/05/21/secure-password-hashing-php/

session_start(); //Does this do anything?  How do I clear session variables on logout?
require "./includes/library.php";

$errors = [];

//Hidden input


if (isset($_POST['submit'])) {
    /* Process log-in request */
    $username = $_POST['username'];
    $password = $_POST['password'];

    /* Connect to DB */
    $pdo = connectDB();

    /* Check the database for occurrences of $username */
    $query = "SELECT id, username, password FROM `bucket_users` WHERE username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([ $username ]);
    $results = $statement->fetch();

    if ($results === FALSE) {
        array_push($errors, "That user doesn't exist.");
    } else if (password_verify($password, $results['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = $results['id'];

        //Check if the remember me function has been selected  ->https://tutorialsclass.com/code/php-login-remember-cookies-script
        if(!empty($_POST["remember"])) {
            setcookie ("username",$_POST["username"],time()+ 3600);
            setcookie ("password",$_POST["password"],time()+ 3600);
            echo "Cookies Set Successfuly";
        } else {
            setcookie("username","");
            setcookie("password","");
            echo "Cookies Not Set";
        }

        header("Location: Profile");
        exit();
    } else {
        array_push($errors, "Incorrect password.");
        $_POST['failed'] = true;
    }
}

if (isset($_POST['deleteList'])){
    $listID = $_POST['listID'];

    /* Connect to DB */
    $pdo = connectDB();

    // query to delete list matching id
    $query = "DELETE FROM bucket_lists WHERE id=?";
    $statement = $pdo->prepare($query);
    $statement->execute([$listID]); // fill with passed in id
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Lato:300,400,700|Roboto:300,400,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1c8ee6a0f5.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="main-box">
        <h1>Login</h1>
        <div class="login-box">
            <form id="main-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- Redirect to DisplayList.html upon completion -->
                <div>
                    <label for="username"><i class="fas fa-envelope"></i></label>
                    <input id="username" name="username" type="text" placeholder="Username" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>">
                </div>
                <div>
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input id="password" name="password" type="password" placeholder="Password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>">
                </div>
                <div>
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button id="submit" name="submit" class="centered">Login</button>
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </form>
        </div>
        <p>Don't have an account?  <a href="Register" class="inline">Register</a> instead.</p>
        <p>Forgot your password?  <a href="RecoverPassword" class="inline">Click here!</a></p>
    </div>
</body>
</html>