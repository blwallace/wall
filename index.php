<?php
session_start();

?>

<html>
<head>
	<title>Login</title>
</head>
<body>

<?php


if (!empty($_SESSION['$errors']))  //If there are errors in registration, display errors
{
	foreach($_SESSION['$errors'] as $error) 
		{echo $error;}

	$_SESSION['$errors']='';  //Sets errors to an emtpy array in case user refreshed page

}
else{ $_SESSION['$errors']='';} //Creates errors variable for session
?>

<form action="process.php" method="post">
	<h3>Login</h3>
	<p>Login: <input type="text" name="email"></p>
	<p>Password: <input type="password" name="password"></p>
	<input type="submit" name="submit">
	<input type="hidden" name="action" value="login">
</form>

<form action="process.php" method="post">
	<h3>Register</h3>
	<p>First Name: <input type="text" name="firstname"></p>
	<p>Last Name: <input type="text" name="lastname"></p>
	<p>Login Email: <input type="text" name="email"></p>
	<p>Password: <input type="password" name="password"></p>
	<p>Confirm Password: <input type="password" name="confirm"></p>	
	<input type="submit" name="submit">
	<input type="hidden" name="action" value="registration">
</form>

</body>
</html>