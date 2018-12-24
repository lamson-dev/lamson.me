<?php
/*
	Son Nguyen
	CSE 190 M
	Homework #9

	TA: Rishi Goutam		
		
	This web service accepts a query parameter 'todolist' OR don't accept any
	when no parameter is passed ~> it searches list.json
	and outputs the user todolist data on in JSON format

	when 'todolist' is passed -> creates/updates list.json with user todolist
			
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$todolist = get_parameter("todolist");
	file_put_contents("list.json", $todolist);
} else { 

	$jsontext = file_get_contents("list.json");
	header("Content-type: application/json");
	if ($jsontext == false) {
		print "";
	} else {
		print $jsontext;
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

/*
	example of JSON data
	{
	    "items": [
	        "Buy a pet jellyfish!",
	        "build an elaborate sculpture out of toothpicks and Swiss cheese",
	        "add &lt;em&gt; tags to my web page",
	        "buy Ben &amp; Jerry's \"large\" size ice cream."
	    ]
	}
*/


?>