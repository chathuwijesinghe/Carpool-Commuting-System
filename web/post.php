<?php
/**
 * Created by IntelliJ IDEA.
 * User: root
 * Date: 4/2/17
 * Time: 10:46 AM
 */
require '../config/session.php';

$internal_error = $departure_point = $departure_point_error = $arrival_point = $arrival_point_error = $date_of_journey = $date_of_journey_error = $available_seats = $available_seats_error = $phone_number = $phone_number_error = $comments = "";
?>

<html>
<body>
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Departure point:</label>
    <input type="text" name="departure_point" value="<?php echo htmlspecialchars($departure_point); ?>"
           autofocus="autofocus"/>
    <span><?php echo $departure_point_error; ?></span><br/>
    <label>Arrival point:</label>
    <input type="text" name="rrival_point" value="<?php echo htmlspecialchars($arrival_point); ?>"/>
    <span><?php echo $arrival_point_error; ?></span><br/>
    <label>Frequency:</label><br/>
    <input type="radio" name="frequency" value="Recurring"/>Recurring<br/>
    <input type="checkbox" name="recurring" value="Monday">Monday
    <input type="checkbox" name="recurring" value="Tuesday">Tuesday
    <input type="checkbox" name="recurring" value="Wednesday">Wednesday
    <input type="checkbox" name="recurring" value="Thursday">Thursday
    <input type="checkbox" name="recurring" value="Friday">Friday
    <input type="checkbox" name="recurring" value="Saturday">Saturday
    <input type="checkbox" name="recurring" value="Sunday">Sunday<br/>
    <input type="radio" name="frequency" value="One time"/>One time<br/>
    <label>Date of journey:</label>
    <input type="text" name="date_of_journey" value="<?php echo htmlspecialchars($date_of_journey); ?>"/>
    <span><?php echo $date_of_journey_error; ?></span><br/>
    <label>Available Seat(s):</label>
    <input type="text" name="available_seats" value="<?php echo htmlspecialchars($available_seats); ?>"/>
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
