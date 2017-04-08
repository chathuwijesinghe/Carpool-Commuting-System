<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/8/17
 * Time: 23:46
 */
require_once '../config/config.php';
require_once '../config/session.php';
require_once 'header.php';
require_once 'header_navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = $_SESSION["username"];

    // find all posts of the current user
    $sql = "SELECT * FROM post WHERE username = '$username' AND frequency = 'recurring'";
    $results_recurring = $conn->query($sql);

    $sql = "SELECT * FROM post WHERE username = '$username' AND frequency = 'one_time'";
    $results_one_time = $conn->query($sql);
}
?>

<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Post</title>
</head>
<body>
<div class="container results_content">
    <div class="search_results">
        <?php if (($results_recurring->num_rows == 0) && ($results_one_time->num_rows == 0)) {
            // no posts available
            echo "<div class=\"alert alert-danger\" role=\"alert\">";
            echo "<strong>Oh Sorry!</strong> You have no posts yet...)";
            echo "</div>";
        } else {
            echo "<div><h3>Your posts</h3><hr></div><div class=\"panel-group available_rides_panel\"><div class=\"panel panel-default\">";
        }
        if ($results_recurring->num_rows > 0) {
            // display recurring rides
            echo "<div class=\"panel-heading\">Recurring rides</div>";
            echo "<div class=\"panel-body\"><div class=\"col-sm-8\"><div class=\"col-sm-6\">";
            while ($row = $results_recurring->fetch_assoc()) {
                echo "<label>From:</label><span> " . $row["departure_point"] . "</span><br>";
                echo "<label>To:</label><span> " . $row["arrival_point"] . "</span><br>";
                echo "<label>Days:</label>";
                // display possible dates
                if ($row["monday"] == 1) {
                    echo "<span>Monday    </span>";
                }
                if ($row["tuesday"] == 1) {
                    echo "<span>Tuesday    </span>";
                }
                if ($row["wednesday"] == 1) {
                    echo "<span>Wednesday    </span>";
                }
                if ($row["thursday"] == 1) {
                    echo "<span>Thursday    </span>";
                }
                if ($row["friday"] == 1) {
                    echo "<span>Friday    </span>";
                }
                if ($row["saturday"] == 1) {
                    echo "<span>Saturday    </span>";
                }
                if ($row["sunday"] == 1) {
                    echo "<span>Sunday    </span>";
                }
                echo "<br>";
                echo "<label>Available Seat(s):</label><span> " . $row["available_seats"] . "</span><br>";
                echo "<label>Phone number:</label><span> " . $row["phone_number"] . "</span><br>";
                echo "<label>Comment:</label><span> " . $row["comment"] . "</span></br>";
                if (isset($row["image"])) {
                    echo "<img src=\"../post_images/" . $row["image"] . "\" style=\"height: 100px; width: 100px\">";
                }
                echo "<hr>";
            }
            echo "</div></div></div>";
        }
        if ($results_one_time->num_rows > 0) {
            // display one time rides
            echo "<div class=\"panel-heading\">One time rides</div>";
            echo "<div class=\"panel-body\"><div class=\"col-sm-8\"><div class=\"col-sm-6\">";
            while ($row = $results_one_time->fetch_assoc()) {
                echo "<label>From:</label><span> " . $row["departure_point"] . "</span><br>";
                echo "<label>To:</label><span> " . $row["arrival_point"] . "</span><br>";
                echo "<label>Date of journey:</label><span> " . $row["date_of_journey"] . "</span><br>";
                echo "<label>Available Seat(s):</label><span> " . $row["available_seats"] . "</span><br>";
                echo "<label>Phone number:</label><span> " . $row["phone_number"] . "</span><br>";
                echo "<label>Comment:</label><span> " . $row["comment"] . "</span></br>";
                if (isset($row["image"])) {
                    echo "<img src=\"../post_images/" . $row["image"] . "\" style=\"height: 100px; width: 100px\">";
                }
                echo "<hr>";
            }
            echo "</div></div></div>";
        } ?>
    </div>
</div>
</body>
</html>
