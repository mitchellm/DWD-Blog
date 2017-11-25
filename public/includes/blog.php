<?php

function renderBlog($title, $author, $content) {
    ?>
    <h1><?= $title ?></h1> by <?= $author ?>
    <br /><br />
    <?= $content ?>
    <?php
}
?>