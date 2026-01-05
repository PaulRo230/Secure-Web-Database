<?php
require_once 'crypto.php';

$plaintext = "Hello Secure World!";
$cipher = aes_encrypt($plaintext);
$hmac = compute_hmac($cipher);

echo "Plaintext: $plaintext<br>";
echo "Ciphertext: $cipher<br>";
echo "HMAC: $hmac<br>";
echo "Decrypted: " . aes_decrypt($cipher) . "<br>";
echo "HMAC Verified: " . (verify_hmac($cipher, $hmac) ? "YES" : "NO");