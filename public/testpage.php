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
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                alert("HELLO");
                $.ajax({
                    type: 'POST',
                    data: 'request=getBlogs',
                    url: 'api/index.php',
                    async: true,
                    success: function (response) {
                        alert(response);
                    },
                    error: function (exception) {
                        console.log('Exception:' + exception);
                    }
                });
            });
        </script>
    </head>
    <div id="content">
        content
    </div>
</html>