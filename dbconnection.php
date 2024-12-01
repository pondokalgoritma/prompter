<?php
$dsn = 'sqlite:'. __DIR__ .'/data.sqlite';
$username = null;
$password = null;

try {
    $db = new PDO($dsn, $username, $password);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("PRAGMA foreign_keys = ON;");

    
    $createTableQuery = "CREATE TABLE IF NOT EXISTS studios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    );";

    $db->exec($createTableQuery);


    $createTableQuery = "CREATE TABLE IF NOT EXISTS prompts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        showcase INTEGER NOT NULL,
        studio_id INTEGER NOT NULL,
        FOREIGN KEY (studio_id) REFERENCES studios(id) ON DELETE RESTRICT
    );";

    $db->exec($createTableQuery);

    $createTableQuery = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_name TEXT NOT NULL UNIQUE,
        full_name TEXT NOT NULL,
        mobile_number TEXT NOT NULL UNIQUE,
        role TEXT NOT NULL,
        password TEXT NOT NULL,
        studio_id INTEGER NULL,
        FOREIGN KEY (studio_id) REFERENCES studios(id) ON DELETE RESTRICT
    );";

    $db->exec($createTableQuery);


    // Default user

    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();

    if ($userCount == 0) {

        $stmt = $db->prepare("
            INSERT INTO users 
                (user_name, full_name, mobile_number, role, password) 
            VALUES 
            (:user_name, :full_name, :mobile_number, :role, :password)");
        
        $stmt->bindValue(':user_name', 'admin');
        $stmt->bindValue(':full_name', 'Admin');
        $stmt->bindValue(':mobile_number', '1234567890');
        $stmt->bindValue(':role', 'admin');
        $stmt->bindValue(':password', password_hash('123', PASSWORD_DEFAULT));

        $stmt->execute();
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}