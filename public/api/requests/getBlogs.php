<?php
require_once 'base.php';
$blogs = $session->getBlogs();
echo json_encode($blogs);
?>