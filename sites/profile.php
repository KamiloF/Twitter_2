<?php
require_once ('../autoloader.php');

session_start();

$client = null;
$info = '';

//Przekierowanie jeśli zalogowany
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	$client = user::loadById($_SESSION['id']);
} else {
	header('Refresh: 0; url= ../index.php');
	exit ;
}

//obj z GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!empty($_GET['id'])) {
		$user = user::loadById($_GET['id']);
		if (!empty($user)) {
			$_SESSION['receiverId'] = $user -> getId();
		} else {
			$info = 'No user with given id';
		}
	}
}

//message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['message'])) {
		$messageText = stripslashes(htmlspecialchars(trim($_POST['message'])));
		$message = new message();
		$message -> setReceiverId($_SESSION['receiverId']);
		$message -> setSenderId($client -> getId());
		$message -> setText($messageText);
		$result = $message -> save();
		if ($result == true) {
			$_SESSION['send'] = true;
			header("Refresh:0 url=messages.php");
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
                        <li class="active"><a href="profile.php?id=<?php echo $client->getId() ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($client) { ?>
                            <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> <?php echo $client -> getUsername(); ?></a></li>
                        <?php } ?>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        <li><a href="messages.php"><span class="glyphicon glyphicon-envelope"></span> Messages</a></li>
                        <li><a href="settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6 ">
                    <?php
					echo $info;
					//tweety użytkownika
					if (!empty($user)) {
						echo "<h3>User " . $user -> getUsername() . "</h3>";
						echo "<h3>Email: " . $user -> getEmail() . "</h3>";
						$allTweets = tweet::loadAllByUserId($user -> getId());
						foreach ($allTweets as $tweet) {
							echo "<a href=tweet.php?id=" . $tweet -> getId() . ">";
							echo "<table>";
							echo "<tr>";
							echo "<td> " . $user -> getUsername() . " </td>";
							echo "<td>: " . $user -> getEmail() . " / </td>";
							echo "<td>Date: " . $tweet -> getCreationDate() . " </td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td colspan='3'> " . $tweet -> getText() . " </td>";
							echo "</tr>";
							echo "</table>";
							echo "</a>";
						}
					}
                    ?>        
                </div>
                <div class="col-sm-3">
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                    </div>
                    <div class="col-sm-6 ">
                        <?php
                        //form message
                        if (!empty($user)) {
                            if ($user->getId() != $client->getId() && $user->getId() != null) {
                        ?>
                                <form action="" method="post" role="form" >
                                    <label for="message"><h2>Message</h2></label>
                                    <input type="text" class="form-control" name="message" id="message" placeholder="Write message"><br>                  
                                    <button type="submit" class="btn btn-info">Send</button>
                                </form>
                        <?php
						}
						}
                        ?>
                    </div>
                    <div class="col-sm-3">
                    </div>
                </div>
            </div>
            <?php
			require_once ('../footer.php');
            ?>
    </body>
</html>
