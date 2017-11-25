<?php
require_once 'base.php';
$blogs = $session->getArchiveByUID($user);
if (count($blogs) > 0) {
    foreach ($blogs as $key => $val) {
        $title = $blogs[$key]['title'];
        $blogid = $blogs[$key]['blogid'];
        echo "<a href=\"#\" blogid=\"{$blogid}\" id=\"showblog\">{$title}</a> <br />";
    }
} else {
    echo "This user has no blogs posted!";
}
?>