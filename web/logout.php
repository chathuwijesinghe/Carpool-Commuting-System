<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/2/17
 * Time: 8:14 AM
 */
session_start();

if (session_destroy()) {
    header("Location: login.php");
}