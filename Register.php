<?php
//$password = 'badpassword';
//$options = ['cost' => 12];
//echo password_hash($password, PASSWORD_DEFAULT, $options);

session_start();
require "./includes/library.php";

$errors = [];
$email_regex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/"; //Email regex from lab 9

if (isset($_POST['submit'])) {
    $valid = true;

    /* Process log-in request */
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_check = $_POST['password-check'];



    /* Connect to DB */
    $pdo = connectDB();

    /* Check the database for occurrences of $username */
    $query = "SELECT username FROM `bucket_users` WHERE username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([ $username ]);
    $results = $statement->fetch();

    //Second implementation, would display all current errors
    if (!empty($results)) { //Database contains a user registered with the name
        array_push($errors, "Username taken.");
        $valid = false;
    }
    if (!preg_match($email_regex, $email)) { //Checks if the email passes regex
        array_push($errors, "Invalid E-mail Formatting.");
        $valid = false;
    }
    if ($password != $password_check) { //Checks if the passwords entered match
        array_push($errors, "Passwords Don't Match.");
        $valid = false;
    }

    if ($valid) {
        $options = ['cost' => 12];
        $password = password_hash($password, PASSWORD_DEFAULT, $options);

        $query = "INSERT INTO bucket_users (username, password, email) VALUES (?,?,?)";
        $statement = $pdo->prepare($query);
        $statement->execute([ $username, $password, $email ]);

        $_SESSION['username'] = $username;
        $_SESSION['userID'] = $pdo->lastInsertId();

//        $query = "SELECT id, username FROM `bucket_users` WHERE username = ?";
//        $statement = $pdo->prepare($query);
//        $statement->execute([ $username ]);
//        $results = $statement->fetch();

//        $_SESSION['username'] = $results['username'];
//        $_SESSION['userID'] = $results['id'];

        //Create default list for user
//        $query = "INSERT INTO bucket_lists (title, fk_userid) VALUES ('Default List',?)";
//        $statement = $pdo->prepare($query);
//        $statement->execute([$_SESSION['userID']]);

        header("Location: Profile");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/MainStyle.css">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Lato:300,400,700|Roboto:300,400,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1c8ee6a0f5.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="main-box">
        <h1>Register</h1>
        <div class="login-box">
            <form id="main-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- Redirect to DisplayList.php -->
                <div class="box2">
                    <div>
                        <label for="email"><i class="fas fa-envelope"></i></label>
                        <input id="email" name="email" type="text" placeholder="E-mail">
                    </div>
                    <div>
                        <input id="username" name="username" type="text" placeholder="Username">
                        <label for="username"><i class="fas fa-user"></i></label>
                    </div>
                </div>
                <div class="box2">
                    <div>
                        <label for="password"><i class="fas fa-lock"></i></label>
                        <input id="password" name="password" type="text" placeholder="Password">
                    </div>
                    <div>
                        <input id="password-check" name="password-check" type="text" placeholder="Re-type Password">
                        <label for="password-check"><i class="fas fa-lock"></i></label>
                    </div>
                </div>

                <button id="submit" name="submit" class="centered">Register</button>
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </form>
        </div>
    </div>
</body>
</html>