<?php

require_once __DIR__ . '/../includes/validation.php';

$data = json_decode($argv[1] ?? '{}', true);

$errors = validateProfile(
    $data['full_name'] ?? '',
    $data['location'] ?? '',
    $data['skills'] ?? '',
    $data['interests'] ?? '',
    $data['biography'] ?? ''
);

echo json_encode($errors);