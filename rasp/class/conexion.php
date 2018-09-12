<?php

	class Conexion{

		private $user="";//INGRESAR INFORMACION DE LA BASE DE DATOS. USUARIO, CLAVE, IP, NOMBRE DB
		private $pass="";
		private $host="";
		private $dataBase="";
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