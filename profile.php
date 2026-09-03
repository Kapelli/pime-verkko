<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);


// Haetaan etusivulle kaikki keskusteluryhmät ja ryhmien tiedot tietokannasta.
$result = mysqli_query($conn, "SELECT id, name, email FROM `user` WHERE id = $user_id");

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiili</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    // Lisätään sivuston yhteinen navigointi.
    include 'include/nav.php';
    ?>
    <div class="avatar">
        <span class="account-avatar-big" aria-hidden="true">
            <?= htmlspecialchars(strtoupper(substr($user["name"], 0, 1))) ?>
        </span>
        <span class="account-text-big">
            <?= htmlspecialchars($user["name"], ENT_QUOTES, 'UTF-8') ?>
        </span>
        <a href="edit_profile.php?id=<?php echo (int) $user['id']; ?>">Muokkaa profiilia</a>
    </div>
    <a class="account-link" href="logout.php">Kirjaudu ulos <span aria-hidden="true">&#8594;</span></a>




</body>

</html>


<?php include 'include/footer.php'; ?>