<?php

include 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('Virheellinen julkaisutunnus.');
}

$select_stmt = mysqli_prepare(
    $conn,
    'SELECT id, group_id, author, content FROM posts WHERE id = ?'
);
mysqli_stmt_bind_param($select_stmt, 'i', $id);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($select_stmt);

if (!$row) {
    exit('Julkaisua ei löytynyt.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($delete_id !== $id) {
        exit('Virheellinen julkaisutunnus.');
    }

    $delete_stmt = mysqli_prepare($conn, 'DELETE FROM posts WHERE id = ?');
    mysqli_stmt_bind_param($delete_stmt, 'i', $id);

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

        <nav>
            <a href="index.php">Etusivu</a>
            <a href="group.php?id=<?php echo (int) $row['group_id']; ?>">Takaisin ryhmään</a>
        </nav>

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

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $row['id']; ?>"
                >

                <button type="submit">
                    Poista
                </button>

            </form>

        </div>

</body>
</html>