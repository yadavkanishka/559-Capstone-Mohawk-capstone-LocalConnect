
<!-- test.php for testing if everythings is working well on the browser !-->

<?php
require_once 'config/db.php';

try {
    $conn = getDBConnection();
    echo "Connected successfully!";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

require_once 'includes/validation.php';

echo "<br><br>Password Validation Tests:<br>";
var_dump(isPasswordValid("password"));      // it should be false
var_dump(isPasswordValid("Password1"));     // it should be false
var_dump(isPasswordValid("P@ssword1"));     // it should be true


?>


