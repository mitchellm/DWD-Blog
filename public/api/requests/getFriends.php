<?php
require_once 'base.php';
$friends = $session->getFriends();
if(count($blogs) == 1) {
    echo "<option value=\"friend0\">{$friends[0]}</option>";
} else {
    for($i = 0; $i<count($blogs); $i++) {
        echo "<option value=\"friend{$i}\">{$friends[$i]}</option>";
    }
}
?>
