<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$db_name = getenv('DB_NAME') ?: 'localconnect';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: '3306';
$ssl_ca = getenv('DB_SSL_CA');

function getDBConnection() {
    global $host, $db_name, $username, $password, $port, $ssl_ca;

    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";

        if (!empty($ssl_ca)) {

    error_log("CA path: " . $ssl_ca);
    error_log("CA exists: " . (file_exists($ssl_ca) ? "YES" : "NO"));
    error_log("CA readable: " . (is_readable($ssl_ca) ? "YES" : "NO"));

    if (!is_readable($ssl_ca)) {
        throw new RuntimeException("SSL certificate is missing or unreadable.");
    }

            $dsn .= ";sslmode=verify-ca;sslrootcert=$ssl_ca";
        }

        $conn = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $conn;

    } catch (Throwable $e) {
        error_log("Database connection error: " . $e->getMessage());
        die("Database connection failed.");
    }
}

