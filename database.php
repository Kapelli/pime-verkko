<?php

// Tietokantapalvelimen yhteystiedot.
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pimeaverkko";

// Luodaan yhteys MySQL-tietokantaan.
$conn = new mysqli($host, $username, $password, $dbname);
// Keskeytetään suoritus, jos tietokantayhteyttä ei voitu muodostaa.
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Palautetaan yhteys, jotta tiedosto toimii sekä include- että require-kutsuilla.
return $conn;
?>