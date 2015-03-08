<?php
session_start();
include('new-connection.php');
$post = $_POST;
$_SESSION['$errors']='';


if(isset($_POST['action']) && $_POST['action']=='login')  //This redirects post to function. Post must have a hidden value action to login correlation
{
	userlogin($post);
}

if(isset($_POST['action']) && $_POST['action']=='registration')
{
	userreg($post);
}

if(isset($_POST['action']) && $_POST['action']=='logoff')
{
	session_destroy();
	header('location: index.php');
	exit;
}

if(isset($_POST['action']) && $_POST['action']=='addmessage')
{
	wall_message($post);
}

if(isset($_POST['action']) && $_POST['action']>=0)
{	
	wall_comment($post);
}

if(isset($_POST['delete']) && $_POST['delete']>=0)
{	
	delete_message($post);
}

function wall_message($post)  //Takes the input $post and inserts a message into the message table
{
	$query1= "INSERT INTO messages (user_id,message,created_at,updated_at) VALUES ('{$_SESSION['user_id']}','{$post['message']}',NOW(),NOW())";
	run_mysql_query($query1);
	header('location: wall.php');
	exit;
}

function wall_comment($post) //Takes the input $post and inserts a comment into the comment table
{
	$query1= "INSERT INTO comments (message_id,user_id,comment,created_at,updated_at) VALUES ('{$_POST['action']}','{$_SESSION['user_id']}','{$post['comment']}',NOW(),NOW())";
	run_mysql_query($query1);
	header('location: wall.php');
	exit;
}

function retrieve_post() //runs query to load messages
{
	$query2 = "SELECT messages.id, messages.message, messages.user_id, messages.created_at, users.first_name, users.last_name 
				FROM messages, users 
				WHERE users.id = messages.user_id
				and messages.deleted_at IS NULL";
	$_SESSION['messagelog'] = array_reverse(fetch_all($query2));
}

function retrieve_comment($messageid) //runs query to load comments
{
	$query = "SELECT comments.comment, users.first_name, users.last_name, comments.created_at
				FROM comments, users
				WHERE comments.message_id = $messageid
				AND users.id = comments.user_id";
	$_SESSION['commentlog'] = array_reverse(fetch_all($query));
}


function emailrepeat($post)  //I think this is broken. It's supposed to make sure that emails don't repeat
{

	$query = "SELECT email FROM users";
	$emaillist= fetch_all($query);

	foreach($emaillist as $emails)
	{
		if($post['email'] == $emails['email'])
			{return true;}
		else {return false;}
	}
}


function userlogin($post)
{
	$query="SELECT * FROM users WHERE users.password = '{$_POST['password']}' AND users.email = '{$_POST['email']}'";

	$user=fetch_all($query);

	if(count($user)>0)
	{
		$_SESSION['firstname']= $user[0]['first_name'];
		$_SESSION['lastname']= $user[0]['last_name'];
		$_SESSION['email']= $post['email'];
		$_SESSION['user_id']= $user[0]['id'];
		header('location: wall.php');
		exit;
	}

	else
	{
		$_SESSION['$errors'][] = "<p>Cannot find user login inforamtion. Please try again</p>";
		header('location: index.php');
		exit;

	}

}

function userreg($post)
{
////Validation
	emailrepeat($post);

	if(empty($_POST['firstname']))
	{
		$_SESSION['$errors'][] = "<p>First name cannot be blank</p>";
	}

	if(empty($_POST['lastname']))
	{
		$_SESSION['$errors'][] = "<p>Last name cannot be blank</p>";
	}

	if(empty($_POST['email']))
	{
		$_SESSION['$errors'][] = "<p>Email cannot be blank</p>";
	}

	if(empty($_POST['password']))
	{
		$_SESSION['$errors'][] = "<p>Password cannot be blank</p>";
	}

	if($_POST['password'] !== $_POST['confirm'])
	{
		$_SESSION['$errors'][] = "<p>Passwords do not match</p>";
	}

	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{	
		$_SESSION['$errors'][] = "<p>Email address must be valid</p>";
	}

	if(emailrepeat($post))
	{
		$_SESSION['$errors'][] = "<p>Email is already in use</p>";
	}

	if(!empty($_SESSION['$errors']))
	{	
		header('location: index.php');
		exit;
	}

	else{ //If validation is successful, insert user input into database
		$query= "INSERT INTO users (first_name,last_name,email,password,created_at,updated_at) VALUES ('{$post['firstname']}','{$post['lastname']}','{$post['email']}','{$post['password']}',NOW(),NOW())";

		run_mysql_query($query);

		userlogin($post);
	}
}

function delete_message($post)
{
	$query1= "UPDATE `wall`.`messages` SET deleted_at = NOW() WHERE {$post['delete']} = id";
	run_mysql_query($query1);
	header('location: wall.php');
}

function delete_comment($post)
{
	$query1= "UPDATE `wall`.`messages` SET deleted_at = NOW() WHERE {$post['delete']} = id";
	run_mysql_query($query1);
	header('location: wall.php');
}






