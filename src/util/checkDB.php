<?php

require_once ('db.php');

$db = new db();

if ($db -> conn == null) {
	header('Refresh: 0; url= database/makeDatabase.php');
	exit ;
}
if ($db -> changeDB('twitter') == false) {
	header('Refresh: 0; url= database/makeDatabase.php');
	exit ;
}
