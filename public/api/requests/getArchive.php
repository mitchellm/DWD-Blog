<?php
require_once 'base.php';
$blogs = $session->getArchive();
if (count($blogs) > 0) {
    foreach ($blogs as $key => $val) {
        $title = $blogs[$key]['title'];
        $blogid = $blogs[$key]['blogid'];
        echo "<a href=\"#\" blogid=\"{$blogid}\" id=\"showblog\">{$title}</a> <br />";
    }
} else {
    echo "No blogs posted! Why not post a new one?";
}
?>