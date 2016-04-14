<?php

/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
	
	php code that redirect todolist page to index page
	also kill all sessions when user click on logout link
		
*/

# This page shows a user form for the student to log out of the system
require_once("shared.php");
session_start();
session_destroy();
session_regenerate_id(TRUE);
session_start();
redirect("index.php", "Logout successful.");
?>