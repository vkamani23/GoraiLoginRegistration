<?php
	
	class DBConnect
	{
		private $con;
		
		function __construct()
		{

		}

		function connect()
		{
			define("DB_SERVER", "localhost");
			define("DB_USER", "root");
			define("DB_PASSWORD", "");
			define("DB_NAME", "android_db");

			$this->con = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);

			if(mysqli_connect_errno())
			{
				echo "Failed to connect to DataBase ".mysqli_connect_err();
			}

			return $this->con;
		}
	}
?>