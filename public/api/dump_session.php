<?php
require_once('classes/QueryBuilder.php');
require_once('classes/Database.php');
require_once('classes/Session.php');

$db = Database::getConnection();
$session = new Session($db);
$qb = QueryBuilder::getInstance();
if($session->isLoggedIn()){
    echo"LOGGED IN";
} else {
    echo "NOT LOGGED IN";
}
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
?>