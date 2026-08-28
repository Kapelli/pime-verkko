<?php

// Tarkistetaan, että käyttäjä antoi nimen.
if (empty($_POST["name"])) {
    die("Name is required");
}

// Tarkistetaan sähköpostiosoitteen muoto.
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Varmistetaan salasanan vähimmäispituus.
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

// Salasanassa pitää olla vähintään yksi kirjain ja yksi numero.
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

// Varmistetaan, että salasanat vastaavat toisiaan.
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Tallennetaan tietokantaan salasanaa varten yksisuuntainen tiiviste.
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Avataan tietokantayhteys ja valmistellaan käyttäjän lisäys.
$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
                  
if ($stmt->execute()) {
    // Ohjataan käyttäjä onnistumisesta kertovalle sivulle.
    header("Location: signup-success.html");
    exit;
    
} else {
    // Erotellaan varattu sähköpostiosoite muista tietokantavirheistä.
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
} 