<?php

require '../config/config.php';

session_start();
$username = $_SESSION["username"];
$email = $_SESSION["email"];

$activation_code = $activation_code_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activation_code = $_POST["activation_code"];
    // compare the activation code entered by the user, with the activation code stored in the database
    $sql = "SELECT * FROM inactive_user WHERE username = '$username' AND activation_code = '$activation_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        /* activation code entered by user is correct */
        // move the user from inactive_users to users table
        while ($row = $result->fetch_assoc()) {
            $password = $row["password"];
        }

        /* delete the entry from inactive_user table */
        $sql = "DELETE FROM inactive_user WHERE username = '$username' AND activation_code = '$activation_code'; ";
        if (mysqli_query($conn, $sql)) {
            /* create an entry in user table */
            $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email'); ";
            if (mysqli_query($conn, $sql)) {
                // redirect to home page
                header("Location: home.php");
                die();
            } else {
                $internal_error = "Error: " . $sql . " < br>" . mysqli_error($conn);
            }
        } else {
            $internal_error = "Error: " . $sql . " < br>" . mysqli_error($conn);
        }
    } else {
        $activation_code_error = "Activation code is invalid . Please check the activation code you entered . ";
    }
}
?>

<html>
<body>
<span><?php echo $internal_error; ?></span><br/>
<p>An email with your activation code has been sent to <?php echo $email ?></p>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Activation code:</label>
    <input type="text" name="activation_code" value="<?php echo htmlspecialchars($activation_code); ?>"
           autofocus="autofocus"/>
    <span><?php echo $activation_code_error; ?></span><br/>
    <input type="submit" value=" Submit "/>
</form>
</body>
</html>