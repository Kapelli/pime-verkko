<?php

include 'database.php';

// Käynnistetään istunto, jotta kirjautuneen käyttäjän tiedot ovat käytettävissä.
session_start();

if (!isset($_SESSION["user_id"]))
{
    // Väliaikaisesti ohajtaan kijautumis sivulle DEBUG
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/../database.php";

    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

?>