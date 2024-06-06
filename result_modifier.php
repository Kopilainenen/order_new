<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arProductIds = array_column($arResult['BASKET_ITEMS'], 'PRODUCT_ID');
$arResult['MEASURE'] = \Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($arProductIds);

//if($USER->isAdmin())
//echo '<pre>'; print_r($arResult['MEASURE']); echo '</pre>';

$arResult["CARD"] = CSiteUtils::checkUserCard();
// get virtual time slots
$arResult['VIRTUAL_TIME_SLOTS'] = getVirtualTimeSlots();

// LEEFT START
// Собираем все товары
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array(
        "ID", "CALLBACK_FUNC", "PRODUCT_PROVIDER_CLASS", "MODULE",
        "PRODUCT_ID", "QUANTITY", "DELAY",
        "CAN_BUY", "PRICE", "WEIGHT",
    )
);
// Обновляем цены и наличие
while ($arItems = $dbBasketItems->Fetch()) {
    // Если товар нельзя купить - удаляем его из корзины
    if ($arItems['CAN_BUY'] == "N")
        CSaleBasket::Delete($arItems['ID']);
}
// Чистим переменные
unset($dbBasketItems, $arItems);
//LEEFT END
global $TTIblocks;
$currentIblock = $TTIblocks["CATALOG_PRODUCT"];
if (CModule::IncludeModule("iblock")) :
    $arSelect = array('ID', 'PROPERTY_weight', 'PROPERTY_B_CATALOG_PRICE_CASHBACK');
    foreach ($arResult["BASKET_ITEMS"] as $key => $arItem) {
        $arFilter = array('IBLOCK_ID' => $TTIblocks["CATALOG_PRODUCT"], 'ID' => $arItem['PRODUCT_ID']);
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($db_res = $res->GetNext()) :
            $arResult['BASKET_ITEMS'][$key]['we'] = $db_res['PROPERTY_WEIGHT_VALUE'];
        endwhile;
    }


    //какие товары отделять в отчёте
    $IBLOCK_ID = array(22, 19);
    $arSelect = array("ID", "NAME", "IBLOCK_ID", 'PROPERTY_B_CATALOG_PRICE_CASHBACK', "PROPERTY_SEPARATE_IN_REPORT", "PROPERTY_SEPARATE_IN_REPORT_FARMA");
    $arFilter = array("IBLOCK_ID" => $IBLOCK_ID);
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $arFields = "";
    $petromos_cafe = array();
    $petromos_pharmacy = array();
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        if ($arFields['IBLOCK_ID'] == 22) {
            $petromos_cafe[] = $arFields;
        }
        if ($arFields['IBLOCK_ID'] == 19) {
            $petromos_pharmacy[] = $arFields;
        }
    }
    for ($i = 0; $i < count($arResult["BASKET_ITEMS"]); $i++)
        $arResult["BASKET_ITEMS"][$i]["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_VALUE"] = "";
    $use_cafe_separator = 0;
    $use_pharmacy_separator = 0;
    for ($i = 0; $i < count($arResult["BASKET_ITEMS"]); $i++) {
        for ($j = 0; $j < count($petromos_cafe); $j++) {
            if ($arResult["BASKET_ITEMS"][$i]["PRODUCT_ID"] == $petromos_cafe[$j]["ID"]) {
                $arResult["BASKET_ITEMS"][$i]["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_VALUE"] = $petromos_cafe[$j]["PROPERTY_SEPARATE_IN_REPORT_VALUE"];
                $use_cafe_separator++;
                break;
            }
        }
        for ($m = 0; $m < count($petromos_pharmacy); $m++) {
            if ($arResult["BASKET_ITEMS"][$i]["PRODUCT_ID"] == $petromos_pharmacy[$m]["ID"]) {
                $arResult["BASKET_ITEMS"][$i]["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_FARMA_VALUE"] = $petromos_pharmacy[$m]["PROPERTY_SEPARATE_IN_REPORT_FARMA_VALUE"];
                $use_pharmacy_separator++;
                break;
            }
        }
    }

    //разбираем, а затем собираем массив
    function sort_by_name($a, $b)
    {
        return (strcmp($a['NAME'], $b['NAME']));
    }

    $from_catalog = array();
    if ($use_cafe_separator > 0 || $use_pharmacy_separator > 0) {
        $from_cafe = array();
        $from_pharmacy = array();
        foreach ($arResult["BASKET_ITEMS"] as $basket_item) {
            if ($basket_item["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_VALUE"] == "Y")
                $from_cafe[] = $basket_item;
            elseif ($basket_item["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_FARMA_VALUE"] == "Y")
                $from_pharmacy[] = $basket_item;
            else
                $from_catalog[] = $basket_item;
        }
        if ($use_cafe_separator > 0)
            $arResult["CAFE_SEPARATOR"] = true; // добавлять разделитель в корзину
        if ($use_pharmacy_separator > 0)
            $arResult["PHARMACY_SEPARATOR"] = true; // добавлять разделитель в корзину
        uasort($from_catalog, 'sort_by_name');
        uasort($from_pharmacy, 'sort_by_name');
        uasort($from_cafe, 'sort_by_name');
        $merga = array_merge($from_catalog, $from_pharmacy);
        $arResult["BASKET_ITEMS"] = array_merge($merga, $from_cafe);
    }
