<?php

define('DB_HOST', '159.203.124.185');
define('DB_USER', 'project');
define('DB_PASS', 'goeagles');
define('DB_NAME', 'blog');

$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('Failed to connect to MySQL Database');
?>
<p>Searching database for list of all registered userID</p>
<?php
$result = $mysqli->query("SELECT userid FROM users");
$id = 1;
while ($row = $result->fetch_assoc()) {
	echo "Record ".$id." :: UserID:".$row['userid'] . "<br/>";
	$id++;
}