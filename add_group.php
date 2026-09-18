<?php

include 'database.php';

require __DIR__ . "/include/session.php";
// Alustetaan lomakkeen palauteviestit.
$message = '';
$message_class = '';
$user_id = (int) $_SESSION['user_id'];

// Luetaan käyttäjän ryhmätilastot user-taulusta.
$count_stmt = mysqli_prepare(
	$conn,
	'SELECT max_groups, groups_created FROM `user` WHERE id = ?'
);
mysqli_stmt_bind_param($count_stmt, 'i', $user_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$group_data = mysqli_fetch_assoc($count_result);
$group_count = (int) $group_data['groups_created'];
$max_groups = (int) $group_data['max_groups'];
mysqli_stmt_close($count_stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Poistetaan ylimääräiset välilyönnit käyttäjän syötteistä.
	$name = trim($_POST['name'] ?? '');
	$description = trim($_POST['description'] ?? '');

	if ($group_count >= $max_groups) {
		$message = "Olet saavuttanut {$max_groups} ryhmän enimmäisrajan.";
		$message_class = 'Epaonnstui_message';
	} elseif ($name === '' || $description === '') {
		// Estetään tyhjän ryhmän tallentaminen.
		$message = 'Täytä kaikki ryhmän kentät.';
		$message_class = 'Epaonnstui_message';
	} else {
		// Käytetään valmisteltua lausetta käyttäjän syötteiden käsittelyyn.
		$stmt = mysqli_prepare(
			$conn,
			'INSERT INTO `groups` (user_id, name, description) VALUES (?, ?, ?)'
		);
		mysqli_stmt_bind_param($stmt, 'iss', $user_id, $name, $description);

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_close($stmt);
			header('Location: index.php?created=1');
			exit;
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
	<link rel="icon" type="image/png" href="images/favicon.png">

<body>
	<?php include 'include/nav.php'; ?>
	<main class="add-post add-group-page">
		<h1>Luo ryhmä</h1>
		<p class="form-user-name">Ryhmät käytössä: <strong><?php echo $group_count; ?>/<?php echo $max_groups; ?></strong></p>

		<?php if ($message !== '') { ?>
			<div class="message <?php echo $message_class; ?>">
				<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
			</div>
		<?php } ?>

		<?php if ($group_count < $max_groups) { ?>
		<form method="post">
			<label for="name">Ryhmän nimi</label>
			<input id="name" name="name" type="text" required>

			<label for="description">Ryhmän kuvaus</label>
			<textarea id="description" name="description" required></textarea>

			<button type="submit">Luo ryhmä</button>
			<button type="button" onclick="window.location.href='index.php';">
				Takaisin
			</button>

		</form>
		<?php } else { ?>
		<div class="message Epaonnstui_message">
			Olet saavuttanut kahden ryhmän enimmäisrajan.
		</div>
		<button type="button" onclick="window.location.href='index.php';">
			Takaisin
		</button>
		<?php } ?>
	</main>
	<?php include 'include/footer.php'; ?>
</body>

</html>