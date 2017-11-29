<?php
require_once 'base.php';
$ret = $session->createRequest($data);
switch ($ret) {
    case 1:
        echo "Request successfully sent.";
        break;
    case 2:
        echo "You can't add yourself!";
        break;
    case 3:
        echo "Already have a pending request with this user!";
        break;
}
$allows = array(1,2,3);
if(!in_array($ret, $allows)) {
    echo "Failed for unknown reason!";
}
?>
