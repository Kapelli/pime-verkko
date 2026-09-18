<?php

include 'database.php';

// Käynnistetään istunto, jotta kirjautuneen käyttäjän tiedot ovat käytettävissä.
session_start();

$session_user_id = filter_var(
    $_SESSION['user_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($session_user_id === false || $session_user_id === null) {
    session_unset();
    session_destroy();
    header('Location: login-signin-page.html');
    exit;
}

$mysqli = require __DIR__ . "/../database.php";
$user_stmt = $mysqli->prepare('SELECT * FROM `user` WHERE id = ? LIMIT 1');
$user_stmt->bind_param('i', $session_user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login-signin-page.html');
    exit;
}

?>