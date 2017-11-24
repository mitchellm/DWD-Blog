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
                    <?php
                    if ($session->isLoggedIn()) {
                        ?>
                        <a id="logoutButton" class="nav-link" href="#">Logout</a>
                        <?php
                    } else {
                        ?>
                        <a id="logoutButton" class="nav-link" href="#" style="display:none;">Logout</a>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>