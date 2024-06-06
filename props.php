<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?
global $USER_FIELD_MANAGER;
// подгружаем номер карты
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$card_number = $request->get('CARD_NUMBER');
$ufAddressCode ='UF_ADDRESS_LIST';
if (SITE_ID == 's2')
    $ufAddressCode ='UF_ADDRESS_LIST_SEV';

$arAddress = $USER_FIELD_MANAGER->GetUserFields('USER', $USER->GetID())[$ufAddressCode];

foreach ($arAddress['VALUE'] as $address) {
    $data[] = json_decode($address, true);
}
foreach ($data as  $item){
    if ($item['main'] == 'true'){
        $porch = $item['porch'];
        $floor = $item['floor'];
    }
}
foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as &$oneP) {
    if ($oneP['CODE'] == 'CARD_NUMBER' && strlen($oneP['VALUE']) == 0) {
        $oneP['VALUE'] = $card_number;
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
?>
<?

function PrintPropsForm($arSource = Array(), $locationTemplate = ".default", $arOrderYesterday = array(), $arResult) {
    if (!empty($arSource)) {

        if (CModule::IncludeModule("sale")) {
            //$rsUser = CUser::GetByID(1);
            //$arUser = $rsUser->Fetch();
            //$couriers_count = $arUser['UF_CURIERS'] !== "" ? intval($arUser['UF_CURIERS']) : 0;

            $db_sales = CSaleOrder::GetList(
                array("DATE_INSERT" => "ASC"), array(
                    'PROPERTY_VAL_BY_CODE_DATE' => '1', // дата заказа СЕГОДНЯ
                    '>=DATE_INSERT' => date('d.m.Y 00:00:00'),
                    '<=DATE_INSERT' => date('d.m.Y 00:00:00', strtotime("+1 day"))
                )
            );
            while ($ar_sales = $db_sales->Fetch()) {
                $db_order = CSaleOrder::GetList(
                    array("DATE_UPDATE" => "DESC"), array("ID" => $ar_sales['ID'])
                );
                if ($arOrder = $db_order->Fetch()) {
                    //print_r($arOrder);
                    $db_vals = CSaleOrderPropsValue::GetList(
                        array("SORT" => "ASC"), array(
                            "ORDER_ID" => $ar_sales['ID'],
                            "CODE" => array("SLOT", "TT"), // временной слот СЕГОДНЯ и ТТ
                        )
                    );
                    $arProp = array();
                    while ($arVals = $db_vals->Fetch())
                        $arProp[$arVals["CODE"]] = $arVals;

                    if ($arProp["SLOT"]["VALUE"] != "" && $arProp["TT"]["VALUE"] == TT)
                        $slots[] = $arProp["SLOT"]['VALUE'];
                }
            }
        }


        $count = array_count_values($slots);

        if (CModule::IncludeModule("sale")) {
            $db_sales = CSaleOrder::GetList(
                array("DATE_INSERT" => "ASC"), array(
                    'PROPERTY_VAL_BY_CODE_DATE' => '2', // дата заказа ЗАВТРА
                    '>=DATE_INSERT' => date('d.m.Y 00:00:00'),
                    '<=DATE_INSERT' => date('d.m.Y 00:00:00', strtotime("+1 day"))
                )
            );

            while ($ar_sales = $db_sales->Fetch()) {
                $db_order = CSaleOrder::GetList(
                    array("DATE_UPDATE" => "DESC"), array("ID" => $ar_sales['ID'])
                );

                if ($arOrder = $db_order->Fetch()) {
                    $db_vals = CSaleOrderPropsValue::GetList(
                        array("SORT" => "ASC"), array(
                            "ORDER_ID" => $ar_sales['ID'],
                            "CODE" => array("SLOT_TOD", "TT") // временной слот ЗАВТРА и ТТ
                        )
                    );
                    $arProp = array();
                    while ($arVals = $db_vals->Fetch())
                        $arProp[$arVals["CODE"]] = $arVals;

                    if ($arProp["SLOT_TOD"]["VALUE"] != "" && $arProp["TT"]["VALUE"] == TT)
                        $slots2[] = $arProp["SLOT_TOD"]['VALUE'];
                }
            }
        }

        $count2 = array_count_values($slots2);

        foreach ($arSource as $arProperties) {
            if ($arProperties["SHOW_GROUP_NAME"] == "Y") {
                ?>
                <tr>
                    <td colspan="2">
                        <b><?= $arProperties["GROUP_NAME"] ?></b>
                    </td>
                </tr>
                <?
            }
            ?>
            <tr>
                <td align="right" valign="top" id="<?= $arProperties["CODE"] ?>">
                    <? if ($arProperties["CODE"] == 'CITY' || $arProperties["ID"] == 40 || $arProperties["ID"] == 65 || $arProperties["ID"] == 66): ?>
                    <? elseif ($arProperties["CODE"] == 'TT'): ?>
                    <? else: ?>
                        <?= $arProperties["NAME"] ?>:<?
                        if ($arProperties["REQUIED_FORMATED"] == "Y") {
                            ?><span class="sof-req">*</span><?
                        }
                    endif;
                    ?>
                </td>
                <td>
                    <?
                    global $APPLICATION;
                    if ($arProperties["TYPE"] == "CHECKBOX") {
                        ?>
                        <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="">
                        <input type="checkbox" name="<?= $arProperties["FIELD_NAME"] ?>"
                               id="<?= $arProperties["FIELD_NAME"] ?>" value="Y"<? if ($arProperties["CHECKED"] == "Y")
                            echo " checked"; ?>>
                        <?
                    } elseif ($arProperties["TYPE"] == "TEXT") {
                        if ($arProperties["ID"] == 40 || $arProperties["ID"] == 65 || $arProperties["ID"] == 66)
                            continue;
                        $required = '';
                        if ($arProperties['REQUIED'] == 'Y') {
                            $required = 'required';
                        }
                        ?>
                        <?php
                        if ($arProperties["CODE"] == 'CITY'):
                            ?>
                            <input class="<?= $required ?>" type="hidden" maxlength="250"
                                   size="<?= $arProperties["SIZE1"] ?>" value="<?= CITY ?>"
                                   name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>">
                        <?
                        elseif ($arProperties["CODE"] == 'TT'):
                            $ttValue = $_COOKIE['BITRIX_TT'];
                            if (strlen($_COOKIE['BITRIX_TT']) == 0 && strlen($_COOKIE['BITRIX_ADRESS']) > 0) {
                                $address = explode('д.', $_COOKIE['BITRIX_ADRESS']);
                                $dbElement = \Bitrix\Iblock\ElementTable::getList(['filter' => ['=NAME' => trim($address[0]), 'IBLOCK_ID' => 37]])->fetch();
                                $ttValue = $dbElement['XML_ID'];
                            }
                            ?>
                            <input class="<?= $required ?>"
                                   type="hidden"
                                   value="<?= $ttValue ?>"
                                   name="<?= $arProperties["FIELD_NAME"] ?>"
                                   id="<?= $arProperties["FIELD_NAME"] ?>"/>
                        <? elseif ($arProperties["CODE"] == 'ADDRESS' && $_COOKIE['BITRIX_ADRESS'] != ""):
                            $adres = $_COOKIE['BITRIX_ADRESS'];
                            ?>
                            <?
                            global $TTIblocks;
                            if (TT != '' && $TTIblocks["CATALOG_PRODUCT"] != 2) {
                                ?>
                                <input class="<?= $required ?>"
                                       type="hidden"
                                       value="<?= $adres ?>"
                                       name="<?= $arProperties["FIELD_NAME"] ?>"
                                       id="<?= $arProperties["FIELD_NAME"] ?>"/>
                                <textarea disabled="disabled"
                                          style="width: 163px;min-height: 70px;"><?= $adres ?></textarea>
                            <? } else { ?>
                                <input class="<?= $required ?>" type="text" maxlength="250"
                                       size="<?= $arProperties["SIZE1"] ?>" value="<?= $adres ?>"
                                       name="<?= $arProperties["FIELD_NAME"] ?>"
                                       id="<?= $arProperties["FIELD_NAME"] ?>">
                            <? } ?>
                        <? elseif ($arProperties["CODE"] == 'ADDRESS' && $_COOKIE['BITRIX_ADRESS'] == ""):
                            ?>
                            <input class="<?= $required ?>" type="text" maxlength="250"
                                   size="<?= $arProperties["SIZE1"] ?>" value="<?= $arProperties["VALUE"] ?>"
                                   name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>">
                        <?
                        else:
                            ?>
                            <input class="<?= $required ?>" type="text" maxlength="250"
                                   size="<?= $arProperties["SIZE1"] ?>" value="<?= $arProperties["VALUE"] ?>"
                                   name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>">
                        <?
                        endif;
                    } elseif ($arProperties["TYPE"] == "SELECT") {
                        ?>
                        <?php
                        if ($arProperties["CODE"] == 'ADDRESS' && SITE_ID == 's3'):
                            ?>
                            <select class="required" name="<?= $arProperties["FIELD_NAME"] ?>"
                                    id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                <option value='' selected>Выберите адрес</option>
                                <? foreach ($arProperties["VARIANTS"] as $arVariants): ?>
                                    <? /* if ($couriers_count > 0) : */ ?>
                                    <option value="<?= $arVariants["VALUE"] ?>">
                                        <?= $arVariants["NAME"] ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        <?
                        elseif ($arProperties["CODE"] == 'SLOT_TOD'):
                            ?>

                            <?
                            if (strlen($arResult['VIRTUAL_TIME_SLOT_HTML']['TOMORROW']) > 0):
                                echo $arResult['VIRTUAL_TIME_SLOT_HTML']['TOMORROW'];
                            else:
                                ?>
                                <select class="required" name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                    <option value='' selected>Выберите время</option>
                                    <? foreach ($arProperties["VARIANTS"] as $arVariants): ?>
                                        <?
                                        $tmp = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFieldValue("CUSTOM_TIMESLOT", "UF_TIMESLOT", $arVariants["VALUE"]);
                                        $couriers_count = intval($tmp) > 0 ? intval($tmp) : 0;

                                        $active = ($count2[$arVariants["VALUE"]] >= $couriers_count) ? "Y" : "N";

                                        if (SITE_ID === 's2') {
                                            if (date('d.m.Y') == '12.08.2019') {
                                                $active = 'N';
                                            }
                                        }
                                        ?>
                                        <? /* if ($couriers_count > 0) : */ ?>
                                        <option<? if ($active == "Y"): ?> disabled=""<? endif ?>
                                                value="<?= $arVariants["VALUE"] ?>">
                                            <?= $arVariants["NAME"] ?>
                                        </option>
                                        <? /* else:?>
                                          <option value="<?= $arVariants["VALUE"] ?>"><?= $arVariants["NAME"] ?></option>
                                          <?endif; */ ?>
                                    <? endforeach; ?>
                                </select>
                            <? endif; ?>
                        <?
                        else:
                            if (strlen($arResult['VIRTUAL_TIME_SLOT_HTML']['TODAY']) > 0):
                                echo $arResult['VIRTUAL_TIME_SLOT_HTML']['TODAY'];
                            else:
                                ?>
                                <select class="required" name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                    <option value='' selected>Выберите время</option>
                                    <? $hoursBeforeCloseSlot = 0; // за сколько часов закрывать слот. При необходимости задать в админке
                                    ?>
                                    <?
                                    $temp_variants = $arProperties["VARIANTS"];
                                    $temp_variants_last = array_pop($temp_variants);
                                    ?>
                                    <? foreach ($arProperties["VARIANTS"] as $arVariants): ?>
                                        <?
                                        // находим время, до которого можно занимать слот
                                        // приводим формат 11:00 - 13:00 к формату 9:00:00 (так как слот закрывается за 2 часа)
                                        $time = substr($arVariants["NAME"], 0, strpos($arVariants["NAME"], ' '));
                                        $maxOrderTime = date('H:i:s', strtotime($time) - 3600 * $hoursBeforeCloseSlot);

                                        //	прости меня будущий программист кому достался этот дибильный шаблон
                                        //	сделать его с нуля мне нельзя, а нагорожено здесь столько и так мощно, что без костылей никак :(
                                        // FWD: ничего страшного, видали шаблоны и намного хуже, даже кошмары снятся после них...
                                        $tmp = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFieldValue("CUSTOM_TIMESLOT", "UF_TIMESLOT", $arVariants["VALUE"]);
                                        $couriers_count = intval($tmp) > 0 ? intval($tmp) : 0;
                                        $tmpYstrd = $arOrderYesterday[$arVariants["VALUE"]]["CNT"]; // количество вчерашних заказов на сегодняшний временной слот

                                        $tmp2 = strtotime($GLOBALS["USER_FIELD_MANAGER"]->GetUserFieldValue("CUSTOM_TS_E_END", "UF_TS_E_END", $arVariants["VALUE"]));
                                        $maxOrderTime = date("H:i:s", $tmp2);

                                        if (intval($tmp2) > 0 && intval($tmp2) <= time()) {
                                            $couriers_count = 0;
                                        }

                                        unset($tmp2);
                                        ?>

                                        <? /* if($temp_variants_last != $arVariants): */ ?>
                                        <option<? if (($couriers_count == 0) || ($couriers_count > 0 && (in_array($arVariants["VALUE"], $slots)) && ($count[$arVariants["VALUE"]] >= ($couriers_count - $tmpYstrd))) || $maxOrderTime <= date('H:i:s') || ($couriers_count > 0 && ($couriers_count - $tmpYstrd) == 0)): ?> disabled=""<? endif ?>
                                                value="<?= $arVariants["VALUE"] ?>">
                                            <?= $arVariants["NAME"] ?>
                                        </option>
                                        <? /* else:?>
                                          <option
                                          <? if (	((in_array($arVariants["VALUE"], $slots)) && ($couriers_count > 0) && $count[$arVariants["VALUE"]] >= ($couriers_count - $tmpYstrd)) ||
                                          (date("19:00:00") <= date('H:i:s')) ||
                                          ($couriers_count > 0 && ($couriers_count - $tmpYstrd) <= 0)): ?> disabled<? endif ?> value="<?= $arVariants["VALUE"] ?>">
                                          <?= $arVariants["NAME"] ?>
                                          </option>
                                          <?endif; */ ?>
                                    <? endforeach; ?>
                                </select>
                            <?
                            endif;
                        endif;
                    } elseif ($arProperties["TYPE"] == "MULTISELECT") {
                        ?>
                        <select multiple name="<?= $arProperties["FIELD_NAME"] ?>"
                                id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                            <?
                            foreach ($arProperties["VARIANTS"] as $arVariants) {
                                ?>
                                <option value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["SELECTED"] == "Y")
                                    echo " selected"; ?>><?= $arVariants["NAME"] ?></option>
                                <?
                            }
                            ?>
                        </select>
                        <?
                    } elseif ($arProperties["TYPE"] == "TEXTAREA") {
                        ?>
                        <textarea rows="<?= $arProperties["SIZE2"] ?>" cols="<?= $arProperties["SIZE1"] ?>"
                                  name="<?= $arProperties["FIELD_NAME"] ?>"
                                  id="<?= $arProperties["FIELD_NAME"] ?>"><?= $arProperties["VALUE"] ?></textarea>
                        <?
                    } elseif ($arProperties["TYPE"] == "LOCATION") {
                        $value = 0;
                        foreach ($arProperties["VARIANTS"] as $arVariant) {
                            if ($arVariant["SELECTED"] == "Y") {
                                $value = $arVariant["ID"];
                                break;
                            }
                        }

                        $GLOBALS["APPLICATION"]->IncludeComponent(
                            "bitrix:sale.ajax.locations", $locationTemplate, array(
                            "AJAX_CALL" => "N",
                            "COUNTRY_INPUT_NAME" => "COUNTRY_" . $arProperties["FIELD_NAME"],
                            "REGION_INPUT_NAME" => "REGION_" . $arProperties["FIELD_NAME"],
                            "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                            "CITY_OUT_LOCATION" => "Y",
                            "LOCATION_VALUE" => $value,
                            "ORDER_PROPS_ID" => $arProperties["ID"],
                            "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                            "SIZE1" => $arProperties["SIZE1"],
                        ), null, array('HIDE_ICONS' => 'Y')
                        );
                    } elseif ($arProperties["TYPE"] == "RADIO") {
                        foreach ($arProperties["VARIANTS"] as $key => $arVariants) {
                            ?>
                            <input type="radio" name="<?= $arProperties["FIELD_NAME"] ?>"
                                   id="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>"
                                   value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["CHECKED"] == "Y")
                                echo " checked"; ?>> <label
                                    for="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>"><?= $arVariants["NAME"] ?>
                                - <?= date('d.m.Y', strtotime("+" . $key . ' days')) ?></label><br/>
                            <?
                        }
                    }

                    if (strlen($arProperties["DESCRIPTION"]) > 0) {
                        ?><br/><small><? echo $arProperties["DESCRIPTION"] ?></small><?
                    }
                    ?>

                </td>
            </tr>
            <?
        }
        ?>
        <?
        return true;
    }
    return false;
}

