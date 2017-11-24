<!doctype html>
<html lang="en">

    <head>
        <?php
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
        if (!$session->isLoggedIn()) {
            $session->redirect("index.php");
        }
        ?>
        <title>BlogArea Profile Page</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/js.js"></script>     
        <script src="js/checklogin.js"></script>  

        <!-- Library -->
        <script src="/wysihtml-0.5.5/dist/wysihtml-toolbar.min.js"></script>
        <!-- wysihtml5 parser rules -->
        <script src="/wysihtml-0.5.5/parser_rules/advanced.js"></script> 

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">

    </head>

    <body>
        <!-- Navigation -->
        <?php
        require_once ('includes/navbar.php');
        ?>


        <div class = "container">

            <div class="toppane">
                <div align="center"> 
                    <img src="images/logo.png"/>
                </div>
            </div>

            <div class="leftpane">
                <div align="center"> 
                    <h2>Friends</h2>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#friend">Friend List</button>
                    <div id="friend" class="collapse">Friend 1</br>Friend 2</br> Friend 3</br></div>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#request">Requests</button>
                    <div id="request" class="collapse">Request 1</br>Request 2</br> Request 3</br></div>
                </div>    
            </div>
            <?php
            $uid = $session->uid();
            $entry = $session->getUserLatestEntry($uid);
            ?>
            <div class="middlepane">
                <div align="center"> 
                    <?php
                    if (is_null($entry)) {
                        echo "You don't have any entries... maybe go post one?";
                    } else {
                        ?>

                        <h1><?= $entry['title'] ?></h1> by <?= $entry['author'] ?>
                        <br />
                        <?= $entry['content'] ?>
                        <?php
                    }
                    ?>
                </div>

            </div>

            <div class="rightpane">

                <div align="center">  
                    <h1>Archive</h1>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#prev">Previous Entries</button>
                    <?php
                    $blogs = $session->getBlogs();
                    ?>
                    <div id="prev" class="collapse">
                        <?php
                        if (count($blogs) > 0) {
                            foreach ($blogs as $key => $val) {
                                $title = $blogs[$key]['title'];
                                $blogid = $blogs[$key]['blogid'];
                                echo "<a href=\"#\" blogid=\"{$blogid}\" id=\"showblog\">{$title}</a> <br />";
                            }
                        } else {
                            echo "No blogs posted! Why not post a new one?";
                        }
                        ?>
                    </div>                  
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#newpost">New Blog Entry</button>
                    <div id="newpost" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">New Blog Entry</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">

                                    <form id="newBlog">
                                        Title: <input type="text" name="title" id="title" style="margin-bottom:15px;"/>
                                        <textarea id="content" placeholder="Enter text here..." autofocus></textarea>


                                        <div class="modal-footer">
                                            <input type="submit" class="btn btn-danger btn-block" />
                                    </form>
                                </div>

                            </div>
                        </div>


                        <!-- Optional JavaScript -->
                        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

                        <script>
                            var editor = new wysihtml.Editor(document.getElementById('editor'), {
                                toolbar: document.getElementById('toolbar'),
                                parserRules: wysihtmlParserRules
                            });
                        </script>

                        </body>

                        </html>
