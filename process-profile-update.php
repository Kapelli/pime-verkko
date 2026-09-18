<?php

require __DIR__ . "/include/session.php";

$user_id = (int) ($_SESSION['user_id'] ?? 0);

function redirect_with_message(string $message, string $status = 'error'): never
{
    header('Location: edit_profile.php?status=' . urlencode($status) . '&message=' . urlencode($message));
    exit;
}

// Siistitään nimi ennen tarkistuksia ja tallennusta.
$name = trim($_POST["name"] ?? '');
$email = trim($_POST["email"] ?? '');

if ($user_id === 0) {
    redirect_with_message('Sinun täytyy kirjautua sisään.');
}

// Tarkistetaan, että käyttäjä antoi nimen.
if ($name === '') {
    redirect_with_message('Nimi on pakollinen.');
}

// Varmistetaan nimen vähimmäispituus.
if (mb_strlen($name) < 8) {
    redirect_with_message('Nimen pitää olla vähintään 8 merkkiä pitkä.');
}

// Sallitaan kirjaimet, numerot, välilyönnit sekä some-nimissä yleiset . _ ja - merkit.
if (!preg_match('/\A[\p{L}\p{N}._ -]+\z/u', $name)) {
    redirect_with_message('Nimi sisältää kiellettyjä merkkejä.');
}

// Varmistetaan nimen enimmäispituus.
if (mb_strlen($name) > 20) {
    redirect_with_message('Nimi voi olla enintään 20 merkkiä pitkä.');
}

// Tarkistetaan sähköpostiosoitteen pituus ja muoto.
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('Anna kelvollinen sähköpostiosoite.');
}


// Avataan tietokantayhteys ja tarkistetaan sähköpostin saatavuus.
$mysqli = require __DIR__ . "/database.php";

// Varmistetaan, ettei nimi kuulu toiselle käyttäjälle.
$name_stmt = $mysqli->prepare('SELECT id FROM user WHERE name = ? AND id <> ? LIMIT 1');
$name_stmt->bind_param('si', $name, $user_id);
$name_stmt->execute();
$name_stmt->store_result();

if ($name_stmt->num_rows > 0) {
    $name_stmt->close();
    redirect_with_message('Nimi on jo käytössä.');
}

$name_stmt->close();

$email_stmt = $mysqli->prepare('SELECT id FROM user WHERE email = ? AND id <> ? LIMIT 1');
$email_stmt->bind_param('si', $email, $user_id);
$email_stmt->execute();
$email_stmt->store_result();

if ($email_stmt->num_rows > 0) {
    $email_stmt->close();
    redirect_with_message('Sähköpostiosoite on jo käytössä.');
} 

$email_stmt->close();
$stmt = $mysqli->prepare('UPDATE user SET name = ?, email = ? WHERE id = ?');
$stmt->bind_param('ssi', $name, $email, $user_id);

if (!$stmt->execute()) {
    $stmt->close();
    redirect_with_message('Profiilin päivittäminen epäonnistui.');
}

$stmt->close();
redirect_with_message('Profiili päivitettiin onnistuneesti.', 'success');