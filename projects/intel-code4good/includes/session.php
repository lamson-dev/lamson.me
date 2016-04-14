<?php

require_once('init.php');

class Session {
	private $logged_in = false;
	public $user_id;

	function __construct() {
		session_start();
		$this->check_login();
	}
	
	public function is_logged_in() {
		return $this->logged_in;	
	}
	
	public function login($username, $password) {
		global $db;

		$query = sprintf("SELECT 'id', 'password' FROM users WHERE username = '%s'", $username);
		echo $query;
		$result = $db->query($query);

		echo "printing results";
		echo $result;
		$user = mysql_fetch_assoc($result);
			
		if(isset($user)) {
			if(encrypt_password($password) == $user['password'])
			{
				$this->user_id = $_SESSION['user_id'] =  $user['id'];
				$this->logged_in = true;
				$expire=time()+60*60*24*30;
				if(!isset($_COOKIE['USERNAME_COOKIE'])){
					setcookie('USERNAME_COOKIE', $username, $expire, "/");
					setcookie('PASSWORD_COOKIE', $password, $expire, "/");
				}
			}
		}
	}
	
	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$past = time() - 3600;
		setcookie('USERNAME_COOKIE', "", time() - 3600, "/");	
		setcookie('PASSWORD_COOKIE', "", time() - 3600, "/"); 
		$this->logged_in = false;
	}
	
	private  function check_login() {
		if(isset($_SESSION['user_id'])) {
		 	$this->user_id = $_SESSION['user_id'];
		 	$this->logged_in = true;
		} elseif(isset($_COOKIE['USERNAME_COOKIE']) && isset($_COOKIE['PASSWORD_COOKIE'])){ 
			$this->login($_COOKIE['USERNAME_COOKIE'], $_COOKIE['PASSWORD_COOKIE']);
		} else {
		 	unset($this->user_id);
		 	$this->logged_in = false;
		}
	}	
}

?>