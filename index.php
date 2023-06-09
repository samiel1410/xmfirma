<?php
require('vendor\robrichards\xmlseclibs\src\XMLSecurityDSig.php');
require('vendor\robrichards\xmlseclibs\src\XMLSecurityKey.php');

//covertir_firma
$filename = 'firma.p12';
$password = 'Flor2022';
$results = array();

//Transformar en pem
$worked = openssl_pkcs12_read(file_get_contents($filename), $results, $password);
file_put_contents('public.pem', $results['pkey']);




//recoger el certificado
file_put_contents('certificado.pem', $results['cert']);



//Crear xml

$xmlstr = '
<factura id="comprobante" version="1.1.0">
  <infoTributaria>
    <ambiente>1</ambiente>
    <tipoEmision>1</tipoEmision>
    <razonSocial>PATRICIO XAVIER CONSTANTE ERAZO</razonSocial>
    <nombreComercial>CONNECTASERVICES</nombreComercial>
    <ruc>1804008520001</ruc>
    <claveAcceso>0706202301180400852000110010010000000018736355013</claveAcceso>
    <codDoc>01</codDoc>
    <estab>001</estab>
    <ptoEmi>001</ptoEmi>
    <secuencial>000000001</secuencial>
    <dirMatriz>FICOA y la delicia 001</dirMatriz>
    <agenteRetencion>1</agenteRetencion>
    <contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>
  </infoTributaria>
  <infoFactura>
    <fechaEmision>07/06/2023</fechaEmision>
    <dirEstablecimiento>FICOA y la delicia 001</dirEstablecimiento>
    <obligadoContabilidad>NO</obligadoContabilidad>
    <tipoIdentificacionComprador>04</tipoIdentificacionComprador>
    <razonSocialComprador> DATACAM</razonSocialComprador>
    <identificacionComprador>1793161367001</identificacionComprador>
    <direccionComprador>QUITO - ELIA LIUT AV BRASIL EDIFICIO ARTEMISA OE3-1</direccionComprador>
    <totalSinImpuestos>22.04</totalSinImpuestos>
    <totalDescuento>0.00</totalDescuento>
    <totalConImpuestos>
      <totalImpuesto>
        <codigo>2</codigo>
        <codigoPorcentaje>0</codigoPorcentaje>
        <descuentoAdicional>0</descuentoAdicional>
        <baseImponible>22.04</baseImponible>
        <valor>0</valor>
      </totalImpuesto>
    </totalConImpuestos>
    <importeTotal>22.04</importeTotal>
    <moneda>DOLAR</moneda>
    <pagos>
      <pago>
        <formaPago>20</formaPago>
        <total>22.04</total>
      </pago>
    </pagos>
  </infoFactura>
  <detalles>
    <detalle>
      <codigoPrincipal>015</codigoPrincipal>
      <codigoAuxiliar>015</codigoAuxiliar>
      <descripcion>AJO SIN OLOR 500 MG*60 CAP</descripcion>
      <cantidad>4</cantidad>
      <precioUnitario>5.51</precioUnitario>
      <descuento>0.00</descuento>
      <precioTotalSinImpuesto>22.04</precioTotalSinImpuesto>
      <impuestos>
        <impuesto>
          <codigo>2</codigo>
          <codigoPorcentaje>0</codigoPorcentaje>
          <tarifa>0</tarifa>
          <baseImponible>22.04</baseImponible>
          <valor>0</valor>
        </impuesto>
      </impuestos>
    </detalle>
  </detalles>
  <infoAdicional>
    <campoAdicional nombre="Regimen">Contribuyente Regimen RIMPE</campoAdicional>
  </infoAdicional></factura>';

  $doc = new DOMDocument();
  $doc->loadXML($xmlstr);

// Save the public key in public.pem file

// Load the XML to be signed



// Create a new Security object 
$objDSig = new XMLSecurityDSig();
// Use the c14n exclusive canonicalization
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
// Sign using SHA-256
$objDSig->addReference(
    $doc, 
    XMLSecurityDSig::SHA1, 
    array('http://www.w3.org/2000/09/xmldsig#rsa-sha1')
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
$objDSig->sign($objKey);

// Add the associated public key to the signature
$objDSig->add509Cert(file_get_contents('certificado.pem'));

// Append the signature to the XML
$objDSig->appendSignature($doc->documentElement);



$firma = $objDSig->print_sign($objKey);


echo $firma;




//Linea para guardar documento
$doc->save('signed.xml');

// Sign the XML file
/*
$firma_signatura =$objDSig->sign($objKey);

echo "FIRMA SIGNATURE: <br>".$firma_signatura;
*/



?>