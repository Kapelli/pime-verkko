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

// Vain oman julkaisun muokkaus on sallittu.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Luetaan profiilin uudet tiedot lomakkeesta.
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $email === '') {
        // Profiilia ei päivitetä puuttuvilla tiedoilla.
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Valid email is required';
        $message_class = 'Epaonnstui_message';
    } else {
        // Tarkistetaan, ettei toinen käyttäjä käytä samaa sähköpostiosoitetta.
        $email_stmt = mysqli_prepare(
            $conn,
            'SELECT id FROM user WHERE email = ? AND id <> ?'
        );
        mysqli_stmt_bind_param($email_stmt, 'si', $email, $id);
        mysqli_stmt_execute($email_stmt);
        $email_result = mysqli_stmt_get_result($email_stmt);

        if (mysqli_fetch_assoc($email_result)) {
            $message = 'email already taken';
            $message_class = 'Epaonnstui_message';
        } else {
            // Päivitetään kirjautuneen käyttäjän omat profiilitiedot.
            $stmt = mysqli_prepare(
                $conn,
                'UPDATE user SET name = ?, email = ? WHERE id = ?'
            );
            mysqli_stmt_bind_param($stmt, 'ssi', $name, $email, $id);

            if (mysqli_stmt_execute($stmt)) {
                $post_stmt = mysqli_prepare(
                    $conn,
                    'UPDATE posts SET author = ? WHERE user_id = ?'
                );
                mysqli_stmt_bind_param($post_stmt, 'si', $name, $id);
                mysqli_stmt_execute($post_stmt);
                mysqli_stmt_close($post_stmt);

                $message = 'Profiili päivitettiin onnistuneesti.';
                $message_class = 'Onnstui_message';

                // Näyttää lomakkeella juuri tallennetut arvot
                $row['name'] = $name;
                $row['email'] = $email;
            } else {
                $message = 'Profiilin päivittäminen epäonnistui.';
                $message_class = 'Epaonnstui_message';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muokkaa profiilia - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
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

            <?php if (!empty($message)) { ?>
                <div class="<?php echo $message_class; ?>">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form method="POST" class="profile-edit-form">

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
                    <button class="secondary-action" type="button" onclick="history.back();">
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