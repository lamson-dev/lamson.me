<?php

function encrypt_password($password) {
	return hash("sha256", $password);
	//return hash_hmac("sha256", $password, $site_key); //sha-256 password	
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

?>