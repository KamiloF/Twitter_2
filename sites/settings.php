<?php
require_once ('../autoloader.php');

session_start();

$editToolbar = false;
$info = '';

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	$client = user::loadById($_SESSION['id']);
} else {
	header('Refresh: 0; url= ../index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($client)) {
	if (!empty($_POST['password'])) {
		$passwordVerify = password_verify($_POST['password'], $client -> getPasswordHash());
		if ($passwordVerify === true) {
			if ($_POST['delete'] === 'yes') {
				$client -> delete();
				session_destroy();
				header('Refresh: 0; url= ../index.php');
				exit ;
			}
			if (!empty($_POST['email']) && (preg_match('/[A-z0-9.+]+\@[A-z0-9]+\.[a-z]{2,}/m', $_POST['email']))) {
				$client -> setEmail($_POST['email']);
				$info .= 'Email changed';
			}
			if (!empty($_POST['username']) && strlen($_POST['username']) > 4 && strlen($_POST['username']) < 20) {
				$userName = stripslashes(htmlspecialchars(trim($_POST['username'])));
				$client -> setEmail($userName);
				$info .= 'Username changed';
			}
			if (!empty($_POST['newpassword'])) {
				$client -> setPasswordHash(trim($_POST['newpassword']));
				$info .= 'Password changed';
			}
		}
	}
	$client -> save();
	if ($client -> save() == false) {
		$info = "Something went wrong!";
	}
	$_SESSION['email'] = $client -> getEmail();
	$_SESSION['id'] = $client -> getId();
	$_SESSION['username'] = $client -> getUsername();
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
                        <li class="active"><a href="profile.php?id=<?php echo $client->getId() ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($client) { ?>
                            <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> <?php echo $client -> getUsername(); ?></a></li>
                        <?php } ?>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        <li><a href="messages.php"><span class="glyphicon glyphicon-envelope"></span> Messages</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-6 ">
                    <?php
                    if (!empty($client)) {
                    ?>
                        <h2>Data change</h2>
                        <form action="" method="post" role="form" >
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Your email">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Your username">
                                <label for="newpassword">New Password:</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New password"> 
                                <label for="password">Actual Password:</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Actual password">
                                <label for="delete">Do you want to delete account? :</label><br>
                                <select id="delete" name="delete">
                                    <option value="no" defoult>No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Send</button>
                        </form>
                    <?php
							}
							echo $info;
                    ?>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
        </div>
        <?php
		require_once ('../footer.php');
        ?>
    </body>
</html>