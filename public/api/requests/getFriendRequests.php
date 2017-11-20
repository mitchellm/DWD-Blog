<?php
require_once 'base.php';
$friendRequests = $session->getFriendsRequests();
if(count($blogs) == 1) {
    echo "<option value=\"friendRequest0\">{$friendReqiests[0]['sender']}</option>";
} else {
    for($i = 0; $i<count($blogs); $i++) {
        echo "<option value=\"{friend$Request$i}\">{$friends[$i]['sender']}</option>";
    }
}
?>
