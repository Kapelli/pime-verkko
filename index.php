<?php

include 'database.php';

$group_deleted = filter_input(INPUT_GET, 'deleted', FILTER_VALIDATE_INT) === 1;
$result = mysqli_query($conn, "SELECT id, name, description FROM `groups`");
$latest_posts = mysqli_query(
    $conn,
    "SELECT posts.author, posts.content, posts.created_at,
            posts.group_id, `groups`.name AS group_name
     FROM posts
     INNER JOIN `groups` ON `groups`.id = posts.group_id
     ORDER BY posts.id DESC
     LIMIT 10"
);
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
        <a href="add_group.php">Luo ryhmä</a>
    </nav>
     <header>
        <h1>Pimeäverkko</h1>
    </header>
    <main>
        <p>
        Pimeäverkko on yksityinen keskustelufoorumi, jossa käyttäjät voivat luoda ryhmiä ja julkaista sisältöä.
        </p>
    </main>
    <div class="home-columns">
        <section class="home-panel">
            <div class="group-list">
                <h2>Ryhmälista</h2>
            </div>
            <div class="group-items">
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <article class="group">
                    <div class="group-header">
                        <a class="name" href="group.php?id=<?php echo (int) $row['id']; ?>">
                            <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                        <div class="post-actions">
                            <div class="muokkaa">
                                <a href="edit_group.php?id=<?php echo (int) $row['id']; ?>">Muokkaa</a>
                            </div>
                            <div class="poista">
                                <a href="delete_group.php?id=<?php echo (int) $row['id']; ?>">Poista</a>
                            </div>
                        </div>
                    </div>
                    <p class="description">
                        <?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </article>
<?php } ?>
            </div>
        </section>

        <section class="home-panel latest-panel">
            <div class="group-list">
                <h2>Uusimmat postaukset</h2>
            </div>
            <div class="latest-posts">
<?php while ($post = mysqli_fetch_assoc($latest_posts)) { ?>
                <article class="latest-post">
                    <a class="latest-group" href="group.php?id=<?php echo (int) $post['group_id']; ?>">
                        <?php echo htmlspecialchars($post['group_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                    <strong class="latest-author">
                        <?php echo htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8'); ?>
                    </strong>
                    <p><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="latest-post-meta">
                        <time><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></time>
                    </div>
                </article>
<?php } ?>
            </div>
        </section>
    </div>

    <?php if ($group_deleted) { ?>
        <div class="message Onnstui_message home-message">
            Ryhmä poistettu onnistuneesti.
        </div>
    <?php } ?>
</body>
</html>