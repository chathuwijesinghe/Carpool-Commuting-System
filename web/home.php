<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/5/17
 * Time: 17:52
 */

$internal_error = $from = $from_error = $to = $to_error = "";
?>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/5/17
 * Time: 17:52
 */
require_once '../config/config.php';
require_once 'header.php';
require_once 'header_navbar.php';

$internal_error = $from = $from_error = $to = $to_error = $start_date = $start_date_error = "";
$execute = true;

// using cookies, retrieve the last search details
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_COOKIE["from"])) {
        $from = $_COOKIE["from"];
    }
    if (isset($_COOKIE["to"])) {
        $to = $_COOKIE["to"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* validate all inputs */
    // validate the from location
    $from = $_POST["from"];
    if (empty($from)) {
        $from_error = "Please enter a value.";
        $execute = false;
    }

    // validate the to location
    $to = $_POST["to"];
    if (empty($to)) {
        $to_error = "Please enter a value.";
        $execute = false;
    }

    // validate the start date
    $start_date = $_POST["start_date"];
    if (empty($start_date)) {
        $start_date_error = "Please enter a value.";
        $execute = false;
    }

    /* if all inputs are valid, then */
    if ($execute) {
        // set cookie to remember the last search
        setcookie("from", $from, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("to", $to, time() + (86400 * 30), "/"); // 86400 = 1 day

        // find recurring rides
        $sql = "SELECT * FROM post WHERE departure_point = '$from' AND arrival_point = '$to' AND frequency = 'recurring'";
        $results_recurring = $conn->query($sql);

        // find one time rides
        $sql = "SELECT * FROM post WHERE departure_point = '$from' AND arrival_point = '$to' AND frequency = 'one_time'";
        $results_one_time = $conn->query($sql);
    }
}
?>

    <div class="container main_home_container">
        <div class="row">
            <div class="quote"> Rideshare from Anywhere to Everywhere</div>
        </div>
        <div class="row">
            <div class="col-sm-6 left_form">
                <div class="find_a_ride_wrp">
                    <h3>Find a Ride</h3>
                    <span><?php echo $internal_error; ?></span><br/>
                    <!-- htmlspecialchars is used to protect against XSS attacks -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input_wrp">
                            <input type="text" placeholder="From" name="from"
                                   value="<?php echo htmlspecialchars($from); ?>"
                                   autofocus="autofocus"><i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <span><?php echo $from_error; ?></span><br/>
                        <div class="input_wrp">
                            <input type="text" name="to" placeholder="To" value="<?php echo htmlspecialchars($to); ?>"/><i
                                    class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <span><?php echo $to_error; ?></span><br/>
                        <div class="input_wrp">
                            <input type="date" name="start_date" placeholder="Start Date"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo htmlspecialchars($start_date); ?>"/><br/>
                        </div>
                        <div class="search_btn">
                            <button type="submit" value="Search"><i class="fa fa-search" aria-hidden="true"></i> SEARCH
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container results_content">
        <div class="search_results">
            <?php if (($results_recurring->num_rows == 0) && ($results_one_time->num_rows == 0)) {
                // no rides available
                echo "<div class=\"alert alert-danger\" role=\"alert\">";
                echo "<strong>Oh Sorry!</strong> No rides available. Come back later...:)";
                echo "</div>";
            } else {
                echo "<div><h3>Available Rides</h3><hr></div><div class=\"panel-group available_rides_panel\"><div class=\"panel panel-default\">";
            }
            if ($results_recurring->num_rows > 0) {
                // display recurring rides
                echo "<div class=\"panel-heading\">Recurring rides</div>";
                echo "<div class=\"panel-body\"><div class=\"col-sm-8\"><div class=\"col-sm-6\">";
                while ($row = $results_recurring->fetch_assoc()) {
                    echo "<label>Member:</label><span> " . $row["username"] . "</span><br>";
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
                    echo "</<br>";
                    echo "<label>Available Seat(s):</label><span> " . $row["available_seats"] . "</span><br>";
                    echo "<label>Phone number:</label><span> " . $row["phone_number"] . "</span><br>";
                    echo "<label>Comment:</label><span> " . $row["comment"] . "</span><hr>";
                }
                echo "</div></div></div>";
            }
            if ($results_one_time->num_rows > 0) {
                // display one time rides
                echo "<div class=\"panel-heading\">One time rides</div>";
                echo "<div class=\"panel-body\"><div class=\"col-sm-8\"><div class=\"col-sm-6\">";
                while ($row = $results_one_time->fetch_assoc()) {
                    echo "<label>Member:</label><span> " . $row["username"] . "</span><br>";
                    echo "<label>Date of journey:</label><span> " . $row["date_of_journey"] . "</span><br>";
                    echo "<label>Available Seat(s):</label><span> " . $row["available_seats"] . "</span><br>";
                    echo "<label>Phone number:</label><span> " . $row["phone_number"] . "</span><br>";
                    echo "<label>Comment:</label><span> " . $row["comment"] . "</span><hr>";
                }
                echo "</div></div></div>";
            } ?>
        </div>
    </div>

<?php require_once 'footer.php'; ?>