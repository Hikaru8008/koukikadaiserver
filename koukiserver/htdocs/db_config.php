<?php
$host = 'mysql'; 
$username = 'data_user';
$password = 'data';
$database = 'develop_db';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";

$retry = 15;
while ($retry > 0) {
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        break;
    } catch (PDOException $e) {
        $retry--;
        if ($retry === 0) {
            die("DB接続失敗: " . $e->getMessage());
        }
        sleep(1);
    }
}
