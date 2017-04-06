<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/2/17
 * Time: 8:07 AM
 */
require_once('config.php');

session_start();

$user_check = $_SESSION['username'];

$ses_sql = mysqli_query($conn, "select username from user where username = '$user_check' ");

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$login_session = $row['username'];

if (!isset($_SESSION['username'])) {
    header("location:login.php");
}