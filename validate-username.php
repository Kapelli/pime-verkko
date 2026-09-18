<?php

session_start();
$mysqli = require __DIR__ . "/database.php";
$name = trim($_GET["name"] ?? '');
$user_id = (int) ($_SESSION['user_id'] ?? 0);

$stmt = $mysqli->prepare(
    "SELECT id FROM user WHERE name = ? AND id <> ? LIMIT 1"
);
$stmt->bind_param("si", $name, $user_id);
$stmt->execute();
$stmt->store_result();
$is_available = $stmt->num_rows === 0;
$stmt->close();

// Palautetaan tarkistuksen tulos JavaScriptille JSON-muodossa.
header("Content-Type: application/json");

echo json_encode(["available" => $is_available]);