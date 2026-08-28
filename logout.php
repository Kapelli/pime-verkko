<?php

// Aloitetaan istunto, jotta se voidaan tuhota.
session_start();

// Poistetaan kirjautumiseen liittyvät istuntotiedot.
session_destroy();

// Palataan etusivulle uloskirjautumisen jälkeen.
header("Location: index.php");
exit;