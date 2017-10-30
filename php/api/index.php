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

$requests = array('login', 'register', 'checklogin', 'logout');

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && isset($request)) {
        $access = true;
	$file = './requests/' . $request . '.php';
	if(file_exists($file) && in_array($request, $requests)) {
		require_once($file);
	} else {
            die("Request not found in host file-system OR not whitelisted.");
        }
} else {
	die("Attempting to direct access OR malformed request sent to API!");
}
?>