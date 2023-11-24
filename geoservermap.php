<?php
// $wfsUrl =
// file_get_contents("http://geoportal.slemankab.go.id/geoserver/geonode/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=geonode:analisis_resiko_bencana_gunung_api&outputFormat=json");

$wfsUrl =
file_get_contents("http://localhost:8080/geoserver/diy/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=diy%3AADMINISTRASIKECAMATAN_AR_25K&maxFeatures=50&outputFormat=application%2Fjson");

header('Content-type: application/json');
echo ($wfsUrl);
?>