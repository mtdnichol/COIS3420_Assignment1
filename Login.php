<?php
//https://www.idiotinside.com/2016/05/21/secure-password-hashing-php/

session_start();
require "./includes/library.php";

if ((isset($_SESSION['username']) && $_SESSION['username'] != '')) { //Checks if the user is already logged in, if so redirect to their profile
    header("Location: Profile");
    exit();
}

//Array of errors which are displayed
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

    if ($results === FALSE) { //Checks if the DB call returns any values
        array_push($errors, "That user doesn't exist."); //If the username doesn't exist, notify the user
    } else if (password_verify($password, $results['password'])) { //Check if the entered password matches the hashed password in the database
        $_SESSION['username'] = $username; //If it does, set session variables accordingly
        $_SESSION['userID'] = $results['id'];

        //Check if the remember me function has been selected  ->https://tutorialsclass.com/code/php-login-remember-cookies-script
        if(!empty($_POST["remember"])) {
            setcookie ("username",$_POST["username"],time()+ 3600); //Creates a cookie for the username, password, and checkbox
            setcookie ("password",$_POST["password"],time()+ 3600);
            setcookie ("checked", true, time()+ 3600);
        } else { //If remember me is unticked, then cookies are reset and destroyed
            setcookie("username","");
            setcookie("password","");
            setcookie ("checked", false, time()+ 3600);
        }

        header("Location: Profile"); //Redirects to profile upon successful login
        exit();
    } else {
        array_push($errors, "Incorrect password."); //If password is wrong, notify the user
        $_POST['failed'] = true;
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
            <form id="main-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- Self processing form that validates entered username and password -->
                <div>
                    <label for="username"><i class="fas fa-envelope"></i></label>
                    <input id="username" name="username" type="text" placeholder="Username" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>"> <!-- Loads from cookie if it exists -->
                </div>
                <div>
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input id="password" name="password" type="password" placeholder="Password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>"> <!-- Loads from cookie if it exists -->
                </div>
                <div>
                    <?php if (isset($_COOKIE['checked']) && $_COOKIE['checked'] == true): ?> <!-- Checks if the checkbox cookie is checked -->
                        <input type="checkbox" id="remember" name="remember" checked> <!-- If it is, create remember me already checked -->
                    <?php else: ?> <!-- Otherwise create an unchecked box.  When user unchecks box their cookies are reset to null values -->
                        <input type="checkbox" id="remember" name="remember">
                    <?php endif; ?>
                    <label for="remember">Remember me</label>
                </div>

                <button id="submit" name="submit" class="centered">Login</button>
                <?php foreach ($errors as $error): ?> <!-- Displays all errors to the user -->
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </form>
        </div>
        <p>Don't have an account?  <a href="Register" class="inline">Register</a> instead.</p> <!-- Links to register and password reset pages -->
        <p>Forgot your password?  <a href="RecoverPassword" class="inline">Click here!</a></p>
    </div>
</body>
</html>