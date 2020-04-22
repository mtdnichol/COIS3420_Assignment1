<?php
session_start();
require "./includes/library.php";

//if the user isn't signed in, redirect to login page
if(!isset($_SESSION['loggedin'])){
  header('Location: Login.php');
  exit;
}

//make a connection to database
$pdo = connectDB();

//query selecting  username, password and email from the Bucket User
$query ='SELECT username, password, email FROM bucket_users WHERE username = ?';
$statement = $pdo -> prepare($query);
$statement -> execute([$_SESSION['username']]);
$userInfo = $statement -> fetchAll();

//to display the bucket list of the signned in user account
$query = "SELECT * FROM `bucket_lists` WHERE fk_userid = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['userID']]);
$userLists = $statement->fetchAll();
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>Profile</title>
     <link rel="stylesheet" href="/css/MainStyle.css">
     <script defer src="./scripts/logout.js"></script>
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
   </head>
   <body>
     <nav class = "topnav">
       <div>
         <h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.js"><i class="fas fa-sign-out-alt"></i>Logout</a>
       </div>
     </nav>
     <div class="content">
       <h2>Profile Page</h2>
       <div>
         <p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['username']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
          <tr>
            <td>Bucket list: </td>
            <td>
            <?php foreach ($userLists as $list): ?>
          <a href="DisplayList.php" value="<?= $list['id'] ?>"><?= $list['title'] ?></a>
            <?php endforeach; ?>
          </td>
          </tr>
				</table>
       </div>
     </div>
   </body>
 </html>
