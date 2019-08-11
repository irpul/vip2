<?php
class Database extends PDO {
	
	private $db_host    = "localhost";  // server name
	private $db_user    = "root";       // user name
	private $db_pass    = "";           // password
	private $db_dbname  = "mikrotik";           // database name
	private $db_charset = "utf8";           // optional character set (i.e. utf8)
	private $db_pcon    = false;        // use persistent connection?
	
	
	public $last_query;
	
	function __construct($connect = true, $database = null, $server = null,
  			$username = null, $password = null, $charset = null , $option = null) {
  		
		if ($database !== null) $this->db_dbname  = $database;
  		if ($server   !== null) $this->db_host    = $server;
  		if ($username !== null) $this->db_user    = $username;
  		if ($password !== null) $this->db_pass    = $password;
  		if ($charset  !== null) $this->db_charset = $charset;
  		
	  	if (strlen($this->db_host) > 0 && strlen($this->db_user) > 0) {
  			if ($connect) $this->Open($option);
  		}
  		
  	}

  	public function query($statement)
  	{
  		$this->last_query = $statement;
  		return parent::query($statement);
  	}
  	public function exec($statement)
  	{
  		$this->last_query = $statement;
  		return parent::exec($statement);
  	}
  	
  	public function open($options) {
  		parent::__construct ( "mysql:host={$this->db_host};dbname={$this->db_dbname}", $this->db_user, $this->db_pass, $options );
  		parent::query("SET NAMES utf8");
  		parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
  		parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	}
	
	
	public function close_connection() {
		unset($this->connection);
	}
	
}
?>