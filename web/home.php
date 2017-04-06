<?php require_once('header.php'); ?>
<?php require_once('header_navbar.php'); ?>
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

        $sql = "SELECT * FROM post";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "Available no. of seats: " . $row["available_seats"] . " - Phone number: " . $row["phone_number"] . " - Comment " . $row["comment"] . "<br>";
            }
        } else {
            echo "No rides available. Come back later.";
        }
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
                           <input type="text" placeholder="From" name="from" value="<?php echo htmlspecialchars($from); ?>"
                                  autofocus="autofocus"><i class="fa fa-map-marker" aria-hidden="true"></i>
                       </div>
                       <span><?php echo $from_error; ?></span><br/>
                       <div class="input_wrp">
                           <input type="text" name="to" placeholder="To" value="<?php echo htmlspecialchars($to); ?>"/><i
                               class="fa fa-map-marker" aria-hidden="true"></i>
                       </div>
                       <span><?php echo $to_error; ?></span><br/>
                       <div class="input_wrp">
                           <input type="date" name="start_date" placeholder="Start Date" min="<?php echo date('Y-m-d'); ?>"
                                  value="<?php echo htmlspecialchars($start_date); ?>"/><br/>
                       </div>
                       <div class="search_btn">
                           <!--               <input type="submit" value="Search"/>-->
                           <button type="submit" value="Search"><i class="fa fa-search" aria-hidden="true"></i> SEARCH</button>
                       </div>
                   </form>
               </div>
           </div>
        </div>

<!--        <div class="quote"> Rideshare from Anywhere to Everywhere</div>-->
    </div>
    <div class="container results_content">
        <div class="search_results">
            <div class="alert alert-danger" role="alert">
                <strong>Oh Sorry!</strong> No rides available. Come back later...:)
            </div>
            <div>
                <h3>Available Rides</h3>
                <hr>
            </div>

            <div class="panel-group available_rides_panel">
                <div class="panel panel-default">
                    <div class="panel-heading">Colombo to Negambo</div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <p>I'm heading Yatalamatta to Galle. If anyone willing to join my ride, please contact me.</p>
                            <div class="col-sm-6">
                                <label>Departure point:</label><span> Colombo</span><br>
                                <label>Date of journey:</label><span> 2017/04/14</span><br>
                                <label>Phone number:</label><span> 071-0000000</span><br>
                            </div>
                            <div class="col-sm-6">
                                <label>Departure point:</label><span> Negambo</span><br>
                                <label>Available Seat(s):</label><span> 2</span><br>

                            </div>


                        </div>
                        <div class="col-sm-4">
                            <button class="apply_btn">Apply</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>




<?php require_once('footer.php'); ?>