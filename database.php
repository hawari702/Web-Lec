<?php

$host = "127.0.0.1"; // Berdasarkan SQL dump, host diatur ke 127.0.0.1
$dbname = "concert_system"; // Sesuaikan dengan nama database dari SQL dump
$username = "root";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
