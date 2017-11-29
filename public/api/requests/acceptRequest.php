<?php
require_once 'base.php';
echo $session->acceptRequest($friendID);
$session->redirect("profile.php");
?>
