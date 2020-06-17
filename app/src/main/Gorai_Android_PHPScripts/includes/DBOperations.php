<?php
	
	class DBOperations
	{
		private $con;

		function __construct()
		{
			require_once dirname(__FILE__).'/DBConnect.php';
			$db = new DBConnect();
			$this->con = $db->connect();
		}

		function isUserExist($F_name, $L_name, $email, $password)
		{

			$stmt = $this->con->prepare("SELECT id FROM userlogintable WHERE F_name = ? and L_name = ? and email = ? and password = ?;");
			$stmt->bind_param("ssss",$F_name,$L_name,$email,$password);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}

		function createUser($F_name,$L_name,$email,$password)
		{
			//$password = md5($pass);
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
    			return 0;
			}
			elseif($this->isUserExist($F_name,$L_name,$email,$password))
			{
				return 1;
			}
			else
			{
				$query = "INSERT INTO userlogintable (F_name, L_name, email, password) values (?, ?, ?, ?);";
				$stmt = $this->con->prepare($query);
				$stmt->bind_param("ssss",$F_name,$L_name,$email,$password);
				if($stmt->execute()){
					return 3;
				}
				else{
					return 2;
				}
			}
			
		}

		function loginUser($email,$password)
		{
			$query = "SELECT id FROM userlogintable WHERE email = ? and password= ?;";
			$stmt = $this->con->prepare($query);
			$stmt->bind_param("ss",$email,$password);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}

		function deleteUser($email)
		{
			$query = "DELETE FROM userlogintable WHERE email = ?;";
			$stmt = $this->con->prepare($query);
			$stmt->bind_param("s",$email);
			$stmt->execute();
			if($stmt->execute()){
				return true;
			}
			else{
				return false;
			}
		}

		function updateUser($F_name,$L_name,$email,$password)
		{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
    			return 0;
			}
			elseif($this->isUserExist($F_name,$L_name,$email,$password))
			{
				return 1;
			}
			else
			{
				$query = "UPDATE userlogintable SET F_name = ?, L_name = ?, password = ? WHERE email = ?;";
				$stmt = $this->con->prepare($query);
				$stmt->bind_param("ssss",$F_name,$L_name,$password,$email);
				if($stmt->execute()){
					return 3;
				}
				else{
					return 2;
				}
			}
			
		}

		function getUserDetails($email)
		{
			$query = "SELECT id, F_name, L_name, email, password FROM userlogintable WHERE email = ?;";
			$stmt = $this->con->prepare($query);
			$stmt->bind_param("s",$email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}
	}
?>