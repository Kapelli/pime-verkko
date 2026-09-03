<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);

//tarkistetaan että käyttäjä on kirjautunt sisään
if ($user_id === 0) {
    die("Virhe: Sinun täytyy kirjautua sisään.");
}

// Luetaan muokattavan ryhmän tunniste URL-osoitteesta.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit('Virheellinen käyttäjä.');
}

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'include/nav.php'; ?>
    <?php include 'include/footer.php'; ?>
</body>

</html>