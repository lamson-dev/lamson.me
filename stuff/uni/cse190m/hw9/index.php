<?php

/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
		
	php code for index page
	allows user input username & password to login
	redirects to todolist page if user is already logged in
		
*/

include("top.html"); 
include("shared.php");

start_page_session();
check_logged_in();
set_flash_message();
 
?>
		<div id="main">
			<p>
				The best way to manage your tasks. <br />
				Never forget the cow (or anything else) again!
			</p>

			<p>
				Log in now to manage your to-do list:
			</p>
			
			<form id="loginform" action="login.php" method="post">
				<div><input id="name" name="name" type="text" size="12" autofocus="autofocus" /> <strong>User Name</strong></div>
				<div><input id="password" name="password" type="password" size="12" /> <strong>Password</strong></div>
				<div><input id="submitbutton" type="submit" value="Log in" /></div>
			</form>

			
			<p id="error"></p>
				
		</div>
		
<?php include("bottom.html"); 

/*
	$dom = new DOMDocument();
	$html = "test.html";
	$dom->validateOnParse = true; //<!-- this first
	$dom->loadHTMLFile($html);        //'cause 'load' == 'parse
	$dom->preserveWhiteSpace = false;
	
	$belement = $dom->getElementById("");
	echo $belement->nodeValue;

	$dom = new DOMDocument();
	
	$flash = getElementById("flash");
	
	$p_tag = $dom->createElement("p");
	$p_tag->appendChild($dom->createTextNode($_SESSION["flash"]));
	$flash->appendChild($p_tag);
	unset($_SESSION["flash"]);
*/

?>

