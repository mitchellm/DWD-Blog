<?php
require_once 'base.php';
$blogs = $session->getArchive();
renderArchive($blogs);
?>