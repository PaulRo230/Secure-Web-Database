<?php

require_once 'config.php';


function aes_encrypt($plaintext) {
    $method = 'AES-256-CBC';
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $ciphertext_raw = openssl_encrypt(
        $plaintext,
        $method,
        AES_KEY,
        OPENSSL_RAW_DATA,
        $iv
    );

    return base64_encode($iv . $ciphertext_raw);
}

function aes_decrypt($ciphertext_b64) {
    $method = 'AES-256-CBC';
    $data = base64_decode($ciphertext_b64);
    $iv_length = openssl_cipher_iv_length($method);

    $iv = substr($data, 0, $iv_length);
    $ciphertext_raw = substr($data, $iv_length);

    return openssl_decrypt(
        $ciphertext_raw,
        $method,
        AES_KEY,
        OPENSSL_RAW_DATA,
        $iv
    );
}

function compute_hmac($clear_text, $ciphertext_b64) {
    return hash_hmac(
        'sha256',
        $clear_text . '|' . $ciphertext_b64,
        HMAC_KEY
    );
}

function verify_hmac($clear_text, $ciphertext_b64, $stored_hmac) {
    $calc = compute_hmac($clear_text, $ciphertext_b64);
    return hash_equals($calc, $stored_hmac);
}