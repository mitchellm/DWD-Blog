<?php
/**
 * @package DWD-Blog
 * @author Mitchell M.
 * @version 1.0.0
 */
/**
 * Loading all the required classes/configuration files first
 */
require_once(__DIR__ . '/api/config/global.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/api/classes/' . $class_name . '.php');
}

/**
 * Creating the database connection and passing it to the primary session object
 */
$db = Database::getConnection();
$session = new Session($db);
if(!$session->isLoggedIn()) {
    $session->login("myname@gmail.com", "123123");
}

echo var_dump($session->deleteFriend(20));
?>