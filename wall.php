<?php
include('process.php');

var_dump($_SESSION['commentlog'])
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Email Validator</title>
	<style>
	</style>

</head>
<body>

<p>Welcome <?= $_SESSION['firstname'] ?>!</p>
<form action="process.php" method="post">
<input type="submit" name="Logout" value="logoff">
<input type="hidden" name="action" value="logoff">
</form>
<form action="process.php" method="post">
<textarea rows="4" cols="50" name="message">
Enter a message</textarea>
<input type="submit">
<input type="hidden" name="action" value="addmessage">
</form>

<?php
retrieve_post();

	foreach($_SESSION['messagelog'] as $messages)
	{?>
		<p><?= $messages['message']?> </p>
		<p><?= $messages['first_name']?> <?= $messages['last_name']?> <?= $messages['created_at']?></p>
<?php 
		retrieve_comment($messages['id']);
		foreach($_SESSION['commentlog'] as $comments)
		{ ?>
		<p><?= $comments['comment']?> </p>

	<?php	}
?>
		<form action="process.php" method="post">
			<textarea rows="2" cols="50" name="comment">
			Enter a Comment</textarea>
			<input type="submit">
			<input type="hidden" name="action" value=<?= $messages['id'] ?>>
		</form>

		<hr>
<?php	} ?>

</body>
</html>

