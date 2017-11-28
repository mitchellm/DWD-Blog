<!doctype html>
<html lang="en"> 
    <head>
        <?php
        /**
         * Loading all the required classes/configuration files first
         */
        require_once(__DIR__ . '/api/config/global.php');
        require_once ('includes/blog.php');
        require_once ('includes/archive.php');

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
                    <div id="friend" class="collapse">

                        <ul class="list-group">

                            <?php
                            $friends = $session->getFriends();
                            foreach ($friends as $friend) {
                                echo "<li class=\"list-group-item text-center\"><h3 id=\"openarchive\" userid=\"{$friend}\">" . $session->lookupUsername($friend) . "<a href=\"#\" id=\"remove\" friendid=\"{$friend}\"> [X]</a></h3></li><br />";
                            }
                            ?>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#request">Requests</button>
                    <div id="request" class="collapse">
                        </ul>						

                        <?php
                        $requests = $session->getPendingRequests();
                        foreach ($requests as $req) {
                            echo "<ul class=\"list-group\">";
                            echo "<li class=\"list-group-item text-center\">"
                            . "<h3>" 
                                    . $session->lookupUsername($req) .
                            "</h3>"
                            . "<button type=\"button\" class=\"btn btn-sm btn-danger\" class=\"reqbtn\" id=\"accbutton\" friendid=\"{$req}\">Accept</button>"
                            . "<button type=\"button\" class=\"btn btn-sm btn-danger\" class=\"reqbtn\" id=\"decbutton\" friendid=\"{$req}\">Decline</button></li>";
                            echo "</ul>";
                        }
                        if (count($requests) < 1) {
                            echo "You don't have any pending friend requests...";
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#search">Search</button>
                    <div id="search" class="collapse">
                        <form class="search-container">
                            <input type="text" id="search-bar" placeholder="Search">
                            <a href="#"><img class="search-icon" src="http://www.endlessicons.com/wp-content/uploads/2012/12/search-icon.png"></a>
                        </form>
                    </div>
                </div>    
            </div>
            <?php
            $uid = $session->uid();
            $entry = $session->getUserLatestEntry($uid);
            ?>
            <div class="middlepane">
                <div id="content" align="center"> 
                    <?php
                    if (is_null($entry)) {
                        echo "You don't have any entries... maybe go post one?";
                    } else {
                        renderBlog($entry['title'], $entry['author'], $entry['content']);
                    }
                    ?>
                </div>
            </div>
            <div class="rightpane">
                <div align="center">  
                    <h1>Archive</h1>

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
                                        Title: <input type="text" name="title" id="title" style="margin-bottom:15px;" />
                                        <textarea id="content" placeholder="Enter text here..." autofocus></textarea>
                                        <div class="modal-footer">
                                            <input type="submit" class="btn btn-danger btn-block" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#prev">Past Entries</button>
                    <div align="left">
                        <div id="prev" class="collapse">
                            <?php
                            $blogs = $session->getArchive();
                            renderArchive($blogs);
                            ?>
                        </div>
                    </div>
                    <a href="#" id="reloadown" class="btn btn-danger btn-block" role="button">Reload Archive</a>

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
