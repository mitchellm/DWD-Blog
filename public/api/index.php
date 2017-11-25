<?php
/**
* @package DWD-Blog
* @author Mitchell M.
* @version 1.0.0
*/

/**
 * Loading all the required classes/configuration files first
 */
require_once(__DIR__ . '/config/global.php');
require_once (__DIR__ . '/../includes/blog.php');
require_once (__DIR__ . '/../includes/archive.php');

function __autoload($class_name) {
	require_once(__DIR__ . '/classes/' . $class_name . '.php');	
}

/**
 * Creating the database connection and passing it to the primary session object
 */
$db = Database::getConnection();
$session = new Session($db);

//Copies all the POST data values into variable variable names
foreach($_POST as $key => $val) { $$key = trim($val); }

//List of valid requests that are handled
$VALID_REQUESTS = array('login', 'register', 'checklogin', 'logout','createEntry',
    'getBlogs','getArchive','removeFriend','acceptRequest','declineRequest','getArchiveByUID'
    ,'loadEntryByID');

//Validating the existance of server variable "HTTP_X_REQUESTED_WITH", if it exists it can verify that the call is ajax
$httpXrequested = isset($_SERVER['HTTP_X_REQUESTED_WITH']);

//Ternary operator to determine if the call is a true ajax call
$isAjaxCall = $httpXrequested ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' : null;

//Validates all the required conditions to make an API call
if ($httpXrequested && ($isAjaxCall) && isset($request)) {
    
    //Sets the access variable that is checked for in the included files and forms a path to the request targed
    $access = true;
    $file = './requests/' . $request . '.php';
    
    //If the request is in the subfolder, and its listed in the valid request array.. 
    //open it
    if (file_exists($file) && in_array($request, $VALID_REQUESTS)) {
        //LOAD THE CONTENT OF THE REQUEST
        require_once($file);
    } else {
        die("Request not found in host file-system OR not whitelisted. REQUEST=[{$request}]");
    }
} else {
    //Build error message
    $req_out = isset($request) ? $request : null;
    $a = $httpXrequested ? "T":"F";
    $b = $isAjaxCall ? "T":"F";
    $c = isset($request) ? "T":"F";
    //Print error message
    die("Attempting to direct access OR malformed request sent to API! (API Level) <br /> Errors: A[" . $a . "] // B[" . $b . "] // C[" . $c. "] // D[" . $req_out . "]");
}
?>