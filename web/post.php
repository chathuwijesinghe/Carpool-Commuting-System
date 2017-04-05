<?php
/**
 * Created by IntelliJ IDEA.
 * User: root
 * Date: 4/2/17
 * Time: 10:46 AM
 */

require '../config/config.php';
//require '../config/session.php';

$internal_error = $departure_point = $departure_point_error = $arrival_point = $arrival_point_error = $frequency = $frequency_error = $recurring_error = $date_of_journey = $date_of_journey_error = $available_seats = $available_seats_error = $phone_number = $phone_number_error = $comments = "";
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
    if (!isset($_POST["frequency"])) {
        $frequency_error = "Please select the frequency.";
        $execute = false;
    } else if ($frequency = "recurring") {
        if (!(isset($_POST['monday']) || isset($_POST['tuesday']) || isset($_POST['wednesday']) || isset($_POST['thursday']) || isset($_POST['friday']) || isset($_POST['saturday']) || isset($_POST['sunday']))) {
            $recurring_error = "Please select at least one day of the week.";
            $execute = false;
        }
    } else if ($frequency = "one_time") {
        if ($_POST['date_of_journey'] < time()) {
            $date_of_journey = "Please select a valid date.";
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
    <input type="radio" name="frequency" value="one_time" <?php if ($frequency == "one_time") {
        echo " checked";
    }; ?>/>One time<br/>
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
    <input type="text" name="comments" value="<?php echo htmlspecialchars($comments); ?>"/><br/>
    <input type="submit" value=" Post "/>
</form>
</body>
</html>
