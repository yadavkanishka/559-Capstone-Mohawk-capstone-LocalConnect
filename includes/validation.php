

<?php
function isPasswordValid($password) {
    // At least 8 characters
    if (strlen($password) < 8) {
        return false;
    }
    // At least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    // At least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    // At least one number
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    // At least one special character
    if (!preg_match('/[\W_]/', $password)) {
        return false;
    }
    
}
?>