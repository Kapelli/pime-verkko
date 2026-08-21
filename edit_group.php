<?php
include 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit('Virheellinen ryhmätunnus.');
}

/* Hae ryhmä */
$stmt = mysqli_prepare($conn, 'SELECT id, name, description FROM `groups` WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    exit('Ryhmää ei löytynyt.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $description === '') {
        $message = 'Täytä kaikki kentät.';
        $message_class = 'Epaonnstui_message';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE `groups` SET name = ?, description = ? WHERE id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $description, $id);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Ryhmä päivitettiin onnistuneesti.';     
            $message_class = 'Onnstui_message';

            // Näyttää lomakkeella juuri tallennetut arvot
            $row['name'] = $name;
            $row['description'] = $description;
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
    <title>Muokkaa ryhmää - Pimeäverkko</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
            <nav>
                <a href="index.php">Etusivu</a>
                <a href="index.php">Takaisin etusivulle</a>
                <a href="add_post.php">Lisää Julkaisua</a>
                
            </nav>

            <div class="add-post center-page">

                <h1>Muokkaa Ryhmää</h1>
<?php if (!empty($message)) { ?>
    <div class="<?php echo $message_class; ?>">
        <?php echo $message; ?>
    </div>
<?php } ?>

                <form method="POST">

                    <label for="name">Ryhmän nimi</label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Nimimerkkisi"
                        value="<?php echo htmlspecialchars($row['name']); ?>"
                    >

                    <label for="description">Ryhmän kuvaus</label>

                    <textarea
                        name="description"
                        id="description"
                        ><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>

                        <input
                            type="hidden"
                            name="id"
                            value="<?php echo $row['id']; ?>"
>

                    <button type="submit">
                        Tallenna muutokset
                    </button>

                </form>

            </div>

</body>
</html>
