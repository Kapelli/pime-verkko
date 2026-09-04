<?php
include 'database.php';

require __DIR__ . "/include/session.php";

$user_name = trim((string) ($user['name'] ?? ''));
if ($user_name === '') {
    exit('Käyttäjänimi puuttuu.');
}

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);

// Luetaan muokattavan julkaisun tunniste URL-osoitteesta.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $user_id === 0) {
    exit('Virheellinen julkaisutunnus.');
}

// Haetaan julkaisun tiedot ja myös user_id, jotta voidaan varmistaa, että käyttäjä omistaa julkaisun.
$stmt = mysqli_prepare($conn, 'SELECT id, group_id, user_id, author, content FROM posts WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    exit('Julkaisua ei löytynyt.');
}

// Vain oman julkaisun muokkaus on sallittu.
if ((int) $row['user_id'] !== $user_id) {
    exit('Et voi muokata toisen käyttäjän julkaisua.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Käyttäjän nimi pysyy aina oikeana, vaikka lomakkeelta ei anneta nimeä erikseen.
    $author = $user_name;
    $content = trim($_POST['content'] ?? '');

    if ($content === '') {
        // Julkaisua ei päivitetä puuttuvilla tiedoilla.
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } else {
        // Päivitetään vain se julkaisu, joka kuuluu kirjautuneelle käyttäjälle.
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE posts SET author = ?, content = ? WHERE id = ? AND user_id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ssii', $author, $content, $id, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Julkaisu päivitettiin onnistuneesti.';
            $message_class = 'Onnstui_message';

            // Näyttää lomakkeella juuri tallennetut arvot.
            $row['author'] = $author;
            $row['content'] = $content;
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
    <title>Muokkaa julkaisua - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body>
    <?php include 'include/nav.php'; ?>

    <div class="add-post center-page">

        <h1>Muokkaa julkaisu</h1>
        <?php if (!empty($message)) { ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <p class="form-user-name">Julkaisu näkyy käyttäjänä: <strong><?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?></strong></p>

            <label for="content">Julkaisu</label>

            <textarea name="content" id="content"><?php echo htmlspecialchars($row['content']); ?></textarea>

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