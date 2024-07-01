<?php
require_once 'includes/config.php';
require_once 'classes/Jwt.php';
$header = [
    'typ' => 'JWT',
    'alg' => 'HS256'
];

// On creer le contenu appeller (payload)
$payload = [
    'user_id' => 123,
    'roles' => [
        'ROLE_ADMIN',
        'ROLE_USER'
    ],
    'email' => 'test@gmail.com'
];

$jwt = new JWT();
$token = $jwt->generate($header, $payload, SECRET);

echo $token;
