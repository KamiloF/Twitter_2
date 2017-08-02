<?php
require_once ('../autoloader.php');

session_start();

$info = '';

//LogIn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['email']) && !empty($_POST['password'])) {
		$passwordVerify = false;
		$obj = user::loadByEmail($_POST['email']);
		if ($obj) {
			$passwordVerify = password_verify($_POST['password'], $obj -> getPasswordHash());
			if ($passwordVerify === true) {
				$_SESSION['email'] = $obj -> getEmail();
				$_SESSION['id'] = $obj -> getId();
				$_SESSION['username'] = $obj -> getUsername();
			} else {
				$info = "You have entered an incorrect password";
			}
		} else {
			$info = "There is no such user";
		}
	} else {
		$info = "You have not provided login credentials";
	}
}
//Przekierowanie jeśli zalogowany
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	header('Refresh: 2; url= ../index.php');
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
							<a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a>
						</li>
						<li>
							<a href="register.php"><span class="glyphicon glyphicon-log-in"></span> Register</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container">
			<div class="row">
				<div class="col-sm-4"></div>
				<div class="col-sm-4 ">
					<form action="" method="post" role="form">
						<div class="form-group">
							<label for="email">Email:</label>
							<input type="text" class="form-control" name="email" id="email"
							placeholder="Your email">
							<label for="password">Password:</label>
							<input type="password" class="form-control" name="password" id="password"
							placeholder="">
						</div>
						<button type="submit" class="btn btn-info">
							LOG IN
						</button>
						<?php echo $info ?>
					</form>
				</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
		<?php
		require_once ('../footer.php');
		?>
	</body>
</html>

