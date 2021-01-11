<?php
class DB extends MySQLi {
	private static $instance = null;

	// private static $_host = "localhost";
	// private static $_uname = "mjtren5_user2";
	// private static $_pass = "XLgf;E0uWz_#";
	// private static $_database = "mjtren5_mjtrends";

	private static $_host = "localhost";
	private static $_uname = "root";
	private static $_pass = "";
	private static $_database = "mjtren5_mjtrends";

	private function __construct($host, $user, $password, $database){
		parent::__construct($host, $user, $password, $database);
	}

	public static function getInstance(){
		if (self::$instance == null){
			self::$instance = new self(self::$_host, self::$_uname, self::$_pass, self::$_database);
		}
		return self::$instance;
	}
}