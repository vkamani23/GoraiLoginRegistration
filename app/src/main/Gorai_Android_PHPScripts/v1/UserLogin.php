<?php

require_once '../includes/DBOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST')
{
	if(isset($_POST['email']) and isset($_POST['password']))
	{
		$db = new DBOperations();
		if($db->loginUser(
			$_POST['email'],
			$_POST['password']))
			{
				$userDetails = $db->getUserDetails($_POST['email']);
				$response['error'] = false;
				$response['message'] = "User Logged In Successfully!";
				$response['id'] = $userDetails['id'];
				$response['F_name'] = $userDetails['F_name'];
				$response['L_name'] = $userDetails['L_name'];
				$response['email'] = $userDetails['email'];
				$response['password'] = $userDetails['password'];
			}
			else
			{
				$response['error'] = true;
				$response['message'] = "Invalid Username or Password!\nPlease Try Again :/";
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