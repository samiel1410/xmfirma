<?php
require('vendor\robrichards\xmlseclibs\src\XMLSecurityDSig.php');
require('vendor\robrichards\xmlseclibs\src\XMLSecurityKey.php');

//covertir_firma
$filename = 'firma.p12';
$password = 'Flor2022';
$results = array();
$worked = openssl_pkcs12_read(file_get_contents($filename), $results, $password);


// Save the public key in public.pem file
file_put_contents('public.pem', $results['pkey']);
// Load the XML to be signed
$doc = new DOMDocument();
$doc->load('factura.xml');

// Create a new Security object 
$objDSig = new XMLSecurityDSig();
// Use the c14n exclusive canonicalization
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
// Sign using SHA-256
$objDSig->addReference(
    $doc, 
    XMLSecurityDSig::SHA1, 
    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
);

// Create a new (private) Security key
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
/*
If key has a passphrase, set it using
$objKey->passphrase = '<passphrase>';
*/
// Load the private key
$objKey->loadKey('public.pem', TRUE);

// Sign the XML file
$firma_signatura =$objDSig->sign($objKey);

echo $firma_signatura;




?>