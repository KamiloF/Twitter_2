<?php
require_once ('src/util/checkDB.php');

require_once ('autoloader.php');

session_start();

$client = null;

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	$client = user::loadById($_SESSION['id']);
}

if ($client != null) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!empty($_POST['tweet'])) {
			$tweetText = stripslashes(htmlspecialchars(trim($_POST['tweet'])));
			$tweet = new tweet();
			$tweet -> setText($tweetText);
			$tweet -> setUserId($_SESSION['id']);
			$tweet -> save();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
		<?php
			require_once ('head.php');
		?>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="sr-only">Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> Twitter</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<?php if ($client != null) { ?>
						<li class="active"><a href="sites/profile.php?id=<?php echo $client->getId() ?>">Profile</a></li>
						<?php } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if (!$client) { ?>
						<li><a href="sites/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
						<li><a href="sites/register.php"><span class="glyphicon glyphicon-log-in"></span> Register</a></li>
						<?php } else { ?>
						<li><a href="sites/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						<li><a href="sites/messages.php"><span class="glyphicon glyphicon-envelope"></span> Messages</a></li>
						<li><a href="sites/settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</nav>
		<?php
			if ($client != null) {
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-8 ">
					<form action="" method="post" role="form" >
						<label for="tweet"><h2>Tweet:</h2></label>
						<input type="text" maxlength="140" class="form-control" name="tweet" id="tweet" placeholder="Write tweet"><br>
						<button type="submit" class="btn btn-info">Send</button><br><br>
					</form>
				</div>
				<div class="col-sm-2"></div>
			</div>
		</div>
		<?php } ?>
		<div class="container">
			<div class="row">
				<div class="col-sm-2"></div>
				<div class="col-sm-8 ">
					<?php
						if ($client != null) {
							$allTweets = tweet::loadAll();
							foreach ($allTweets as $tweet) {
								$user = user::loadById($tweet->getUserId());

								echo "<table>";
								echo "<tr>";
								echo "<td><a href='sites/profile.php?id=" . $user->getId() . "'>" . $user->getUsername() . "</a></td>";
								echo "<td>: ".$user->getEmail()." / </td>";
								echo "<td>Date: " . $tweet->getCreationDate() . " </td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td colspan='3'>" . "<a href=sites/tweet.php?id=" . $tweet->getId() . ">" . $tweet->getText() . "</a></td>";
								echo "</tr>";
								echo "</table>";
								}
						} else {
					?>
						<h3>Nie jesteś zalogowany, <a href = "sites/login.php">zaloguj się</a> lub <a href = "sites/register.php">załóż konto</a></h3>
					<?php } ?>
				</div>
				<div class="col-sm-2">
				</div>
			</div>
		</div>
		<?php
			require_once ('footer.php');
		?>
	</body>
</html>
