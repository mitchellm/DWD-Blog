<html>
    <head>
        <?php
        
        require_once(__DIR__ . '/../api/config/global.php');
        function __autoload($class_name) {
                require_once(__DIR__ . '/../api/classes/' . $class_name . '.php');	
        }
        $db = Database::getConnection();
        $session = new Session($db);
        if(!$session->isLoggedIn()) {
            echo $session->login("a@b.c","12") ? "Logged you in for this demo." : "Couldnt log you in...";
        } else {
            echo "Already signed into an account that will work for the demo!";
        }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="./js/blog.js"></script>
        <meta charset="UTF-8">
        <title>blogging functional demo</title>
    </head>
    <body>
        <p>
            For this page you are auto-logged in as a some random user account, you then are simulating the input to post a blog entry/create a new blog.
        </p>
        <form style="border:1px solid black; float:left;" id="create">
            <table>
                <tr>
                    <td colspan="2" style="text-align:center;">Create New Blog</td>
                </tr>
                <tr>
                    <td>
                        Title:
                    </td>
                    <td>
                        <input type="text" name="title" id="title" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input style="width:100%;" type="submit" name="create" id="create"/>
                    </td>
                </tr>
            </table>
        </form>
        <form style="border:1px solid black; float:left;">
            <table>
                <tr>
                    <td colspan="2" style="text-align:center;">Post New Blog Entry</td>
                </tr>
                <tr>
                    <td>
                        Heading:
                    </td>
                    <td>
                        <input type="text" name="heading" id="heading" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Post Under Blog:
                    </td>
                    <td>
                        <select>
                            <option value="1">My life as a farm hand</option>
                            <option value="2">My new job</option>
                            <option value="3">Owning an audi</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Content:
                    </td>
                    <td>
                        <textarea></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input style="width:100%;" type="submit" name="create" id="create"/>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>