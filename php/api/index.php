<?php
/**
* @package DWD-Blog
* @author Mitchell M.
* @version 1.0.0
*/

require_once(__DIR__ . '/../config/global.php');
function __autoload($class_name) {
	require_once(__DIR__ . '/../classes/' . $class_name . '.php');	
}
$db = Database::getConnection();
$session = new Session($db);

foreach($_REQUEST as $key => $val) { $$key = trim($val); }

$requests = array('login', 'register');

if($request && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$file = './requests/' . $request . '.php';
	if(file_exists($file) && in_array($request, $requests)) {
		require_once($file);
	} else {
            die("request not configured with api");
        }
} else {
	die("no direct access");
}
?>