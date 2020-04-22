<?php
//https://www.idiotinside.com/2016/05/21/secure-password-hashing-php/

session_start(); //Does this do anything?  How do I clear session variables on logout?
session_destroy();
session_start();
require "./includes/library.php";

$errors = [];

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
        header("Location: DisplayList.php");
        exit();
    } else {
        array_push($errors, "Incorrect password.");
    }
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
                    <input id="username" name="username" type="text" placeholder="Username">
                </div>
                <div>
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input id="password" name="password" type="text" placeholder="Password">
                </div>

                <button id="submit" name="submit" class="centered">Login</button>
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </form>
        </div>
        <p>Don't have an account?  <a href="Register.php" class="inline">Register</a> instead.</p>
    </div>
</body>
</html>