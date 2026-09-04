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
    exit('Virheellinen ryhmätunnus.');
}

// Haetaan muokattavan ryhmän nykyiset tiedot.
$stmt = mysqli_prepare($conn, 'SELECT id, user_id, name, description FROM `groups` WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    exit('Ryhmää ei löytynyt.');
}

if ((int) $row['user_id'] !== $user_id) {
    exit('Et voi muokata toisen käyttäjän ryhmää.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Luetaan lomakkeella annetut uudet arvot.
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $description === '') {
        // Ryhmässä täytyy olla sekä nimi että kuvaus.
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } else {
        // Päivitetään vain oma ryhmä.
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE `groups` SET name = ?, description = ? WHERE id = ? AND user_id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ssii', $name, $description, $id, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Ryhmä päivitettiin onnistuneesti.';
            $message_class = 'Onnstui_message';

            // Näyttää lomakkeella juuri tallennetut arvot
            $row['name'] = $name;
            $row['description'] = $description;
        } else {
            $message = 'Julkaisun päivittäminen epäonnistui.';
            $message_class = 'Epaonnstui_message';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muokkaa ryhmää - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body>
    <?php include 'include/nav.php'; ?>

    <div class="add-post center-page">

        <h1>Muokkaa Ryhmää</h1>
        <?php if (!empty($message)) { ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label for="name">Ryhmän nimi</label>

            <input type="text" name="name" id="name" placeholder="Nimimerkkisi"
                value="<?php echo htmlspecialchars($row['name']); ?>">

            <label for="description">Ryhmän kuvaus</label>

            <textarea name="description"
                id="description"><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <button type="submit">
                Tallenna muutokset
            </button>
            <button type="button" onclick="history.back();">
                Takaisin
            </button>


        </form>

    </div>
	<?php include 'include/footer.php'; ?>
</body>

</html>