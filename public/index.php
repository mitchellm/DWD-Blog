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
        ?>
        
        <title>BlogArea Login</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/js.js"></script>     
        <script src="js/checklogin.js"></script>     

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">

    </head>

    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container">

                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fa fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a id="logoutButton" class="nav-link" href="#" style="display:none;">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class = "container">

            <div class="toppane">
                <div align="center"> 
                    <img src="images/logo.png"/>
                </div>
            </div>

            <div class="leftpane">
                <div align="center"> 
                    <h1>Welcome</h1>
                </div>    
                <p></br>
                    BlogArea is an online platform that allows users to instantly share thoughts, experiences, and creative ideas.
                </p>
                <p>
                    With BlogArea you can:
                <ul>
                    <li>freely express your thoughts and opinions</li>
                    <li>connect with like-minded individuals</li>
                    <li>share knowledge with others</li>
                    <li>practice your writing skills</li>
                    <li>and much more!</li>
                </ul> 
                </p>
                <p>
                    Sign up now to get started!
                </p>


            </div>

            <div class="middlepane">
                <div align="center"> 
                    <?php
                    $blog = $session->getLatestEntry();
                    ?>
                    <h1><?=$blog['title']?></h1> by <?=$blog['author']?>
                    <?=$blog['content']?>

                    <!--
                    <h1>Featured Posts</h1>
                                        <div class="row">
                    <!-- Carousel --
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators --
                    <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
                    <!-- Wrapper for slides --
                    <div class="carousel-inner">
                            <div class="item active">
                                    <p alt="First slide">
                                            "But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born
                                            and I will give you a complete account of the system, and expound the actual teachings of the great explorer
                                            of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, 
                                            because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter 
                                            consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain
                                            pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can 
                                            procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical
                                            exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses
                                            to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"
                                    </p>
                            </div>
                            <div class="item">
                                    <p alt="Second slide">
                                            "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti
                                            atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt
                                            in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est
                                            et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus
                                            id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem
                                            quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et
                                            molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus
                                            maiores alias consequatur aut perferendis doloribus asperiores repellat."
                                    </p>
                            </div>
                            <div class="item">
                                    <p alt="Third slide">
                                            "On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized
                                            by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that
                                            are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is
                                            the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish.
                                            In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we
                                            like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the
                                            claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and
                                            annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects
                                            pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."
                                    </p>
                            </div>
                    </div>
                    <!-- Controls --
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                    </div><!-- /carousel -->
                </div>

            </div>


            <div class="rightpane">
                <div id="notice" align="center" style="display:none;">
                    You are now logged in, proceed to your profile by clicking in the upper right corner of the page!
                </div>
                <div align="center" class="LoginArea" id="loginPanel">  
                    <form class="form-signin" id="loginForm">
                        <h1 class="text-center">Sign In</h1>
                        <p>
                            </br>
                            <label class="sr-only">Email address</label>
                            <input type="email"  id="loginemail"  placeholder="Email address" class="form-control" required autofocus>
                        </p>

                        <p>
                            <label class="sr-only">Password</label>
                            <input type="password"  id="loginpass"  placeholder="Password" class="form-control" required>
                        </p>

                        <p><label><input type="checkbox"> Remember me</label></p>

                        <button type="submit" class="btn btn-danger btn-block">Sign In</button>
                    </form>

                    <label class="signup" data-toggle="modal" id="registerButton" data-target="#register">Don't have an account? Sign up now!
                    </label>

                    <div id="register" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <form id="registerForm">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Register</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            <label class="sr-only">Email address</label>
                                            <input type="email" placeholder="Email address" id="regemail" class="form-control" required>
                                        </p>
                                        <p>
                                            <label class="sr-only">Password</label>
                                            <input type="password" placeholder="Password" id="regpassword" class="form-control" required>
                                        </p>
                                        <p>
                                            <label class="sr-only">Confirm Password</label>
                                            <input type="password" placeholder="Confirm Password" id="regpasswordconf" class="form-control" required>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger btn-block">Register</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    </body>

</html>