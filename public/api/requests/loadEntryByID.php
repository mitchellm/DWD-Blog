<?php
require_once 'base.php';
$entry = $session->loadEntryByID($blog);
renderBlog($entry['title'],$entry['author'],$entry['content']);
?>