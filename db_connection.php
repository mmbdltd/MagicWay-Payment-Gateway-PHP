<?php
/**
 * here we implement MySql database server
 * for others database server change mysqli_connect function
 */
$host = "localhost"; // put the HOST NAME
$username = "root"; // Put the MySQL Username
$password = "nopass"; // Put the MySQL Password
$database = "magicway-php-plugin"; // Put the Database Name

// Create connection for integration
$conn_integration = mysqli_connect($host, $username, $password, $database);

// Check connection for integration
if (!$conn_integration) {
    die("Connection failed: " . mysqli_connect_error());
}

