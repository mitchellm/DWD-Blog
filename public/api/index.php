<?php
/**
* @package DWD-Blog
* @author Mitchell M.
* @version 1.0.0
*/

require_once(__DIR__ . '/config/global.php');
function __autoload($class_name) {
	require_once(__DIR__ . '/classes/' . $class_name . '.php');	
}
$db = Database::getConnection();
$session = new Session($db);

foreach($_REQUEST as $key => $val) { $$key = trim($val); }

$VALID_REQUESTS = array('login', 'register', 'checklogin', 'logout');
$httpXrequested = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
$isAjaxCall = $httpXrequested ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' : null;

//CHEAP BUGFIX (to be figured out l8er)
$isAjaxCall = $isAjaxCall || $request == "checklogin";

if ($httpXrequested && ($isAjaxCall) && isset($request)) {
    $access = true;
    $file = './requests/' . $request . '.php';
    if (file_exists($file) && in_array($request, $VALID_REQUESTS)) {
        require_once($file);
    } else {
        die("Request not found in host file-system OR not whitelisted.");
    }
} else {
    $req_out = isset($request) ? $request : null;
    $a = $httpXrequested ? "T":"F";
    $b = $isAjaxCall ? "T":"F";
    $c = isset($request) ? "T":"F";
    die("Attempting to direct access OR malformed request sent to API! (API Level) <br /> Errors: A[" . $a . "] // B[" . $b . "] // C[" . $c. "] // D[" . $req_out . "]");
}
?>