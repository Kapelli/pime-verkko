<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);

//tarkistetaan että käyttäjä on kirjautunt sisään
if ($user_id === 0) {
    die("Virhe: Sinun täytyy kirjautua sisään.");
}

// Luetaan poistettavan ryhmän tunniste ja tarkistetaan se.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('Virheellinen ryhmätunnus.');
}

$select_stmt = mysqli_prepare($conn, 'SELECT id, user_id, name, description FROM `groups` WHERE id = ?');
// Haetaan poistettavan ryhmän tiedot vahvistussivua varten.
mysqli_stmt_bind_param($select_stmt, 'i', $id);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($select_stmt);

if (!$row) {
    exit('Ryhmää ei löytynyt.');
}

if ((int) $row['user_id'] !== $user_id) {
    exit('Et voi poistaa toisen käyttäjän ryhmää.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Varmistetaan, että lomake lähettää saman tunnisteen kuin sivu.
    $delete_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($delete_id !== $id) {
        exit('Virheellinen ryhmätunnus.');
    }

    // Poistetaan ensin ryhmän julkaisut ja sitten itse ryhmä yhdessä tapahtumassa.
    mysqli_begin_transaction($conn);

    $posts_stmt = mysqli_prepare($conn, 'DELETE FROM posts WHERE group_id = ? AND user_id = ?');
    mysqli_stmt_bind_param($posts_stmt, 'ii', $id, $user_id);
    $posts_deleted = mysqli_stmt_execute($posts_stmt);
    mysqli_stmt_close($posts_stmt);

    $group_stmt = mysqli_prepare($conn, 'DELETE FROM `groups` WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($group_stmt, 'ii', $id, $user_id);
    $group_deleted = mysqli_stmt_execute($group_stmt);
    mysqli_stmt_close($group_stmt);

    if ($posts_deleted && $group_deleted) {
        mysqli_commit($conn);
        header('Location: index.php?deleted=1');
        exit;
    }

    mysqli_rollback($conn);
    $message = 'Ryhmän poistaminen epäonnistui.';
    $message_class = 'Epaonnstui_message';
}

?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poista ryhmä - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'include/nav.php'; ?>

    <div class="add-post center-page">

        <h1>Poista ryhmä</h1>

        <?php if (!empty($message)) { ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <p>Haluatko varmasti poistaa ryhmän ja kaikki sen julkaisut?</p>

        <p>
            <strong><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <p>
            <?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?>
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

</body>

</html>