<?php

include 'database.php';

require __DIR__ . "/include/session.php";

// Tallennetaan kirjautuneen käyttäjän id, jotta voidaan tarkistaa omistajuus.
$user_id = (int) ($_SESSION['user_id'] ?? 0);


// Haetaan etusivulle kaikki keskusteluryhmät ja ryhmien tiedot tietokannasta.
$result = mysqli_query($conn, "SELECT id, name, email FROM `user` WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    exit('Käyttäjää ei löytynyt.');
}


//Käyttäjän omat postaukset
$post_result = mysqli_query($conn, "SELECT id, author, content, created_at FROM `posts` WHERE user_id = $user_id ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiili - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body class="profile-page-body">

    <?php
    // Lisätään sivuston yhteinen navigointi.
    include 'include/nav.php';
    ?>
    <main class="profile-page">
        <section class="profile-card" aria-labelledby="profile-title">
            <div class="profile-card__topline">
                <span class="profile-kicker">Oma profiili</span>
            </div>

            <div class="profile-card__identity">
                <span class="account-avatar-big" aria-hidden="true">
                    <?= htmlspecialchars(strtoupper(substr($user["name"], 0, 1))) ?>
                </span>
                <div>
                    <h1 id="profile-title"><?= htmlspecialchars($user["name"], ENT_QUOTES, 'UTF-8') ?></h1>
                    <p>Jäsen Pimeäverkossa</p>
                </div>
            </div>

            <div class="profile-detail">
                <span class="profile-detail__label">Sähköposti</span>
                <span class="profile-detail__value"><?= htmlspecialchars($user["email"], ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="profile-actions">
                <a class="action-button" href="edit_profile.php?id=<?php echo (int) $user['id']; ?>">
                    Muokkaa profiilia
                </a>
                <a class="logout-button" href="logout.php">
                    Kirjaudu ulos <span aria-hidden="true">&#8594;</span>
                </a>
            </div>
        </section>
        <section class="profile-posts">
            <h2>Omat postaukset</h2>
            <p>Omat postaukset näkyvät tässä.</p>

            <?php if (mysqli_num_rows($post_result) === 0) { ?>
                <article class="profile-post">
                    <p>Et ole julkaissut vielä mitään.</p>
                </article>
            <?php } ?>

            <?php while ($post = mysqli_fetch_assoc($post_result)) { ?>
                <article class="profile-post">
                    <strong class="profile-author">
                        <?php echo htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8'); ?>
                    </strong>
                    <p><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="profile-post-meta">
                        <time><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></time>
                    </div>
                </article>
            <?php } ?>

        </section>
    </main>

    <?php include 'include/footer.php'; ?>
</body>

</html>