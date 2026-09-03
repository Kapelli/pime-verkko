<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);

// Luetaan poistettavan julkaisun tunniste ja tarkistetaan se.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('Virheellinen julkaisutunnus.');
}

if (!$id || $user_id === 0) {
    exit('Virheellinen julkaisutunnus.');
}

$select_stmt = mysqli_prepare(
    $conn,
    'SELECT id, group_id, user_id, author, content FROM posts WHERE id = ?'
);
// Haetaan poistettavan julkaisun tiedot vahvistussivua varten.
mysqli_stmt_bind_param($select_stmt, 'i', $id);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($select_stmt);

if (!$row) {
    exit('Julkaisua ei löytynyt.');
}

// Vain oman julkaisun muokkaus on sallittu.
if ((int) $row['user_id'] !== $user_id) {
    exit('Et voi muokata toisen käyttäjän julkaisua.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Estetään toisen julkaisun poistaminen muuttamalla lomakkeen tunnistetta.
    $delete_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($delete_id !== $id) {
        exit('Virheellinen julkaisutunnus.');
    }

    $delete_stmt = mysqli_prepare($conn, 'DELETE FROM posts WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($delete_stmt, 'ii', $id, $user_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        mysqli_stmt_close($delete_stmt);
        header('Location: group.php?id=' . (int) $row['group_id'] . '&deleted=1');
        exit;
    }

    $message = 'Julkaisun poistaminen epäonnistui.';
    $message_class = 'Epaonnstui_message';
    mysqli_stmt_close($delete_stmt);
}

?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poista julkaisu - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'include/nav.php'; ?>

    <div class="add-post center-page">

        <h1>Poista julkaisu</h1>

        <?php if (!empty($message)) { ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <p>Haluatko varmasti poistaa tämän julkaisun?</p>

        <p>
            <strong><?php echo htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <p>
            <?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <form method="POST">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <button type="submit">
                Poista
            </button>
            <button type="button" onclick="history.back();">
                Takaisin
            </button>


        </form>

    </div>
	<?php include 'include/footer.php'; ?>
</body>

</html>