<?php
/**
 * Created by IntelliJ IDEA.
 * User: nishen
 * Date: 3/29/17
 * Time: 10:31 AM
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'carpool_commuting_system');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE); // Create connection