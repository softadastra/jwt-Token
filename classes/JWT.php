<?php
class JWT
{
    public function generate(array $header, array $payload, string $secret, int $validity = 86400): string
    {
        if ($validity > 0) {
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }

        // Encode en base 64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // on genere la signature
        $secret = base64_encode(SECRET);
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        $jwt = $base64Header . '.' . $base64Payload . '.' . $signature;

        return $jwt;
    }

    public function check(string $token, string $secret): bool
    {
        // On recupere le header et le payload du token
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // On genere un token de verification
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }

    public function getHeader(string $token)
    {
        // Demontage du token
        $array = explode('.', $token);
        //on decode le header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    public function getPayload(string $token)
    {
        // Demontage du token
        $array = explode('.', $token);
        //on decode le payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new DateTime();

        return $payload['exp'] < $now->getTimestamp();
    }

    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }
}
