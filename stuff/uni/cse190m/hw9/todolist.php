<?php
/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
		
	php code for todolist page
	Shows the user To-Do List, user must be logged in to see
		
*/
 
include("top.html");
include("shared.php");

start_page_session();
ensure_logged_in();
set_flash_message();

?>
		<div id="main">
			<h2><?= $_SESSION["name"] ?>'s To-Do List</h2>

			<div id="buttons">
				<input id="itemtext" type="text" size="30" autofocus="autofocus" /> <br />
				<button id="add">Add to Bottom</button>
				<button id="delete">Delete Top Item</button>
			</div>
						
			<div id="listblock">
				<ul id="todolist"></ul>
			</div>
			
			<p id="logout"><a href="logout.php">Log Out</a></p>
		</div>
		
<?php include("bottom.html"); ?>