?>
<b><?= GetMessage("SOA_TEMPL_PROP_INFO") ?></b><br/>
<table class="sale_order_full_table">
    <tr>
        <td>
            <?
            if (!empty($arResult["ORDER_PROP"]["USER_PROFILES"])) {
                ?>
                <?= GetMessage("SOA_TEMPL_PROP_CHOOSE") ?><br/>
                <!--                <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
                                                                                                                                                                                    <option value="0"><?= GetMessage("SOA_TEMPL_PROP_NEW_PROFILE") ?></option>
                <?
                foreach ($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                        <option value="<?= $arUserProfiles["ID"] ?>"<? if ($arUserProfiles["CHECKED"] == "Y")
                        echo " selected"; ?>><?= $arUserProfiles["NAME"] ?></option>
                    <?
                }
                ?>
                                                                                                                                                                                </select>
                                                                                                                                                                                <br />
                                                                                                                                                                                <br />-->
                <?
            }
            ?>
            <div style="display:none;">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:sale.ajax.locations", ".default", array(
                    "AJAX_CALL" => "N",
                    "COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
                    "REGION_INPUT_NAME" => "REGION_tmp",
                    "CITY_INPUT_NAME" => "tmp",
                    "CITY_OUT_LOCATION" => "Y",
                    "LOCATION_VALUE" => "",
                    "ONCITYCHANGE" => "",
                ), null, array('HIDE_ICONS' => 'Y')
                );
                ?>
            </div>
            <table class="sale_order_full_table_no_border">
                <?
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"], $arResult["ORD_YESTERDAY"], $arResult);
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], $arResult["ORD_YESTERDAY"], $arResult);
                ?>
            </table>
        </td>
    </tr>
</table>
<br/><br/>