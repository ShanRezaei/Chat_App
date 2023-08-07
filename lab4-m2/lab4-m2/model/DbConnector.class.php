<?php
/**
	 * Class DbConnector
	 * Handles the connection between our application and the database
*/
class DbConnector {
	const HOST = "localhost";
	const USER = "nkhodapanah_h5298191";
	const PASS = "Herzing_2023";
	const DBNAME = "nkhodapanah_herzing";
	public $db=null;

	public function __construct() {
	//try to connect to the database using the provided credentials
    //if the connection works, it will keep the persistence, else it will throw an error
		try {
	#PDO_MYSQL is a driver that implements the PHP Data Object (PDO) interface to enable access from PHP to MySQL databases.
			$this->db = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::DBNAME, self::USER, self::PASS);

	//show MySql errors
			$this->db->setAttribute( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
	//show character encoding
			$this->db->exec("set names utf8");

		} catch (Exception $e) {
			die("Database Connection Error: " . $e->getMessage());
		}
	}
}
?>