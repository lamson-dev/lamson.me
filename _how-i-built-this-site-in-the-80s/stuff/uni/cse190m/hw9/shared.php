<?php
/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
		
	This php file contains all php functions for Remember The Cow page		
*/

# Start session for current page
function start_page_session() {
	if (!isset($_SESSION)) { session_start(); }
}

# Return TRUE if given password is correct password for this user name
function is_correct_password($name, $pw) {
	if ($name == "koojin" && $pw == "12345") {
		return TRUE;
	} else {
		return FALSE;
	}
}

# Redirects current page to the given URL and optionally sets flash message
function redirect($url, $flash_message = NULL) {
	
	if ($flash_message) {
		if (!isset($_SESSION)) { session_start(); }
		$_SESSION["flash"] = $flash_message;
	}
	header("Location: $url");
	die();
}

# Redirects current page to index.php if user is not loged in
function ensure_logged_in() {
	if (!isset($_SESSION["name"])) {
		redirect("index.php", "You must log in first");
	} 
}

# Redirects current page to todolist.php if user is already loged in
function check_logged_in() {
	if (isset($_SESSION["name"])) {
		redirect("todolist.php");
	}
}

# Returns the value of the given query parameter.
# If the parameter has not been passed, issues an HTTP 400 error.
function get_parameter($name) {
	if (isset($_POST[$name])) {
		return $_POST[$name];
	} else {
		header("HTTP/1.1 400 Invalid Request");
		die("HTTP/1.1 400 Invalid Request - you forgot to pass a '$name' parameter.");
	}
}

function set_flash_message() {
	if (isset($_SESSION["flash"])) {
		#temporary message across page redirects
		?>
		<div id="flash"> <?= $_SESSION["flash"] ?> </div>
		<?php
		unset($_SESSION["flash"]);
	} 

}

/*
function getElementById($id)
{
    $xpath = new DOMXPath($this->domDocument);
    return $xpath->query("//*[@id='$id']")->item(0);
}
*/


?>