<?php

include 'database.php';


session_start();


if (password_verify($password, $user["password"]))
{
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["name"] = $user["name"];

    header("Location: profile.php");
    exit;
}

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
    <?php
include 'include/nav.php';
?>

    <div class="account-bar">
        <?php if (isset($user)): ?>
            <div class="account-identity">
                <span class="account-avatar" aria-hidden="true">
                    <?= htmlspecialchars(strtoupper(substr($user["name"], 0, 1))) ?>
                </span>
                <div>
                    <span class="account-label">Kirjautuneena</span>
                    <strong><?= htmlspecialchars($user["name"], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>
            <a class="account-link" href="logout.php">Kirjaudu ulos <span aria-hidden="true">&#8594;</span></a>
        <?php else: ?>
            <div class="account-identity">
                <span class="account-avatar account-avatar-guest" aria-hidden="true">?</span>
                <div>
                    <span class="account-label">Tervetuloa mukaan</span>
                    <strong>Liity keskusteluun</strong>
                </div>
            </div>
            <div class="account-links">
                <a class="account-link" href="login.php">Kirjaudu sisään</a>
                <a class="account-link account-link-secondary" href="signup.html">Luo tili</a>
            </div>
        <?php endif; ?>
    </div>


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
                <a href="add_group.php">Lisää ryhmä</a>
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
    <?php include 'include/footer.php'; ?>
</body>
</html>