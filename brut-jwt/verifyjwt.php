<?php

$base64UrlDecode = function (string $encoded) {
    if ($remainder = strlen($encoded) % 4) {
        $encoded .= str_repeat('=', 4 - $remainder);
    }

    return base64_decode(strtr($encoded, '-_', '+/'));
};

$token = $argv[1];
$payload = explode('.', $token);

$headers = (array) json_decode($base64UrlDecode($payload[0]));
$claims  = (array) json_decode($base64UrlDecode($payload[1]));
$signature = $base64UrlDecode($payload[2]);

$publicKey = openssl_get_publickey(file_get_contents('public.pem'));
$verified = openssl_verify($payload[0].'.'.$payload[1], $signature, $publicKey, OPENSSL_ALGO_SHA256);

if (!$verified || !isset($claims['exp']) || time() >= $claims['exp']) {
    die('Invalid JWT');
}

printf('Hello %s!', $claims['usr']); // Verified :)
