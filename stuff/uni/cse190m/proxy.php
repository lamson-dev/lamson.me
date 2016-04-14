<?php
	ini_set("display_errors", "1");
	error_reporting(E_ALL | E_NOTICE | E_WARNING);
	
	// proxy.php
	// A script to proxy HTTP requests to given URL.
	// 21 Apr 2007
	// Marty Stepp
	// Morgan Doocy
	// For CSE 190M
	
	// Note: 'url' parameter MUST adhere to RFC 1378. Its query string, if
	// present, must be encoded (e.g., with urlencode()), meaning any encoded
	// characters in a query value will be double-encoded.
	
	if (!isset($_GET['url']) || $_GET['url'] == "") {
		die_header(400, "URL not specified.");
	}
	
	$url = $_GET['url'];   # "http://webster.cs.washington.edu/cse190m/babynames.php?";
	$param_count = 1;
	foreach ($_GET as $key => $value) {
		if ($key == "url") { continue; }
		if ($param_count == 1) {
			$url .= "?";
		} else {
			$url .= "&";
		}
		$url .= urlencode($key) . "=" . urlencode($value);
		$param_count++;
	}
	
	// parse url
	$count = preg_match("/([^:]*):\/\/([^\/:]*):?([^\/]*)(\/.*)/", $url, $matches);
	if (!$count) {
		die_header(500, "Non-matching URL: $url");
	}
	$protocol = $matches[1];
	$host = $matches[2];
	$port = $matches[3];
	$path = $matches[4];
	switch ($protocol) {
		case 'http':
			$protocol = 'tcp';
			$port = $port ? $port : 80;
			break;
			
		case 'https':
			$protocol = 'ssl';
			$port = $port ? $port : 443;
			break;
			
		default:
			die_header(405, "Protocol not allowed: $protocol");
	}
	
	// open socket and send request
	$sock = @fsockopen("{$protocol}://{$host}", $port, $errno, $errstr);
	if (!$sock) {
		die_header(500, "Connection to {$protocol}://{$host}:{$port} failed: [$errno] $errstr");
	}
	fwrite($sock, "GET $path HTTP/1.1\r\n");
	fwrite($sock, "Host: $host\r\n");
	fwrite($sock, "Connection: Close\r\n");
	fwrite($sock, "Accept: {$_SERVER['HTTP_ACCEPT']}\r\n");
	fwrite($sock, "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n");
	if (isset($_SERVER['HTTP_REFERER'])) {
		fwrite($sock, "Referer: {$_SERVER['HTTP_REFERER']}\r\n");
	}
	fwrite($sock, "\r\n");
	
	// handle response
	$headers = array();
	while (!feof($sock)) {
		$line = fgets($sock);
		if ($line == "\r\n") {
			// reached end of headers; forward them on using header()
			foreach ($headers as $header) {
				header($header);
			}
			// now forward content
			while (!feof($sock)) {
				echo fgets($sock);
			}
		} else {
			array_push($headers, trim($line));
		}
	}
	fclose($sock);
	
	// die gracefully sending the given HTTP error code and message
	function die_header($code, $msg) {
		switch ($code) {
			case 500:
				header("HTTP/1.1 500 Internal Server Error");
				break;
				
			case 405:
				header("HTTP/1.1 405 Method Not Allowed");
				break;

			case 404:
				header("HTTP/1.1 404 Not Found");
				break;
				
			case 400:
				header("HTTP/1.1 400 Bad Request");
				break;
		}
		header("Content-Type: text/plain");
		echo $msg;
		
		// clean up and exit
		global $sock;
		if ($sock) {
			fclose($sock);
		}
		exit();
	}
?>