endif;
$arResult['SHOW_ATTENTION'] = false;
$pieTotalPrice = 0;
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

$arResult['AGE_18'] = false;
foreach ($arResult["BASKET_ITEMS"] as $keys => $arItem) {

    $diffPrice = $discountPercent = 0;
    $basketItem = $basket->getItemById($arItem['ID']);
    if ($basketItem !== null)
        $arItem['CUSTOM_PRICE'] = $basketItem->isCustomPrice();

    if ($arItem['CUSTOM_PRICE'])
        $arResult['SHOW_ATTENTION'] = true;

    $element = \Bitrix\Iblock\ElementTable::getById($arItem['PRODUCT_ID'])->fetch();

    //$arItem['IS_PACKAGE'] = $element['XML_ID'] == CBasketActions::PACKAGE;
    $arItem['IS_PACKAGE'] = $element['XML_ID'] == CBasketActions::getPackageFromHL($xml = true);

    $nav = CIBlockSection::GetNavChain($element['IBLOCK_ID'], $element['IBLOCK_SECTION_ID'])->Fetch();
    $arResult['SECTION'][$nav['ID']] = $nav['NAME'];
    $cardPrice = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'CART_PRICE'])->Fetch()['VALUE'];
    $oldPrice = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'OLD_PRICE'])->Fetch()['VALUE'];
    $unit = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], [], ['CODE' => 'weight'])->Fetch()['VALUE_ENUM'];
    $cashback = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'B_CATALOG_PRICE_CASHBACK'])->Fetch()['VALUE'];
    $cashback_date = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'CATALOG_PRICE_CASHBACK_EXPIRATIONDATE'])->Fetch()['VALUE'];

    if (!array_key_exists($element['ID'], $hasPicId) && $element['IBLOCK_ID'] != 52) {

        if ($element['IBLOCK_ID'] != 43) {
            $filter = ['XML_ID' => $element['XML_ID'], 'IBLOCK_ID' => 43];
            $res = \Bitrix\Iblock\ElementTable::getList(['filter' => $filter])->fetch();
            if ($res['DETAIL_PICTURE'] > 0) {
                $file = CFile::ResizeImageGet($res["DETAIL_PICTURE"], ['width' => 300, 'height' => 80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
                $arItem['DETAIL_PICTURE'] = $file;
            }
        } elseif ($element['DETAIL_PICTURE'] > 0) {
            $file = CFile::ResizeImageGet($element["DETAIL_PICTURE"], ['width' => 300, 'height' => 80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $arItem['DETAIL_PICTURE'] = $file;
        }
        $arItem['NEW_DETAIL_PICTURE'] = false;
        $picture = CSiteUtils::getProductPicture($element['XML_ID'], array('width' => 300, 'height' => 80));
        if (strlen($picture) > 0) {
            $arItem['DETAIL_PICTURE'] = $picture;
            $arItem['NEW_DETAIL_PICTURE'] = true;
        }
        $hasPicId[$element['ID']] = $element['ID'];
    } elseif ($element['PREVIEW_PICTURE'] > 0) {
        $arItem['COMPOSITION'] = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'COMPOSITION'])->Fetch()['VALUE'];
        $arItem['CALORIE'] = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'CALORIE'])->Fetch()['VALUE'];
        $arItem['VES'] = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], 'by', 'asc', ['CODE' => 'VES'])->Fetch()['VALUE'];


        $file = CFile::ResizeImageGet($element["PREVIEW_PICTURE"], ['width' => 140, 'height' => 110], BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arItem['DETAIL_PICTURE'] = $file;
    }

    if ($oldPrice > 0) {
        $diffPrice = ($oldPrice - $arItem['BASE_PRICE']) * $arItem['QUANTITY'];
    } elseif ($cardPrice > 0) {
        $diffPrice = ($arItem['BASE_PRICE'] - $cardPrice) * $arItem['QUANTITY'];
        $discountPercent = (int)((($arItem['BASE_PRICE'] - $cardPrice) / $arItem['BASE_PRICE']) * 100);
    }

    if ($element['IBLOCK_ID'] == 52) {
        //$arResult['PIES'][] = $arItem;
        //$pieTotalPrice += ($arItem['PRICE'] * $arItem['QUANTITY']);
        //unset($arResult["BASKET_ITEMS"][$keys]);
        //continue;
    }
    $totalPrice += ($arItem['PRICE'] * $arItem['QUANTITY']);
    $arResult['PRODUCTS_BY_SECTIONS'][$nav['ID']][] = $arItem;
    $arResult['PRODUCTS'][$element['ID']] = [
        'CART_PRICE' => $cardPrice,
        'OLD_PRICE' => $oldPrice,
        'DIFF' => $diffPrice,
        'PERCENT' => $discountPercent,
        'WE' => $unit,
        'CASHBACK' => $cashback,
        'CASHBACK_DATE' => $cashback_date,
    ];


    $arSections = ['Табачные изделия и аксессуары', 'Энергетические напитки'];
    $iblockElement = \Bitrix\Iblock\ElementTable::getById($arItem['PRODUCT_ID'])->fetch();
    if ($iblockElement['IBLOCK_SECTION_ID'] > 0) {
        $section = \Bitrix\Iblock\SectionTable::getById($iblockElement['IBLOCK_SECTION_ID'])->fetch();
        if (in_array($section['NAME'], $arSections)) {
            $arResult['AGE_18'] = true;
        }
    }
}
$arResult['PIE_ORDER_PRICE_FORMATED'] = CCurrencyLang::CurrencyFormat($pieTotalPrice, 'RUB');

