<?php
// ajax autorise tout les requette
header('Access-Control-Allow-Origin: *');
// requette d'envoie en json
header('Content-Type: applicatin/json');

// On interdit toute methode qui n'est pas post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // pas le droit
    http_response_code(405);
    echo json_encode(['message' => 'Methode non autorisee']);
    exit;
}


// On verifi si on recoit un token
if (isset($_SERVER['Authorization'])) {
    $token = trim($_SERVER['Authorization']);
} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $token = trim($_SERVER['HTTP_AUTHORIZATION']);
} elseif (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    if (isset($requestHeaders['Authorization'])) {
        $token = trim($requestHeaders['Authorization']);
    }
}

if (!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)) {
    http_response_code(400);
    echo json_encode(['message' => 'Token introuvable']);
    exit;
}

// On extrait le token
$token = str_replace('Bearer ', '', $token);

require_once 'includes/config.php';
require_once 'classes/JWT.php';

$jwt = new JWT();

// On verifie la validite
if (!$jwt->isValid($token)) {
    http_response_code(400);
    echo json_encode(['message' => 'Token invalide']);
    exit;
}

// On verifie la signature
if (!$jwt->check($token, SECRET)) {
    http_response_code(403);
    echo json_encode(['message' => 'Le token est invalide']);
    exit;
}


// On verifie l'expiration
if ($jwt->isExpired($token)) {
    http_response_code(403);
    echo json_encode(['message' => 'Le token a expire']);
    exit;
}

echo json_encode($jwt->getPayload($token));
