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

<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Find a ride</title>
</head>
<body
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <input type="text" placeholder="From" name="from" value="<?php echo htmlspecialchars($from); ?>
           autofocus="autofocus" />
    <span><?php echo $from_error; ?></span><br/>

    <input type="text" name="to" placeholder="To" value="<?php echo htmlspecialchars($to); ?>"/>
    <span><?php echo $to_error; ?></span><br/>
<<<<<<< HEAD
    
    <input type="date" name="start_date" placeholder="Start Date" min="<?php echo date('Y-m-d'); ?>"
           value="<?php echo htmlspecialchars($start_date); ?>"/><br/>
    <input type="submit" value="Search"/>
=======
    <label>Start date :</label>
    <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>"
           value="<?php echo htmlspecialchars($start_date); ?>"/>
    <span><?php echo $start_date_error; ?></span><br/>
    <input type="submit" value="Find"/>
>>>>>>> 33035c22e74369a46c60b496b5ef25b28e312240
</form>
</body>
</html>