$arResult['ORDER_PRICE_FORMATED'] = CCurrencyLang::CurrencyFormat($totalPrice, 'RUB');
$totalPrice += $arResult['DELIVERY_PRICE'];
$arResult['ORDER_TOTAL_PRICE_FORMATED'] = CCurrencyLang::CurrencyFormat($totalPrice, 'RUB');
//print_var($arResult['PRODUCTS_BY_SECTIONS'], $USER->IsAdmin());

global $USER_FIELD_MANAGER;
// подгружаем номер карты
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$card_number = $_SESSION['CARD'];
$ufAddressCode = 'UF_ADDRESS_LIST';
if (SITE_ID == 's2')
    $ufAddressCode = 'UF_ADDRESS_LIST_SEV';
if (SITE_ID == 's3')
    $ufAddressCode = 'UF_ADDRESS_LIST_NOV';

$arAddress = $USER_FIELD_MANAGER->GetUserFields('USER', $USER->GetID())[$ufAddressCode];

foreach ($arAddress['VALUE'] as $address) {
    $data[] = json_decode($address, true);
}

foreach ($data as $item) {
    if ($item['main'] == 'true') {
        $porch = $item['porch'];
        $floor = $item['floor'];
        $arResult['MAIN_ADDRESS'] = $item;
    }
}
foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as &$oneP) {
    if ($oneP['CODE'] == 'CARD_NUMBER') {
        $oneP['VALUE'] = $_SESSION["CARD"];
    }
    if ($oneP['CODE'] == 'PODJEZD') {
        $oneP['VALUE'] = $porch;
    }
    if ($oneP['CODE'] == 'FLOOR') {
        $oneP['VALUE'] = $floor;
    }
    //$oneP['VALUE'] = ($oneP['CODE'] == 'CARD_NUMBER' && empty($oneP['VALUE'])) ? $card_number : '';
}
foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as &$oneP) {
    if ($oneP['CODE'] == 'CARD_NUMBER') {
        $oneP['VALUE'] = $_SESSION["CARD"];
    }
    if ($oneP['CODE'] == 'PODJEZD') {
        $oneP['VALUE'] = $porch;
    }
    if ($oneP['CODE'] == 'FLOOR') {
        $oneP['VALUE'] = $floor;
    }
    //$oneP['VALUE'] = ($oneP['CODE'] == 'CARD_NUMBER' && empty($oneP['VALUE'])) ? $card_number : '';
}
unset($oneP);

