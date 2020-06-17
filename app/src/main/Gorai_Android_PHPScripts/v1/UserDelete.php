<?php

require_once '../includes/DBOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST')
{
	if(isset($_POST['email']))
	{
		$db = new DBOperations();
		if($db->deleteUser($_POST['email']))
		{
			$response['error'] = false;
			$response['message'] = "User Deleted Successfully!";
		}
		else
		{
			$response['error'] = true;
			$response['message'] = "Invalid Request!\nPlease Try Again :/";
		}
	}
	else
	{
		$response['error'] = true;
		$response['message'] = "Missing Username or Password";
	}
}
else
{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo json_encode($response);
?>