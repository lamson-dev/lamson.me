<?php include("top.html"); ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #4

		TA: Rishi Goutam		
		
		Description: php file of "NerdLuv" online dating site
		 
		this page get user information from signup.php
		then add user information into singles.txt file
		and show Thank You note to user
-->

<?php

	$userinfo = $_POST;	
	$name = $userinfo["name"];
	
	# add a new line with new user information into singles.txt
	$newline = implode(",", $userinfo) . "\n";
	$text = file_get_contents("singles.txt") . $newline;
	file_put_contents("singles.txt", $text);
	
?>
	<div>
		<h1>Thank you!</h1>
		<p>Welcome to NerdLuv, <?= $name ?>!</p>
		<p>Now <a href="matches.php">log in to see your matches!</a></p>
	</div>


<?php include("bottom.html"); ?>