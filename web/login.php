<?php
/**
 * Created by IntelliJ IDEA.
 * User: root
 * Date: 4/2/17
 * Time: 8:14 AM
 */
require '../config/config.php';

session_start();

$internal_error = $login_error = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $login_error = "";

    // validate username and password against the database records
    $sql = "SELECT username FROM user WHERE username = '$username' and password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;

        // redirect to home page
        header("Location: home.php");
        die();
    } else {
        $login_error = "Username or password incorrect. Please try again.";
    }
}

?>

<html>
<body>
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"
           autofocus="autofocus"/><br/>
    <label>Password:</label>
    <input type="password" name="password"/>
    <span><?php echo $login_error; ?></span><br/>
    <input type="submit" value=" Submit "/>
</form>
</body>
</html>