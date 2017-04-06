<?php
/**
 * Created by IntelliJ IDEA.
 * User: root
 * Date: 4/2/17
 * Time: 10:46 AM
 */

require_once '../config/config.php';
require_once '../config/session.php';

$internal_error = $departure_point = $departure_point_error = $arrival_point = $arrival_point_error = $frequency = $frequency_error = $recurring_error = $date_of_journey = $date_of_journey_error = $available_seats = $available_seats_error = $phone_number = $phone_number_error = $comment = "";
$execute = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* validate all inputs */
    // validate the departure point
    $departure_point = $_POST["departure_point"];
    if (empty($departure_point)) {
        $departure_point_error = "Please enter a value.";
        $execute = false;
    }

    // validate the arrival point
    $arrival_point = $_POST["arrival_point"];
    if (empty($arrival_point)) {
        $arrival_point_error = "Please enter a value.";
        $execute = false;
    }

    // validate frequency
    $frequency = $_POST["frequency"];
    if (!isset($frequency)) {
        $frequency_error = "Please select the frequency.";
        $execute = false;
    } else if ($frequency == "recurring") {
        if (!(isset($_POST['monday']) || isset($_POST['tuesday']) || isset($_POST['wednesday']) || isset($_POST['thursday']) || isset($_POST['friday']) || isset($_POST['saturday']) || isset($_POST['sunday']))) {
            $recurring_error = "Please select at least one day of the week.";
            $execute = false;
        } else {
            $date_of_journey = (new DateTime('1970-01-01'))->format('Y-m-d');
        }
    } else {
        if (!isset($_POST['date_of_journey'])) {
            $date_of_journey_error = "Please select a date of journey.";
            $execute = false;
        } else {
            $date_of_journey = $_POST['date_of_journey'];
        }
    }

    // validate no. of seats available
    $available_seats = $_POST["available_seats"];
    if (empty($available_seats)) {
        $available_seats_error = "Please enter a value.";
        $execute = false;
    } else if ($available_seats <= 0) {
        $available_seats_error = "At least one seat should be available.";
        $execute = false;
    }

    // validate phone number
    $phone_number = $_POST["phone_number"];
    if (empty($phone_number)) {
        $phone_number_error = "Please enter a value.";
        $execute = false;
    } else if (!preg_match("/^[0-9]{3}-[0-9]{7}$/", $phone_number)) {
        $phone_number_error = "Please enter a valid phone number. (0xx-xxxxxxx)";
        $execute = false;
    }

    // validate comment - comments are optional, add any validation here, if needed.
    if (isset($_POST["comment"])) {
        $comment = $_POST["comment"];
    } else {
        $comment = "";
    }

    /* then, if all inputs are valid */
    if ($execute) {
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // assign values required for database operation
        if (isset($_POST['monday'])) {
            $monday = 1;
        } else {
            $monday = 0;
        }
        if (isset($_POST['tuesday'])) {
            $tuesday = 1;
        } else {
            $tuesday = 0;
        }
        if (isset($_POST['wednesday'])) {
            $wednesday = 1;
        } else {
            $wednesday = 0;
        }
        if (isset($_POST['thursday'])) {
            $thursday = 1;
        } else {
            $thursday = 0;
        }
        if (isset($_POST['friday'])) {
            $friday = 1;
        } else {
            $friday = 0;
        }
        if (isset($_POST['saturday'])) {
            $saturday = 1;
        } else {
            $saturday = 0;
        }
        if (isset($_POST['sunday'])) {
            $sunday = 1;
        } else {
            $sunday = 0;
        }

        $sql = "INSERT INTO post (departure_point, arrival_point, frequency, monday, tuesday, wednesday, thursday, friday, saturday, sunday, date_of_journey, available_seats, phone_number, comment)
VALUES ('$departure_point', '$arrival_point', '$frequency', $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, '$date_of_journey', $available_seats, '$phone_number', '$comment')";
        if (mysqli_query($conn, $sql)) {
            // redirect to home page
            header("Location: home.php");
            die();
        } else {
            $internal_error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
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
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Departure point:</label>
    <input type="text" name="departure_point" value="<?php echo htmlspecialchars($departure_point); ?>"
           autofocus="autofocus"/>
    <span><?php echo $departure_point_error; ?></span><br/>
    <label>Arrival point:</label>
    <input type="text" name="arrival_point" value="<?php echo htmlspecialchars($arrival_point); ?>"/>
    <span><?php echo $arrival_point_error; ?></span><br/>
    <label>Frequency:</label>
    <span><?php echo $frequency_error; ?></span><br/>
    <input type="radio" name="frequency" value="recurring"/>Recurring<br/>
    <input type="checkbox" name="monday" value="monday">Monday
    <input type="checkbox" name="tuesday" value="tuesday">Tuesday
    <input type="checkbox" name="wednesday" value="wednesday">Wednesday
    <input type="checkbox" name="thursday" value="thursday">Thursday
    <input type="checkbox" name="friday" value="friday">Friday
    <input type="checkbox" name="saturday" value="saturday">Saturday
    <input type="checkbox" name="sunday" value="sunday">Sunday
    <span><?php echo $recurring_error; ?></span><br/>
    <input type="radio" name="frequency" value="one_time"/>One time<br/>
    <label>Date of journey:</label>
    <input type="date" name="date_of_journey" min="<?php echo date('Y-m-d'); ?>"
           value="<?php echo htmlspecialchars($date_of_journey); ?>"/>
    <span><?php echo $date_of_journey_error; ?></span><br/>
    <label>Available Seat(s):</label>
    <input type="number" name="available_seats" min="1" value="<?php echo htmlspecialchars($available_seats); ?>"/>
    <span><?php echo $available_seats_error; ?></span><br/>
    <label>Phone number:</label>
    <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>"/>
    <span><?php echo $phone_number_error; ?></span><br/>
    <label>Comments:</label>
    <input type="text" name="comment" value="<?php echo htmlspecialchars($comment); ?>"/><br/>
    <input type="submit" value=" Post "/>
</form>
</body>
</html>
