<?php

require_once '../includes/DBOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST')
{
	if(isset($_POST['F_name']) and isset($_POST['L_name']) and 
		isset($_POST['email']) and isset($_POST['password']))
	{
		$db = new DBOperations();
		$result = $db->updateUser($_POST['F_name'], $_POST['L_name'], $_POST['email'], $_POST['password']);
		if($result == 3)
		{
			$userDetails = $db->getUserDetails($_POST['email']);
			$response['error'] = false;
			$response['message'] = "User Details Updated Successfully!";
			$response['id'] = $userDetails['id'];
			$response['F_name'] = $userDetails['F_name'];
			$response['L_name'] = $userDetails['L_name'];
			$response['email'] = $userDetails['email'];
			$response['password'] = $userDetails['password'];
		}
		elseif($result == 0)
		{
			$response['error'] = true;
			$response['message'] = "Invalid Email ID";
		}
		elseif($result == 1)
		{
			$response['error'] = true;
			$response['message'] = "Email Already Exists";
		}
		elseif($result == 2)
		{
			$response['error'] = true;
			$response['message'] = "Something went wrong, Try Again :/";
		}
	}
	else
	{
		$response['error'] = true;
		$response['message'] = "Required fields are missing";
	}
}
else
{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo json_encode($response);
?>