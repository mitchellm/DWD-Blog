<?php
require_once('classes/QueryBuilder.php');
$qb = QueryBuilder::getInstance();
$qry = $qb->start();
$qry->select("userid")->from("users")->where("email", "=", "a@b.c");
$result = $qry->get();


?>
<pre>
<?php
var_dump($result);
?>
</pre>