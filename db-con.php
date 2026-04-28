<?php
$type = 'mysql';
$server = 'localhost';
$db = 'auctionDB';
$port = '3306';
$charset = 'utf8mb4';
 
$username = 'root';
$password = '';  // 'root' for uwamp
 
$options = [
    // Able to catch errors
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Index rows by column name
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Stay true to data types!
    PDO::ATTR_EMULATE_PREPARES => false,
];
 
// mysql:host=localhost;dbname=phpbook;port=3306;charset=utf8mb4
$dsn = "mysql:host=localhost;dbname=auctionDB;port=3306;charset=utf8mb4";
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";