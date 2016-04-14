<?php
require_once('init.php');

class Database {
		
		private $connection;
		
		function __construct() {
			$this->openConnection();
		}
			
		public function openConnection() {	
			$this->connection = mysql_connect("localhost", "lamson5_dummy", "whocares");
			if(!$this->connection) {
				die("Sorry! Database connection failed " . mysql_error());	
			} else {
				$db_select = mysql_select_db("lamson5_test", $this->connection);	
				if (!$db_select) {
					die("Database selection failed: " . mysql_error());
				}	
			}
		}
		
		public function closeConnection() {
			if(isset($this->connection)) {
				mysql_close($This->connection);
				unset($this->connection);
			}
		}
		
		public function query($query) {
			return mysql_query($query) or die(mysql_error());			
		}
		
		public function escape_value( $value ) {
			if(get_magic_quotes_gpc()) { 
				$value = stripslashes( $value ); 
			}
			
			return mysql_real_escape_string( $value );
		}
}

?>