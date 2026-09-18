<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);

//tarkistetaan että käyttäjä on kirjautunt sisään
if ($user_id === 0) {
    die("Virhe: Sinun täytyy kirjautua sisään.");
}

// Profiilia muokataan aina kirjautuneen käyttäjän omalla tunnisteella.
$id = $user_id;

if ($id === 0) {
    exit('Virheellinen käyttäjä.');
}

// hataan käyttäjän profiilin tiodot
$stmt = mysqli_prepare($conn, 'SELECT id, name, email FROM user WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    exit('Käyttäjää ei löytynyt.');
}

$message = trim($_GET['message'] ?? '');
$message_class = ($_GET['status'] ?? '') === 'success'
    ? 'Onnstui_message'
    : 'Epaonnstui_message';
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muokkaa profiilia - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <!-- defer suorittaa validointikoodin vasta HTML:n latauduttua. -->
    <script src="js/validation-profile-edit.js" defer></script>
</head>

<body>
    <?php include 'include/nav.php'; ?>

    <main class="profile-edit-page">
        <section class="profile-edit-card">
            <div class="profile-edit-heading">
                <span class="profile-edit-kicker">Tilin asetukset</span>
                <h1>Muokkaa profiilia</h1>
                <p>Päivitä nimesi ja sähköpostiosoitteesi.</p>
            </div>

            <?php if ($message !== '') { ?>
                <div class="message <?php echo $message_class; ?>">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <form action="process-profile-update.php" method="POST" id="profile-edit" class="profile-edit-form">

                <label for="name">Nimi</label>

                <input type="text" name="name" id="name" placeholder="Nimesi"
                    value="<?php echo htmlspecialchars($row['name']); ?>">

                <label for="email">Sähköposti</label>
                <input type="email" name="email" id="email" placeholder="Sähköpostisi"
                    value="<?php echo htmlspecialchars($row['email']); ?>">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <div class="profile-edit-actions">
                    <button class="action-button" type="submit">
                        Tallenna muutokset
                    </button>
                    <button class="secondary-action" type="button" onclick="window.location.href='profile.php';">
                        Takaisin
                    </button>
                </div>
            </form>
        </section>
    </main>

    </div>

    <?php include 'include/footer.php'; ?>

</body>

</html>