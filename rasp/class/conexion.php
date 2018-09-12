<?php

	class Conexion{

		private $user="postgres";
		private $pass="postgres";
		private $host="localhost";
		private $dataBase="VOGUE_ERP-BD-2018-02-28";
		private $dbconn;

		public function __construct(){
			$this->setConnection();			
		}

		public function setConnection(){
			$conn_string = "host=" . $this->host . " dbname=" . $this->dataBase . " user=" . $this->user . " password=" . $this->pass;
			$this->dbconn = pg_connect($conn_string) or die('Could not connect: ' . pg_last_error());
		}

		public function closeConnection(){
			pg_close($this->dbconn);
		}
	}
?>