
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

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        if ($ssl_ca) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }

        $conn = new PDO($dsn, $username, $password, $options);

        return $conn;

    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("Database connection failed.");
    }
}

?>
