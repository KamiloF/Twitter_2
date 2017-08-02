<?php
require_once ('../autoloader.php');

session_start();

$client = false;
$message = null;

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	$client = user::loadById($_SESSION['id']);
} else {
	header('Refresh: 0; url= ../index.php');
	exit ;
}

// Setting of message to show
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!empty($_GET['id'])) {
		$message = message::loadById($_GET['id']);
		$sender = user::loadById($message -> getSenderId());
		$receiver = user::loadById($message -> getReceiverId());
		if (!empty($message -> getId())) {
			$_SESSION['messageId'] = $message -> getId();
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['mess'])) {
		$messTx = stripslashes(htmlspecialchars(trim($_POST['messageText'])));
		$to = $_POST['messageTo'];
		$messageTo = user::loadByUserName($to);
		$messageText = new message();
		$messageText -> setText($messTx);
		$messageText -> setReceiverId($messageTo -> getId());
		$messageText -> setSenderId($client -> getId());
		$messageText -> save();
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
                        <li><a href="settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Message</h3>
                    <form action="" method="post" role="form" >
                        <label>Enter the recipient's name or click on its name below</label>
                        <input type="text" class="form-control" name="messageTo" id="messageTo"
                               placeholder="User Name" maxlength="20"><br>
                        <input type="text" class="form-control" name="messageText" id="messageText"
                               placeholder="Write message" maxlength="255"><br> 
                        <button type="submit" class="btn btn-info" name="mess" style="width: 100px">Send</button>
                    </form>
                </div>
                <div class="col-sm-6">
                </div>
                <?php
				//All messages view
				if (!empty($_SESSION['messageId']) && !empty($message)) {
					echo "<h6>From " . $sender -> getUsername() . " To : " . $receiver -> getUsername() . "</h6><br>";
					echo "<h2>" . $message -> getText() . "</h2>";
					$message -> setRead(1);
					$message -> save();
					$_SESSION['messageId'] = null;
				}
                ?>
            </div>
        </div>                
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <?php
					if ($client) {
						echo "<h4>Received messages: </h4><br>";
						echo "<table>";
						echo 'From: ';
						$allMessages = message::loadAllByUserId($client -> getId(), 1);
						foreach ($allMessages as $mess) {
							$sender = user::loadById($mess -> getSenderId());
							$isRead = ($mess -> getRead() == 1) ? '' : '_Unread_';
							echo "<tr>";
							echo "<td><a href='profile.php?id=" . $mess -> getSenderId() . "'>" . $sender -> getUsername() . "</a>:</td>";
							echo "<td>$isRead</td>";
							echo "<td><a href='messages.php?id=" . $mess -> getId() . "'>" . substr($mess -> getText(), 0, 30) . "</a></td>";
							echo "</tr>";
						}
						echo "</table>";
					}
                    ?>        
                </div>
                <div class="col-sm-1 ">
                </div>
                <div class="col-sm-5">
                    <?php
					if ($client) {
						echo "<h4>Messages sent: </h4><br>";
						echo "<table>";
						echo 'To: ';
						$allMessages = message::loadAllByUserId($client -> getId());
						foreach ($allMessages as $mess) {
							$receiver = user::loadById($mess -> getReceiverId());
							echo "<tr>";
							echo "<td><a href='profile.php?id=" . $mess -> getReceiverId() . "'>" . $receiver -> getUsername() . "</a>:</td>";
							echo "<td><a href='messages.php?id=" . $mess -> getId() . "'>" . substr($mess -> getText(), 0, 30) . "</a></td>";
							echo "</tr>";
						}
						echo "</table>";
					}
                    ?> 
                </div>
            </div>
        </div>
        <?php
		require_once ('../footer.php');
        ?>
    </body>
</html

