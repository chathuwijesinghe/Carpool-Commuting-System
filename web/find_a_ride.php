<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/5/17
 * Time: 17:52
 */

$internal_error = $from = $from_error = $to = $to_error = "";
?>

<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Find a ride</title>
</head>
<body>
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>From :</label>
    <input type="text" name="from" value="<?php echo htmlspecialchars($from); ?>"
           autofocus="autofocus"/>
    <span><?php echo $from_error; ?></span><br/>
    <label>To :</label>
    <input type="text" name="to" value="<?php echo htmlspecialchars($to); ?>"/>
    <span><?php echo $to_error; ?></span><br/>
    <label>Start date :</label>
    <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>"
           value="<?php echo htmlspecialchars($start_date); ?>"/><br/>
    <input type="submit" value="Search"/>
</form>
</body>
</html>
