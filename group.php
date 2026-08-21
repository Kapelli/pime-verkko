<?php

include 'database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $group_id = trim($_POST["group_id"]);
    $author = trim($_POST["author"]);
    $content = trim($_POST["content"]);
    $created_at = trim($_POST["created_at"]);

    if (empty($description) || empty($content)) {

        $message = "Täytä kaikki kentät.";
        $message_class = "Epaonnstui_message";

    } else {

        $sql = "INSERT INTO posts (group_id, author, content, created_at)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $group_id,
            $author,
            $content,
            $created_at
        );
    if (mysqli_stmt_execute($stmt)) {
        $message = "Julkaisu lisättiin onnistuneesti.";
        $message_class = "Onnstui_message";
    } else {
        $message = "Julkaisun lisääminen epäonnistui.";
        $message_class = "Epaonnstui_message";
        }
    }
}
$result = mysqli_query($conn, "SELECT id, group_id, author, content, created_at FROM `posts`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <nav>
                <a href="index.php">Etusivu</a>
            </nav>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="postaukset">
        <article class="post">
            <p class="author">
                <?php echo htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="content">
                <?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="created_at">
                <?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
        </article>
<?php } ?>
    </div>
</body>
</html>