<?php
session_start(); //Starts the session
session_destroy(); //Destroys the current session, erasing all session variables
header('Location: Login'); //Redirects the user to the login page