<?php
include('process.php');

if(!isset($_SESSION['email']))  //User cannot visit page unless logged in
{
	$_SESSION['$errors'][] = "<p>Please login</p>";
		header('location: index.php');
		exit;
}
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The Wall</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="wall.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
</head>
<body>

<div class="container-fluid">
<div class="row">
<div class="col-md-3"></div>

<div class="col-md-6">

<h3>Welcome <?= $_SESSION['firstname'] ?> <?=$_SESSION['lastname'] ?>!</h3>
	
	
	<form action="process.php" method="post" class="logout">
		<input type="submit" name="Logout" value="logoff" class="logout">
		<input type="hidden" name="action" value="logoff">
	</form>
<!-- LOG OUT -->


<!-- POST MESSAGE -->
<form action="process.php" method="post">
<textarea rows="3" cols="75" name="message"></textarea>
<input type="submit" class="submit">
<input type="hidden" name="action" value="addmessage">
</form>
</div>

<div class="col-md-3"></div>
</div>


<?php
retrieve_post(); //RUNS QUERY FOR MESSAGE BOARD. DOES NOT RETRIEVE COMMENTS
	foreach($_SESSION['messagelog'] as $messages)
	{?>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class ="message">
					<div class="post">
						<p><strong><?= $messages['first_name']?> <?= $messages['last_name']?> <?= $messages['created_at']?></strong></p> <!-- DISPLAYS ORIGINAL NAME -->
						<p><?= $messages['message']?></p> 
				<?php
					if($messages['user_id'] == $_SESSION['user_id'])
						{?>
							<form action="process.php" method="post">
								<input type="submit" value="delete" class="delete2">
								<input type="hidden" name="delete" value=<?= $messages['id'] ?>>
							</form>
				<?php 
						}?>
					</div>
				
				<!-- DISPLAYS COMMENTS -->
		<?php
		retrieve_comment($messages['id']);

			foreach($_SESSION['commentlog'] as $comments)
			{?>
				<div class="comment">
					<p><strong><?= $comments['first_name']?> <?= $comments['last_name']?> <?= $comments['created_at']?></strong></p>
					<p><?= $comments['comment']?></p>
				<?php
					if($comments['user_id'] == $_SESSION['user_id'])
						{?>
							<form action="process.php" method="post">
								<input type="submit" value="delete" class="delete">
								<input type="hidden" name="deletec" value=<?= $comments['id'] ?>>
							</form>
				<?php 
						}?>
				</div>	
		<?php
			} ?>
				<!-- AREA TO ENTER COMMENTS FOR A MESSAGE. -->
				<form action="process.php" method="post">
					<textarea rows="2" cols="70" name="comment"></textarea>
					<input type="submit" class="submit">
					<input type="hidden" name="action" value=<?= $messages['id'] ?>>
				</form>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>	

<?php	
	} ?>
</div>
</div>
</div>

</body>
</html>

