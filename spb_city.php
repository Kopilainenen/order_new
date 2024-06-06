<?php

include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

function getContent($url) {
    $content = file_get_contents($url);
    return $content;
}

header('Content-Type: application/json');
$address = $_GET['city'];
//$xml = simplexml_load_file('https://geocode-maps.yandex.ru/1.x/?geocode='.$address);
if (strstr(strtolower($address), 'кудрово') === false) {
    $address = 'Кудрово ' . $address;
}
$d = getContent('https://geocode-maps.yandex.ru/1.x/?geocode=' . $address);
$xml = simplexml_load_string($d, 'SimpleXMLElement', LIBXML_NOCDATA);
for ($i = 0; $i < count($xml->GeoObjectCollection->featureMember); $i++) {
    $kind = $xml->GeoObjectCollection->featureMember[$i]->GeoObject->metaDataProperty->GeocoderMetaData->kind;
    if ($kind == 'street' || $kind == 'house') {
        $components = (array) $xml->GeoObjectCollection->featureMember[$i]->GeoObject->metaDataProperty->GeocoderMetaData->Address;
        $isNeedCity = false;
        for ($j = 0; $j < count($components['Component']); $j++) {

            if ($components['Component'][$j]->kind == 'locality'):
                $city = strtolower($components['Component'][$j]->name);
                if (strstr($city, 'кудрово') !== false) {
                    $isNeedCity = true;
                    $returnCity = $city;
                }
            endif;
            if ($components['Component'][$j]->kind == 'street'):
                $street = strtolower($components['Component'][$j]->name);
                if (strstr($street, 'строителей') !== false || strstr($street, 'областная') !== false || strstr($street, 'ленинградская') !== false 
                        || strstr($street, 'каштановая') !== false || strstr($street, 'березовая') !== false || strstr($street, 'дубовая') !== false || strstr($street, 'берёзовая') !== false) {
                    $isNeedStreet = true;
                    $returnStreet = $street;
                }
            endif;
        }
        if ($isNeedCity && $isNeedStreet) {
            echo json_encode(array('error' => 0, 'street' => $returnStreet, 'city' => $returnCity));
            die();
        }
    }
}
echo json_encode(array('error' => 1));