global $arZone;


$arResult['STORE_LIST'] = CSiteUtils::getPickupList();
if ($arZone['ID'] > 0 && $_REQUEST['ORDER_ID'] == 0) {



    $zoneExpress = $arZone['EXPRESS'] == 'Y';
    $arResult['ZONE'] = $arZone['NAME'];
    foreach ($arResult["PAY_SYSTEM"] as $k => $arPaySystem) {
        if (!in_array($arPaySystem["ID"], $arZone['PAYMENTS']))
            unset($arResult["PAY_SYSTEM"][$k]);
    }

    foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery) {

        if ($arDelivery['SID'] == 'new20') {
            $arDelivery["ID"] = str_replace('new', '', $arDelivery['SID']);
            $arDelivery["NAME"] = $arDelivery["TITLE"];
            $arDelivery["FIELD_NAME"] = 'DELIVERY_ID';
        }
        if ($arDelivery['SID'] == 'new25') {
            $arDelivery["ID"] = str_replace('new', '', $arDelivery['SID']);
            $arDelivery["NAME"] = $arDelivery["TITLE"];
            $arDelivery["FIELD_NAME"] = 'DELIVERY_ID';
        }
        if (!$zoneExpress && $arDelivery['ID'] == CSiteUtils::$deliveryExpress) {
            unset($arResult["DELIVERY"][$delivery_id]);
            continue;
        }



        $zoneFrom = $zoneTo = '';
        $curDate = new \Bitrix\Main\Type\DateTime();
        if ($arZone['EXPRESS_FROM'])
            $zoneFrom = strtotime(date('d.m.Y ' . $arZone['EXPRESS_FROM'] . ':00'));
        if ($arZone['EXPRESS_TO'])
            $zoneTo = strtotime(date('d.m.Y ' . $arZone['EXPRESS_TO'] . ':00'));

        if ($arDelivery['ID'] == CSiteUtils::$deliveryExpress && $zoneFrom && $zoneTo &&
            ($zoneFrom >= $curDate->getTimestamp() ||  $zoneTo <= $curDate->getTimestamp())) {
            unset($arResult["DELIVERY"][$delivery_id]);
            continue;
        }
        if (!$zoneExpress && $arDelivery['ID'] == CSiteUtils::$deliverySevskExpress) {
            unset($arResult["DELIVERY"][CSiteUtils::$deliverySevskExpress]);
            continue;
        }


        if (!in_array($arDelivery["ID"], $arZone['DELIVERY'])) {
            unset($arResult["DELIVERY"][$delivery_id]);
            continue;
        }

        //unset($arResult['DELIVERY'][$delivery_id]);
    }
}

//print_var($arResult["DELIVERY"], $USER->IsAdmin());
$arResult["ZONE"] = $arZone;

$userId = $USER->GetID();
if ($userId) {
    $parameters = [
        'select' => ['EMAIL', 'PERSONAL_PHONE'],
        'filter' => [
            'ID' => $userId
        ]
    ];

    $rsData = \Bitrix\Main\UserTable::getList($parameters);

    if ($arData = $rsData->fetch()) {
        $arResult['USER_DATA'] = $arData;
    }
}

