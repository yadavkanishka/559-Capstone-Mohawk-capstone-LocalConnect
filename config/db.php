

<?php
$host = "127.0.0.1:8889"; // MAMP MySQL default port
$db_name = "localconnect";
$username = "root";
$password = "root"; // default MAMP password

function getDBConnection() {
    global $host, $db_name, $username, $password;
    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
            $username,
            $password
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>