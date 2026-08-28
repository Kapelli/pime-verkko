<?php

include 'database.php';

$message = '';
$message_class = '';
$selected_group_id = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT) ?: 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    $selected_group_id = $group_id ?: 0;
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!$group_id || $author === '' || $content === '') {
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO posts (group_id, author, content, created_at) VALUES (?, ?, ?, NOW())'
        );
        mysqli_stmt_bind_param($stmt, 'iss', $group_id, $author, $content);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Julkaisu lisättiin onnistuneesti.';
            $message_class = 'Onnstui_message';
        } else {
            $message = 'Julkaisun lisääminen epäonnistui.';
            $message_class = 'Epaonnstui_message';
        }
        mysqli_stmt_close($stmt);
    }
}

if (!$selected_group_id) {
    exit('Valitse ryhmä ennen julkaisun lisäämistä.');
}

$group_stmt = mysqli_prepare($conn, 'SELECT name FROM `groups` WHERE id = ?');
mysqli_stmt_bind_param($group_stmt, 'i', $selected_group_id);
mysqli_stmt_execute($group_stmt);
$group_result = mysqli_stmt_get_result($group_stmt);
$group = mysqli_fetch_assoc($group_result);
mysqli_stmt_close($group_stmt);

if (!$group) {
    exit('Virheellinen ryhmä.');
}
?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisää julkaisu - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'include/nav.php'; ?>

    <main class="add-post add-post-page">
        <h1>Lisää julkaisu</h1>

        <?php if ($message !== '') { ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <form method="post">
            <label for="author">Nimi</label>
            <input id="author" name="author" type="text" required>

            <label for="group_name">Ryhmä</label>
            <input id="group_name" type="text"
                value="<?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
            <input type="hidden" name="group_id" value="<?php echo (int) $selected_group_id; ?>">

            <label for="content">Julkaisu</label>
            <textarea id="content" name="content" required></textarea>

            <button type="submit">Julkaise</button>
            <button type="button" onclick="history.back();">
                Takaisin
            </button>

        </form>

    </main>
</body>

</html>