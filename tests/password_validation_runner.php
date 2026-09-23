<?php

require_once __DIR__ . '/../includes/validation.php';

$password = $argv[1] ?? '';

echo isPasswordValid($password) ? 'true' : 'false';