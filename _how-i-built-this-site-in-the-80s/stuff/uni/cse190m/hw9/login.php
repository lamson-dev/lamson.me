<?php

/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
	
	php code that allow user login form to submits to here	
	Upon login, remembers user login name in a PHP session variable
		
*/

include("shared.php");

$username = get_parameter("name");
$password = get_parameter("password");

if (is_correct_password($username, $password)) {
	start_page_session();
	$_SESSION["name"] = $username;	#remember user name
	redirect("todolist.php", "Login successful! Welcome back.");
} else {
	redirect("index.php", "Incorrect Username/Password. Please try again.");
}

?>