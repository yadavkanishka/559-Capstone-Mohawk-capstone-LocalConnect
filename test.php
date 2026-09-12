
<!-- test.php for testing if everythings is working well on the browser !-->

<?php
require_once 'config/db.php';

try {
    $conn = getDBConnection();
    echo "Connected successfully!";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
?>