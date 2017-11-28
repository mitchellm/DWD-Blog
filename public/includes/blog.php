<?php

function renderBlog($title, $author, $content) {
    if($title == "") {
        $title = "Undefined Title";
    }
    if($author == "") {
        $author = "Undefined Author";
    }
    if($content == "") {
        $content = "Content unavailable!";
    }
    ?>
    <h1><?= $title ?></h1> by <?= $author ?>
    <br /><br />
    <?= $content ?>
    <?php
}
?>