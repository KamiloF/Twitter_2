<?php
require_once ('../autoloader.php');

session_start();

$client = null;
$tweet = null;

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	$client = user::loadById($_SESSION['id']);
} else {
	header('Refresh: 0; url= ../index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!empty($_GET)) {
		$tweet = tweet::loadById($_GET['id']);
		if ($tweet) {
			$_SESSION['tweetPostId'] = $_GET['id'];
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_SESSION['tweetPostId'] && $client) {
		$tweet = tweet::loadById($_SESSION['tweetPostId']);
		if (!empty($_POST['comment'])) {
			$commentText = stripslashes(htmlspecialchars(trim($_POST['comment'])));
			$comment = new comment();
			$comment -> setText($commentText);
			$comment -> setUserId($_SESSION['id']);
			$comment -> setPostId($_SESSION['tweetPostId']);
			$comment -> save();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
		<?php
		require_once ('../head.php');
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
					<a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-home"></span> Twitter</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="profile.php?id=<?php echo $client->getId() ?>"><span class="glyphicon glyphicon-user"></span> Profile</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container">
			<div class="row">
				<div class="col-sm-4 sol-lg-6"></div>
				<div class="col-sm-6 sol-lg-4">
					<?php
if (!empty($tweet)) {
$tweetAuthor = user::loadById($tweet->getUserId());
echo "<table>";
echo "<tr>";
echo "<td> " . $tweetAuthor->getUsername() . "</td>";
echo "<td>: " . $tweetAuthor->getEmail() . " / </td>";
echo "<td>Date: " . $tweet->getCreationDate() . " </td>";
echo "</tr>";
echo "<tr id='text'>";
echo "<td colspan='3'>" . $tweet->getText() . " </td>";
echo "</tr>";
echo "</table>";
					?>
					<div>
						<h4>Comments</h4>
						<?php
						$allComments = comment::loadAllByPostId($_SESSION['tweetPostId']);
						foreach ($allComments as $comment) {
							$author = user::loadById($comment -> getUserId());
							echo "<table class='comments'>";
							echo "<tr>";
							echo "<td>" . $author -> getUsername() . "</td>";
							echo "<td>_Date: " . $comment -> getCreationDate() . " </td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td colspan='3'> " . $comment -> getText() . " </td>";
							echo "</tr>";
							echo "</table>";
						}
						}
						?>
					</div>
				</div>
				<div class="col-sm-2 sol-lg-2"></div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-4 sol-lg-6"></div>
				<div class="col-sm-6 sol-lg-4">
					<?php if ($client) { ?>
					<form action="" method="post" role="form" >
						<label for="comment"><h2>Comment:</h2></label>
						<input type="text" class="form-control" name="comment" id="comment" placeholder="Write comment" maxlength="40">
						<br>
						<button type="submit" class="btn btn-info">
							Send
						</button>
					</form>
					<?php } ?>
				</div>
				<div class="ol-sm-2 sol-lg-2"></div>
			</div>
		</div>
		<?php
		require_once ('../footer.php');
		?>
	</body>
</html>

