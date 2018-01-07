<?php

include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'document.php';

$Document = new WDoc();

$Document->title = 'dove siamo';

$Document->main = '<div class="col-md-12" id="map" style="height: 500px"></div>' . "\r\n";

$Document->main .= '<script>' . "\r\n";
$Document->main .= 'var map;' . "\r\n";
$Document->main .= 'function initMap() {' . "\r\n";
$Document->main .= "map = new google.maps.Map(document.getElementById('map'), {\r\n";
$Document->main .= 'center: {lat: -34.397, lng: 150.644},' . "\r\n";
$Document->main .= 'zoom: 8' . "\r\n";
$Document->main .= '});' . "\r\n";
$Document->main .= '}' . "\r\n";
$Document->main .= '</script>' . "\r\n";

$Document->main .= '<script src="https://maps.googleapis.com/maps/api/js?callback=initMap"
async defer></script>' . "\r\n";

$Document->getDocument();

?>