$ttId = $_COOKIE['ADDRESS_ID'];
if ($ttId > 0) {
    if ($_COOKIE['DELIVERY'] == 'DELIVERY') {
        $parameters = [
            'select' => ['XML_ID'],
            'filter' => [
                'ID' => $ttId
            ]
        ];
        $dbElement = \Bitrix\Iblock\ElementTable::getList($parameters)->fetch();
        if ($dbElement['XML_ID']) {
            $ttValue = $dbElement['XML_ID'];
        }
    } else {
        $parameters = [
            'select' => ['UF_NUMBER_SHOP', 'UF_TT'],
            'filter' => [
                'ID' => $ttId
            ]
        ];
        $arData = \Bitrix\Catalog\StoreTable::getList($parameters)->fetch();
        if ($arData['UF_TT']) {
            $ttValue = $arData['UF_TT'];
        }
    }
    if ($ttValue) {
        $arResult['SHOW_EXPRESS'] = LimitSlotsTable::checkExpressToday($ttValue) ? "Y" : "N";


        $date = new DateTime();

        $dateTomorrow = new DateTime();
        $dateTomorrow->modify("+ 1 day");

        $dateAfterTomorrow = new DateTime();
        $dateAfterTomorrow->modify("+ 2 day");

        $parametersLimit = [
            'order' => ['DATE_LIMIT' => 'DESC'],
            'filter' => [
                'TT' => $ttValue,
                'ACTIVE' => 'Y',
                'SITE' => '',
                'DATE_LIMIT' => [
                    $date->format('d.m.Y'),
                    $dateTomorrow->format('d.m.Y'),
                    $dateAfterTomorrow->format('d.m.Y')
                ],
                'ZONE_ID' => $arZone['ID']
            ],
        ];
        $arResult['DATE_FULL_BLOCK'] = $arResult['BLOCK_SLOTS'] = $arResult['SLOTS_LIMIT'] = [];
        $lists = LimitSlotsTable::getList($parametersLimit)->fetchAll();
        foreach ($lists as $list) {
            $list['SLOTS_ZONE'] = json_decode($list['ZONE_SLOTS_IDS'], true);
            if (!$list['SLOTS_ZONE']) {
                $arResult['DATE_FULL_BLOCK'][] = $list['DATE_LIMIT']->format('d.m.Y');
            }
            foreach ($list['SLOTS_ZONE'] as $slotId) {
                $list['SLOTS'][] = SlotsTable::getById($slotId)->fetch()['SLOT'];
            }

            if ($list['SLOTS'])
                $arResult['BLOCK_SLOTS'][$arZone['ID']][$list['DATE_LIMIT']->format('d.m.Y')] = $list['SLOTS'];
            $arResult['SLOTS_LIMIT'][$arZone['ID']][$list['DATE_LIMIT']->format('d.m.Y')] = $list;
        }


        if (!$list) {
            $parametersLimit = [
                'order' => ['DATE_LIMIT' => 'DESC'],
                'filter' => [
                    'TT' => $ttValue,
                    'ACTIVE' => 'Y',
                    //'SITE' => SITE_ID,
                    'DATE_LIMIT' => [
                        $date->format('d.m.Y'),
                        $dateTomorrow->format('d.m.Y'),
                        $dateAfterTomorrow->format('d.m.Y')
                    ],
                    'SITE' => '',
                ],
            ];
            $lists = LimitSlotsTable::getList($parametersLimit)->fetchAll();
            foreach ($lists as $list) {
                $list['SLOTS_ZONE'] = json_decode($list['ZONE_SLOTS_IDS'], true);
                if (!$list['SLOTS_ZONE']) {
                    $arResult['DATE_FULL_BLOCK'][] = $list['DATE_LIMIT']->format('d.m.Y');
                }
            }
        }

    }
}

if ($arResult['MAIN_ADDRESS']) {
    $parameters = [
        'select' => [
            'ID', 'PROPERTY_STREET',
            'PROPERTY_CITY', 'XML_ID',
            'PROPERTY_HOUSE_FROM', 'PROPERTY_HOUSE_TO',
            'PROPERTY_HOUSE_ORDER'
        ],
        'filter' => [
            'IBLOCK_ID' => 37,
            'ACTIVE' => 'Y',
            'ID' => $arResult['MAIN_ADDRESS']['ttId'],
        ],
    ];

    $rsData = CIBlockElement::GetList($parameters['order'], $parameters['filter'], false, $parameters['nav'], $parameters['select'])->Fetch();
    $arResult['MAIN_ADDRESS']['HOUSE_FROM'] = $rsData['PROPERTY_HOUSE_FROM_VALUE'];
    $arResult['MAIN_ADDRESS']['HOUSE_TO'] = $rsData['PROPERTY_HOUSE_TO_VALUE'];
}
