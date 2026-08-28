<?php

// Kertoo näkymälle, epäonnistuiko viimeisin kirjautumisyritys.
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ladataan tietokantayhteys ja etsitään käyttäjä sähköpostiosoitteen perusteella.
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    // Muutetaan kyselyn ensimmäinen tulos käyttäjän tiedoiksi.
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        // Verrataan annettua salasanaa tietokantaan tallennettuun tiivisteeseen.
        if (password_verify($_POST["password"], $user["password_hash"])) {
            // Luodaan turvallinen istunto onnistuneen kirjautumisen jälkeen.
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }
    
    // Näytetään yleinen virheilmoitus, jos käyttäjää tai salasanaa ei tunnistettu.
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Kirjaudu | Pimeäverkko</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<main class="auth-card">
    
    <h1>Kirjaudu</h1>
    
    <?php if ($is_invalid): ?>
        <em>Virheellinen sähköposti tai salasana.</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">Sähköposti</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Salasana</label>
        <input type="password" name="password" id="password">
        
        <button>Kirjaudu sisään</button>
    </form>
    </main>
</body>
</html>