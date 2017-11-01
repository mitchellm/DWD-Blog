<?php
require_once 'base.php';
$blogs = $session->getBlogs();
if(count($blogs) == 1) {
    echo "<option value=\"{$blogs["blogid"]}\">{$blogs["title"]}</option>";
} else {
    for($i = 0; $i<count($blogs); $i++) {
        echo "<option value=\"{$blogs[$i]['blogid']}\">{$blogs[$i]['title']}</option>";
    }
}
?>