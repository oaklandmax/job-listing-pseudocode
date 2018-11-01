<?php

class db {

	private function open_db(){
			
		define(SERVERNAME, "localhost");
		define(USERNAME, "maxperez");
		define(PASSWORD, "hireme");
		define(DBNAME, "ucjobs");
		define(TABLE, "listings");
	
		// Create connection
		$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error); // this may not be nesc
		} else {
			return $conn;
		}
	}

}