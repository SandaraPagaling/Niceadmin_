<?php
// Run this script from browser or CLI to create the `niceadmin` database and `event_registrations` table.
// Usage: http://localhost/NiceAdmin/tools/event_db.php

$sqlFile = __DIR__ . '/../sql/event_registration.sql';
if (!file_exists($sqlFile)) {
    echo "SQL file not found: " . htmlspecialchars($sqlFile);
    exit;
}

$sql = file_get_contents($sqlFile);
// Split into statements (naive) — works for simple SQL file.
$statements = array_filter(array_map('trim', preg_split('/;\s*\r?\n/', $sql)));

$mysqli = new mysqli('127.0.0.1', 'root', '', '');
if ($mysqli->connect_errno) {
    echo "Connect failed: (" . $mysqli->connect_errno . ") " . htmlspecialchars($mysqli->connect_error);
    exit;
}

foreach ($statements as $stmt) {
    if ($stmt === '') continue;
    if ($mysqli->query($stmt) === false) {
        echo "Error executing statement: " . htmlspecialchars($mysqli->error) . "<br>Statement: " . htmlspecialchars($stmt) . "<br>";
    } else {
        echo "OK: " . htmlspecialchars(substr($stmt,0,80)) . "...<br>";
    }
}

$mysqli->close();
echo "<p>Finished. You can now import DB or continue.</p>";
