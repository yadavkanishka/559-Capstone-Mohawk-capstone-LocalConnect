

<?php
function isPasswordValid(string $password) {
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
    return true; 
}
function passwordRequirementsText() {
    return "Password must be at least 8 characters and include an uppercase letter, a lowercase letter, a number, and a special character.";
}

function isEmailValid($email) {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

//  it should Return an array of validation error strings for registration

function validateRegistration($email, $password, $full_name) {

    $errors = [];

        if ($full_name === '') {
            $errors[] = "Full name is required.";
        }

        if ($email === '') {
            $errors[] = "Email is required.";
        } 
            elseif (!isEmailValid($email)) 
            {
                $errors[] = "Please enter a valid email address.";
            }
        if (!isPasswordValid($password)) {
            $errors[] = passwordRequirementsText();
        }
    return $errors;

}
function validateProfile($full_name, $location, $skills, $interests, $biography) {
    $errors = [];

    if ($full_name === '') {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) > 150) {
        $errors[] = "Full name must be 150 characters or less.";
    }

    if (strlen($location) > 100) {
        $errors[] = "Location must be 100 characters or less.";
    }

    if (strlen($skills) > 2000) {
        $errors[] = "Skills must be 2000 characters or less.";
    }

    if (strlen($interests) > 2000) {
        $errors[] = "Interests must be 2000 characters or less.";
    }

    if (strlen($biography) > 5000) {
        $errors[] = "Biography must be 5000 characters or less.";
    }

    return $errors;
}
?>