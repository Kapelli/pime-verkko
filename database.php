<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "pimeaverkko";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Palautetaan yhteys, jotta tiedosto toimii sekä include- että require-kutsuilla.
return $conn;
?>