<?php
function renderArchive($blogs) {
    if (count($blogs) > 0) {
        foreach ($blogs as $key => $val) {
            $title = $blogs[$key]['title'];
            $blogid = $blogs[$key]['blogid'];
            echo "<a href=\"#\" blogid=\"{$blogid}\" id=\"showblog\">{$title}</a> <br />";
        }
    } else {
        echo "No blogs posted! Why not post a new one?";
    }
}
?>