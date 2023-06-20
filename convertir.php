<?php
$filename = 'firma.p12';
$password = 'Flor2022';
$results = array();
$worked = openssl_pkcs12_read(file_get_contents($filename), $results, $password);
if($worked) {
    echo '<pre>', print_r($results, true), '</pre>';
} else {
    echo openssl_error_string();
}



$new_password = null;
$result = null;
$worked = openssl_pkey_export($results['pkey'], $result, $new_password);
if($worked) {
    echo "<pre>It worked!  Your new pkey is:\n", $result, '</pre>';
} else {
    echo openssl_error_string();
}


// Save the public key in public.pem file
file_put_contents('public.pem', $result);
?>