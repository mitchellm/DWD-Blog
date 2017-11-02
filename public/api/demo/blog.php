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
            echo "<br />Stored SID (CLIENT):".$_SESSION['sid']." \\ Server side: " . $session->sid;
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
            <br/>
            You create a new blog (basically a folder) with the form on the left, then you can post entries under that header with the right form.
            <br/>
            After you create a new blog heading, it will populate under the dropdown and you can select it to post from.
        </p>
        <p id="info" style="font-weight:bold;">
            
        </p>
        <form style="border:1px solid black; float:left;" id="create">
            <table>
                <tr>
                    <td colspan="2" style="text-align:center;">Create New Blog</td>
                </tr>
                <tr>
                    <td>
                        Blog Title:
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
        <form style="border:1px solid black; float:left;" id="newentry">
            <table>
                <tr>
                    <td colspan="2" style="text-align:center;">Post New Blog Entry</td>
                </tr>
                <tr>
                    <td>
                        Entry Title:
                    </td>
                    <td>
                        <input type="text" name="heading" id="title" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Post Under Blog:
                    </td>
                    <td>
                        <select name="blogid">
                            <?php
                            $blogs = $session->getBlogs();
                                for($i = 0; $i<count($blogs); $i++) {
                                    echo "<option value=\"{$blogs[$i]['blogid']}\">{$blogs[$i]['title']}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Content:
                    </td>
                    <td>
                        <textarea id="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input style="width:100%;" type="submit" name="create" id="create"/>
                    </td>
                </tr>
            </table>
        </form>
        <table>
            <tr>
                <th>
                    Blog Title
                </th>
                <th>
                    Entry Title
                </th>
                <th>
                    Author
                </th>
                <th>
                    Post date
                </th>
            </tr>
            <tr>
                <td>
                    A day in the life
                </td>
                <td>
                    Day 1
                </td>
                <td>
                    Mitchell M.
                </td>
                <td>
                    11/1/2/2017
                </td>
            </tr>
            <tr>
                <td>
                    A day in the life
                </td>
                <td>
                    Day 1
                </td>
                <td>
                    Mitchell M.
                </td>
                <td>
                    11/1/2/2017
                </td>
            </tr>
            <tr>
                <td>
                    A day in the life
                </td>
                <td>
                    Day 1
                </td>
                <td>
                    Mitchell M.
                </td>
                <td>
                    11/1/2/2017
                </td>
            </tr>
            <tr>
                <td>
                    A day in the life
                </td>
                <td>
                    Day 1
                </td>
                <td>
                    Mitchell M.
                </td>
                <td>
                    11/1/2/2017
                </td>
            </tr>
        </table>
    </body>
</html>