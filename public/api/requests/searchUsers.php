<?php
require_once 'base.php';
$entries = $session->userSearch($searchInput);
if(count($blogs) == 1) {
    echo "<option value=\"userID0\">{$entries['userid']}</option>";
} else {
    for($i = 0; $i<count($blogs); $i++) {
        echo "<option value=\"userID{$i}}\">{$entries[$i]['userid']}</option>";
    }
}
?>
