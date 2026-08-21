<?php

include 'database.php';

$group_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$group_id) {
    exit('Virheellinen ryhmä.');
}
$post_deleted = filter_input(INPUT_GET, 'deleted', FILTER_VALIDATE_INT) === 1;
$group_stmt = mysqli_prepare(
    $conn,
    'SELECT name, description FROM `groups` WHERE id = ?'
);
mysqli_stmt_bind_param($group_stmt, 'i', $group_id);
mysqli_stmt_execute($group_stmt);
$group_result = mysqli_stmt_get_result($group_stmt);
$group = mysqli_fetch_assoc($group_result);
mysqli_stmt_close($group_stmt);

if (!$group) {
    exit('Ryhmää ei löytynyt.');
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, group_id, author, content, created_at
     FROM `posts`
     WHERE group_id = ?
     ORDER BY id DESC"
);
mysqli_stmt_bind_param($stmt, "i", $group_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <nav>
            <a href="index.php">Etusivu</a>
            <a href="add_post.php?group_id=<?php echo (int) $group_id; ?>">Lisää julkaisu</a>
        </nav>

        <header>
            <h1><?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </header>
        <main>
            <p><?php echo htmlspecialchars($group['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        </main>

    <div class="postaukset">
    <?php if ($post_deleted) { ?>
        <div class="message Onnstui_message">
            Julkaisu poistettu onnistuneesti.
        </div>
    <?php } ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
<article class="post">
    <div class="author-row">
        <p class="author">
            <?php echo htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p class="created_at">
            <?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <div class="post-actions">
            <div class="muokkaa">
                <a href="edit_post.php?id=<?php echo (int) $row['id']; ?>">
                    Muokkaa
                </a>
            </div>
            <div class="poista">
                <a href="delete_post.php?id=<?php echo (int) $row['id']; ?>">
                    Poista
                </a>
            </div>
        </div>
    </div>

    <p class="content">
        <?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?>
    </p>

</article>
<?php } ?>
    </div>
</body>
</html>