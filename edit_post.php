<?php
include 'database.php';

require __DIR__ . "/include/session.php";

// Luetaan muokattavan julkaisun tunniste URL-osoitteesta.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit('Virheellinen julkaisutunnus.');
}

// Haetaan julkaisun nykyiset tiedot lomaketta varten.
$stmt = mysqli_prepare($conn, 'SELECT id, group_id, author, content FROM posts WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    exit('Julkaisua ei löytynyt.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Luetaan julkaisun uudet tiedot lomakkeesta.
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($author === '' || $content === '') {
        // Julkaisua ei päivitetä puuttuvilla tiedoilla.
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } else {
        // Päivitetään julkaisu parametrisoidulla kyselyllä.
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE posts SET author = ?, content = ? WHERE id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ssi', $author, $content, $id);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Julkaisu päivitettiin onnistuneesti.';
            $message_class = 'Onnstui_message';

            // Näyttää lomakkeella juuri tallennetut arvot
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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
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

            <label for="author">Nimimerkki</label>

            <input type="text" name="author" id="author" placeholder="Nimimerkkisi"
                value="<?php echo htmlspecialchars($row['author']); ?>">

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

</body>

</html>