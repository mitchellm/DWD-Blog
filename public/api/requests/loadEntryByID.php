<?php
require_once 'base.php';
$entry = $session->loadEntryByID($blog);
?>
<h1><?= $entry['title'] ?></h1> by <?= $entry['author'] ?>
<br /><br />
<?= $entry['content'] ?>