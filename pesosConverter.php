<?php
$respuesta = array();

$USD=$_GET["USD"];

if ($USD=="") {
    $respuesta["resultado"]="error";
    $respuesta["mensaje"]="falta precio en dolares";
    print_r(json_encode($respuesta));
    die();
}

$html = file_get_contents('http://portal.banamex.com.mx/c719_004/divisasMetales/es/resumen?xhost=http://www.banamex.com/');

$dom = new DOMDocument;

$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$consultaDllInter = "/html/body/div/div/div/div/div/table[1]/tbody/tr[3]/td[3]";
$ejecutaDllInter=$xpath->query($consultaDllInter);

if (!is_null($ejecutaDllInter)) {
  foreach ($ejecutaDllInter as $element) {
    $precioInterbancario = doubleval($element->nodeValue);
  }
}

$consultaDll = "/html/body/div/div/div/div/div/table[1]/tbody/tr[3]/td[2]";
$ejecutaDll=$xpath->query($consultaDll);

if (!is_null($ejecutaDll)) {
  foreach ($ejecutaDll as $element) {
    $precioBanamex = doubleval($element->nodeValue);
  }
}

$precioInterbancario=$precioInterbancario * $USD;
//echo $html;
$precioBanamex=$precioBanamex * $USD;
//print_r($precio);

$respuesta["Interbancario"]=$precioInterbancario;
$respuesta["Banamex"]=$precioBanamex;

print_r(json_encode($respuesta));

?>