<?php
require_once ('../autoloader.php');

session_start();

$info = '';
// response about errors in registration form

// If user is already loged in, redirects to index.php
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	header('Refresh: ; url= ../index.php');
}

// Reception of signup form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password'])) {
		if (user::loadByEmail($_POST['email'])) {
			$info = "Email is busy";
			header('Refresh: 0; url=login.php');
		} else {
			if (user::loadByUsername($_POST['username'])) {
				$info = "Username is busy";
			} else {

				$userName = stripslashes(htmlspecialchars(trim($_POST['username'])));
				$email = $_POST['email'];

				if (strlen($userName) > 4 && strlen($userName) < 20 && (preg_match('/[A-z0-9.+]+\@[A-z0-9]+\.[a-z]{2,}/m', $email))) {

					$obj1 = new user();
					$obj1 -> setUsername($userName);
					$obj1 -> setEmail($email);
					$obj1 -> setpasswordHash(trim($_POST['password']));
					$obj1 -> save();
					$info = "Registration success<br>";
					header('Refresh: 2; url=login.php');
				} else {
					$info = "Akceptowany login: od 4 do 20 znaków bez polskich liter i spacji lub NIEPOPRAWNY EMAIL!";
				}
			}
		}
	}
} else {
	$info = "Complete all fields";
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
					<ul class="nav navbar-nav"></ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>
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
					<form action="" method="post" role="form" >
						<div class="form-group">
							<label for="username">Username:</label>
							<input type="text" class="form-control" name="username" id="username"
							placeholder="Your username">
							<label for="email">Email:</label>
							<input type="text" class="form-control" name="email" id="email"
							placeholder="Your email">
							<label for="password">Password:</label>
							<input type="password" class="form-control" name="password" id="password"
							placeholder="">
						</div>
						<button type="submit" class="btn btn-success">
							REGISTER
						</button>
					</form>
					<?php
					echo $info;
					?>
				</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
		<?php
		require_once ('../footer.php');
		?>
	</body>
</html>