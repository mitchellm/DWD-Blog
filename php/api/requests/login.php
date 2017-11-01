<?php
require_once('base.php');
echo $session->login($email,$password) ? 1 : 0;