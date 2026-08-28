<?php

include 'database.php';

require __DIR__ . "/include/session.php";
// Alustetaan lomakkeen palauteviestit.
$message = '';
$message_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Poistetaan ylimääräiset välilyönnit käyttäjän syötteistä.
	$name = trim($_POST['name'] ?? '');
	$description = trim($_POST['description'] ?? '');

	if ($name === '' || $description === '') {
		// Estetään tyhjän ryhmän tallentaminen.
		$message = 'Täytä kaikki ryhmän kentät.';
		$message_class = 'Epaonnstui_message';
	} else {
		// Käytetään valmisteltua lausetta käyttäjän syötteiden käsittelyyn.
		$stmt = mysqli_prepare(
			$conn,
			'INSERT INTO `groups` (name, description) VALUES (?, ?)'
		);
		mysqli_stmt_bind_param($stmt, 'ss', $name, $description);

		if (mysqli_stmt_execute($stmt)) {
			$message = 'Ryhmä lisättiin onnistuneesti.';
			$message_class = 'Onnstui_message';
		} else {
			$message = 'Ryhmän lisääminen epäonnistui.';
			$message_class = 'Epaonnstui_message';
		}
		mysqli_stmt_close($stmt);
	}
}

?>
<!DOCTYPE html>
<html lang="fi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Luo ryhmä - Pimeäverkko</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php include 'include/nav.php'; ?>

	<main class="add-post add-group-page">
		<h1>Luo ryhmä</h1>

		<?php if ($message !== '') { ?>
			<div class="message <?php echo $message_class; ?>">
				<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
			</div>
		<?php } ?>

		<form method="post">
			<label for="name">Ryhmän nimi</label>
			<input id="name" name="name" type="text" required>

			<label for="description">Ryhmän kuvaus</label>
			<textarea id="description" name="description" required></textarea>

			<button type="submit">Luo ryhmä</button>
			<button type="button" onclick="history.back();">
				Takaisin
			</button>

		</form>
	</main>
</body>

</html>