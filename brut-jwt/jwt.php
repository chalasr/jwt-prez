<?php

$base64UrlEncode = function (string $json) {
    return str_replace('=', '', strtr(base64_encode($json), '+/', '-_'));
};

$headers = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
$claims  = json_encode(['usr' => 'chalasr', 'exp' => time() + 3600, 'iat' => time()]);
$payload = [$base64UrlEncode($headers), $base64UrlEncode($claims)];

$privateKey = openssl_get_privatekey(file_get_contents(__DIR__.'/private.pem'), 'jwt-demo');
$signature = '';
openssl_sign(implode('.', $payload), $signature, $privateKey, OPENSSL_ALGO_SHA256);

$payload[] = $base64UrlEncode($signature);
$token = implode('.', $payload);

print $token; // JWT :)
