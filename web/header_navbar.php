<?php
session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}
?>

<header>
    <!--bootstrap nav bar from w3schools-->
    <nav class="navbar navbar-default page_header">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="logo clearfix">
                    <a href="home.php"> <img src="../images/logo.png" class="img-responsive"></a>
                    <span>CAR POOLING</span>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right links">
                    <!-- if the user is logged in -->
                    <?php if (isset($username)) {
                        echo "<li><a href=\"offer_ride.php\"><i class=\"fa fa-car\" aria-hidden=\"true\"></i>Offer a ride</a></li>";
                        echo "<li><a href=\"my_posts.php\"><i class=\"fa fa-car\" aria-hidden=\"true\"></i>My posts</a></li>";
                        echo "<li><a href=\"logout.php\"><i class=\"fa fa-car\" aria-hidden=\"true\"></i>Logout</a></li>";
                    } ?>

                    <!-- if the user isn't logged in -->
                    <?php if (!isset($username)) {
                        echo "<li><a href=\"login.php\"><i class=\"fa fa-cog\" aria-hidden=\"true\"></i>Login</a></li>";
                        echo "<li><a href=\"register.php\"><span class=\"glyphicon glyphicon-log-in\"></span>Register</a></li>";
                    } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>