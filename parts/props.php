<?php
global $TTIblocks;
?>
<div class="checkout-categories-item js-checkout-category closed" data-category="props">
    <div class="checkout-categories-item-head js-checkout-head">
        <div class="checkout-categories-item-head__title">
            <?= $number; ?>.Введите информацию о покупателе
        </div>
        <i class="far fa-check-circle check-ok"></i>
        <!--        <div class="checkout-categories-item-head__arrow">-->
        <!--        </div>-->
    </div>
    <div class="checkout-categories-item-body">
        <?
        foreach ($arResult["ORDER_PROP"] as $key => $propsValues) {
            if ($key != 'USER_PROPS_Y' && $key != 'USER_PROPS_N')
                continue;
            foreach ($arResult["ORDER_PROP"][$key] as $arProperties) {

                if (strlen($_REQUEST['ORDER_PROP_' . $arProperties['ID']]) > 0) {
                    $arProperties['VALUE'] = $_REQUEST['ORDER_PROP_' . $arProperties['ID']];
                }
                if ($arProperties['CODE'] == 'FIO')
                    $fio = $arProperties;
                if ($arProperties['CODE'] == 'EMAIL')
                    $email = $arProperties;
                if ($arProperties['CODE'] == 'PHONE')
                    $phone = $arProperties;
                if ($arProperties['CODE'] == 'ADDRESS')
                    $address = $arProperties;
                if ($arProperties['CODE'] == 'CITY')
                    $city = $arProperties;
                if ($arProperties['CODE'] == 'PODJEZD')
                    $porch = $arProperties;
                if ($arProperties['CODE'] == 'FLOOR')
                    $floor = $arProperties;
                if ($arProperties['CODE'] == 'DATE')
                    $date = $arProperties;
                if ($arProperties['CODE'] == 'CARD_NUMBER')
                    $card = $arProperties;
            }
        }

        if (strlen($_REQUEST['house-checkout']) > 0)
            $arResult['MAIN_ADDRESS']['house'] = $_REQUEST['house-checkout'];
        if (strlen($_REQUEST['korpus-checkout']) > 0)
            $arResult['MAIN_ADDRESS']['korpus'] = $_REQUEST['korpus-checkout'];
        if (strlen($_REQUEST['flat-checkout']) > 0)
            $arResult['MAIN_ADDRESS']['flat'] = $_REQUEST['flat-checkout'];
        if (strlen($_REQUEST['comment-checkout']) > 0)
            $arResult['MAIN_ADDRESS']['comment'] = $_REQUEST['comment-checkout'];


        ?>
        <div class="checkout-categories-item-body-about-row">
            <label class="profile-content-form__input">
                <input class="inp <?= $fio['REQUIED'] == 'Y' ? 'required' : '' ?>" type="text" maxlength="250"
                    size="<?= $fio["SIZE1"] ?>" value="<?= $fio["VALUE"] ?>" name="<?= $fio["FIELD_NAME"] ?>"
                    id="<?= $fio["FIELD_NAME"] ?>">
                <span><?= $fio["NAME"] ?></span>
            </label>
        </div>
        <div class="checkout-categories-item-body-about-row">
            <label class="profile-content-form__input">
                <?
                $isDisabledPhone = false;
                if ($arResult['USER_DATA']['PERSONAL_PHONE']) {
                    $phone["VALUE"] = $arResult['USER_DATA']['PERSONAL_PHONE'];
                    $isDisabledPhone = true;
                }
                global $USER;
                if ($USER->IsAdmin())
                    $isDisabledPhone = false;
                ?>
                <input class="inp <?= $phone['REQUIED'] == 'Y' ? 'required' : '' ?>"
                    <?= ($isDisabledPhone) ? 'readonly' : '' ?> type="text" maxlength="250"
                    size="<?= $phone["SIZE1"] ?>" value="<?= $phone["VALUE"] ?>" name="<?= $phone["FIELD_NAME"] ?>"
                    id="<?= $phone["FIELD_NAME"] ?>">
                <span><?= $phone["NAME"] ?></span>
            </label>
            <label class="profile-content-form__input">
                <?
                $isDisabledEmail = false;
                if ($arResult['USER_DATA']['EMAIL']) {
                    $email["VALUE"] = $arResult['USER_DATA']['EMAIL'];
                    $isDisabledEmail = true;
                }
                // if ($USER->IsAdmin())
                //     $isDisabledEmail = false;
                ?>
                <input class="inp <?= $email['REQUIED'] == 'Y' ? 'required' : '' ?>"
                    <?= ($isDisabledEmail) ? 'readonly' : '' ?> type="text" maxlength="250"
                    size="<?= $email["SIZE1"] ?>" value="<?= $email["VALUE"] ?>" name="<?= $email["FIELD_NAME"] ?>"
                    id="<?= $email["FIELD_NAME"] ?>">
                <span><?= $email["NAME"] ?></span>
            </label>
        </div>
        <div id="delivery-props">
            <div class="checkout-categories-item-body-about-row__title">
                Адрес доставки
            </div>
            <?

            if ($_COOKIE['DELIVERY'] == 'DELIVERY' || !isset($_COOKIE['DELIVERY'])) {
                $addressValue = '';
                if ($arResult['MAIN_ADDRESS']['city_format'] && strpos($arResult['MAIN_ADDRESS']['street'], $arResult['MAIN_ADDRESS']['city_format']) === false) {

                    $ufAddressCode = 'Архангельск';
                    if (SITE_ID == 's2')
                        $ufAddressCode = 'Северодвинск';
                    if (SITE_ID == 's3')
                        $ufAddressCode = 'Новодвинск';


                    if (!$arResult['MAIN_ADDRESS']['isRegion']) {
                        $addressValue .= "г. ";
                    }
                    $addressValue .= $ufAddressCode . ", ";
                }
                if ($arResult['MAIN_ADDRESS']['street']) {
                    $addressValue .= $arResult['MAIN_ADDRESS']['street'] . ", ";
                }
                if ($arResult['MAIN_ADDRESS']['house']) {
                    $addressValue .= "д. " . $arResult['MAIN_ADDRESS']['house'];
                    if ($arResult['MAIN_ADDRESS']['korpus']) {
                        $addressValue .= " к. " . $arResult['MAIN_ADDRESS']['korpus'];
                    }
                    $addressValue .= ", ";
                }
                if ($arResult['MAIN_ADDRESS']['flat']) {
                    $addressValue .= "кв. " . $arResult['MAIN_ADDRESS']['flat'] . ", ";
                }
                if ($arResult['MAIN_ADDRESS']['porch']) {
                    $addressValue .= "подъезд " . $arResult['MAIN_ADDRESS']['porch'] . ", ";
                }
                if ($arResult['MAIN_ADDRESS']['floor']) {
                    $addressValue .= "этаж " . $arResult['MAIN_ADDRESS']['floor'];
                }
            } else {
                $addressValue = $_COOKIE['BITRIX_ADRESS'];
            }
            ?>

            <input type="hidden" class="address-hidden" value="<?= $addressValue ?>"
                name="<?= $address["FIELD_NAME"] ?>" id="<?= $address["FIELD_NAME"] ?>" />
            <div class="checkout-categories-item-body-about-row">
                <label class="profile-content-form__input">
                    <input class="inp" type="text" autocomplete="off"
                        value="<?= ($arResult['MAIN_ADDRESS']['isRegion']) ? $arResult['MAIN_ADDRESS']['city_format'] : CITY ?>"
                        readonly placeholder=" ">
                    <span>Город</span>
                </label>
                <?
                $address = explode(', ', $arResult['MAIN_ADDRESS']['street']);

                $showAddress = SITE_ID != 's2' || $USER->IsAdmin() || true;
                if (SITE_ID == 's2' && strlen($_REQUEST['street_se']) > 0 && !$USER->IsAdmin())
                    $address[0] = $_REQUEST['street_se'];

                if (SITE_ID == 's2')
                    echo '<input type="hidden" name="street_se" value="' . $address[0] . '">';
                ?>
                <label class="profile-content-form__input">
                    <input class="inp address-value" type="text" autocomplete="off"
                        <?= $showAddress ? 'disabled' : '' ?> value="<?= $address[0] ?>" id="street-checkout"
                        name="street_se" placeholder=" ">
                    <span>Улица</span>
                </label>
            </div>
            <?
            if ($arResult['MAIN_ADDRESS']['HOUSE_FROM'] && $arResult['MAIN_ADDRESS']['HOUSE_TO']) {
                if ($arResult['MAIN_ADDRESS']['house'] < $arResult['MAIN_ADDRESS']['HOUSE_FROM']
                    || $arResult['MAIN_ADDRESS']['house'] > $arResult['MAIN_ADDRESS']['HOUSE_TO'])
                    $arResult['MAIN_ADDRESS']['house'] = '';
            }

            ?>
            <div class="checkout-categories-item-body-about-row">
                <label class="profile-content-form__input mod-house">
                    <input class="inp address-value" type="text" autocomplete="off"
                        value="<?= $arResult['MAIN_ADDRESS']['house'] ?>" id="house-checkout" name="house-checkout"
                        <?= $arResult['MAIN_ADDRESS']['house'] == '' ? '' : 'readonly' ?> placeholder=" ">
                    <span>Дом</span>
                </label>

                <label class="profile-content-form__input mod-building">
                    <input class="inp address-value" type="text" autocomplete="off"
                        value="<?= $arResult['MAIN_ADDRESS']['korpus'] ?>" id="korpus-checkout" name="korpus-checkout"
                        readonly placeholder=" ">
                    <span>Корпус</span>
                </label>

                <label class="profile-content-form__input mod-porch" id="porch-checkout">
                    <input class="inp address-value <?= $porch['REQUIED'] == 'Y' ? 'required' : '' ?>" type="text"
                        maxlength="250" readonly size="<?= $porch["SIZE1"] ?>" value="<?= $porch["VALUE"] ?>"
                        name="<?= $porch["FIELD_NAME"] ?>" id="<?= $porch["FIELD_NAME"] ?>">
                    <span>Подъезд</span>
                </label>
                <label class="profile-content-form__input mod-floor" id="floor-checkout">
                    <input class="inp address-value <?= $floor['REQUIED'] == 'Y' ? 'required' : '' ?>" type="text"
                        maxlength="250" readonly size="<?= $floor["SIZE1"] ?>" value="<?= $floor["VALUE"] ?>"
                        name="<?= $floor["FIELD_NAME"] ?>" id="<?= $floor["FIELD_NAME"] ?>">
                    <span>Этаж</span>
                </label>
                <label class="profile-content-form__input mod-appartment">
                    <input class="inp address-value" type="text" readonly autocomplete="off" placeholder=" "
                        value="<?= $arResult['MAIN_ADDRESS']['flat'] ?>" id="flat-checkout" name="flat-checkout">
                    <span>Квартира</span>
                </label>
                <!--            <label class="profile-content-form__input mod-remark">-->
                <!--                <input class="inp address-value" type="text" autocomplete="off"-->
                <!--                       value="-->
                <? //= $arResult['MAIN_ADDRESS']['comment'] ?>
                <!--"-->
                <!--                       placeholder=" " id="comment-checkout" name="comment-checkout">-->
                <!--                <span>Примечание</span>-->
                <!--            </label>-->
            </div>
        </div>
        <div id="pickup-props" style="display: none">
            <div class="checkout-categories-item-body-about-row">
                <label for="" class="profile-content-form__input">
                    <select id="" class="order-select">
                        <?
                        $selectStore = current($arResult['STORE_LIST'])['ADDRESS'];
                        foreach ($arResult['STORE_LIST'] as $store):
                            if ($store['SELECTED']) {
                                $selectStore = $store['ADDRESS'];
                            }
                            ?>
                        <option data-number="<?= $store['UF_NUMBER_SHOP'] ?>" data-tt="<?=$store['UF_TT']?>"
                            value="<?= $store['ADDRESS'] ?>" <?= ($store['SELECTED'] ? 'selected' : 'disabled') ?>>
                            <?= $store['ADDRESS'] ?></option>
                        <? endforeach; ?>
                    </select>
                    <span>Адрес самовывоза</span>
                </label>
            </div>
            <div class="checkout-categories-item-body-about-row">
                <div class="info">
                    <div class="info__ico">
                        <img src="<?= $templateFolder ?>/ico/info-ico.png" alt="">
                    </div>
                    <?
                    $time = time();
                    $select = 1;
                    if ($time >= strtotime(date('d.m.Y 08:00')) && $time < strtotime(date('d.m.Y 17:00'))) {
                        $select = 1;
                        $addDay = 0;
                    } elseif ($time >= strtotime(date('d.m.Y 17:00')) && $time < strtotime(date('d.m.Y 23:59'))) {
                        $select = 2;
                        $addDay = 1;
                    } elseif ($time >= strtotime(date('d.m.Y 00:00')) && $time < strtotime(date('d.m.Y 08:00'))) {
                        $select = 3;
                        $addDay = 0;
                    }
                    ?>
                    <? if ($_COOKIE['DELIVERY'] == 'PICKUP'): ?>
                    <input type="hidden" name="ORDER_PROP_54"
                        value="<?= date('d.m.Y', strtotime(date('d.m.Y') . "+{$addDay} days")) ?>">
                    <? endif; ?>
                    <div class="info__text info__text-1 <?= ($select == 1) ? '' : 'hide' ?>">
                        Вы сможете забрать заказ в выбранном Вами магазине <span
                            class="address-pickup"><?= $selectStore ?></span> при получении СМС сообщения о
                        готовности выдачи, но не ранее чем через 2 часа от оформления заказа.
                        <span class="red">Важно</span> - заказ будет храниться в магазине до 21:00 <?= date('d.m.Y') ?>.
                        Назовите номер заказа сотруднику магазина при получении.
                    </div>
                    <div class="info__text info__text-2 <?= ($select == 2) ? '' : 'hide' ?>">
                        Вы сможете забрать заказ в выбранном Вами магазине <span
                            class="address-pickup"><?= $selectStore ?></span> при получении СМС сообщения о
                        готовности выдачи, но не ранее чем
                        10:00 <?= date('d.m.Y', strtotime(date('d.m.Y') . "+1 days")) ?>.
                        <span class="red">Важно</span> - заказ будет храниться в магазине до
                        21:00 <?= date('d.m.Y', strtotime(date('d.m.Y') . "+1 days")) ?>. Назовите номер заказа
                        сотруднику магазина при получении.
                    </div>
                    <div class="info__text info__text-3 <?= ($select == 3) ? '' : 'hide' ?>">
                        Вы сможете забрать заказ в выбранном Вами магазине <span
                            class="address-pickup"><?= $selectStore ?></span> при получении СМС сообщения о
                        готовности выдачи, но не ранее чем 10:00 <?= date('d.m.Y') ?>.
                        <span class="red">Важно</span> - заказ будет храниться в магазине до 21:00 <?= date('d.m.Y') ?>.
                        Назовите номер заказа сотруднику магазина при получении.
                    </div>
                </div>
            </div>
        </div>
        <?
        foreach ($arResult["ORDER_PROP"] as $key => $propsValues) {
            if ($key != 'USER_PROPS_Y' && $key != 'USER_PROPS_N')
                continue;
            foreach ($arResult["ORDER_PROP"][$key] as $arProperties) {
                if ($arProperties["TYPE"] == "TEXT") {
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
        <input class="<?= $required ?>" type="hidden" maxlength="250" size="<?= $arProperties["SIZE1"] ?>"
            value="<?= CITY ?>" name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>">
        <?
                    elseif ($arProperties["CODE"] == 'ZONE'):
                        ?>
        <input class="<?= $required ?>" type="hidden" maxlength="250" size="<?= $arProperties["SIZE1"] ?>"
            value="<?= $arResult['ZONE'] ?>" name="<?= $arProperties["FIELD_NAME"] ?>"
            id="<?= $arProperties["FIELD_NAME"] ?>">
        <? elseif ($arProperties["CODE"] == 'ZONE_ID' && $_COOKIE['ZONE_ID'] > 0):
                        ?>
        <input class="<?= $required ?>" type="hidden" maxlength="250" size="<?= $arProperties["SIZE1"] ?>"
            value="zone_<?= $_COOKIE['ZONE_ID'] ?>" name="<?= $arProperties["FIELD_NAME"] ?>"
            id="<?= $arProperties["FIELD_NAME"] ?>">
        <?
                    elseif ($arProperties["CODE"] == 'TT'):

//                        if (strlen($_COOKIE['BITRIX_TT']) == 0 && strlen($_COOKIE['BITRIX_ADRESS']) > 0) {
//
//                            $address = explode('д.', $_COOKIE['BITRIX_ADRESS']);
//
//
//                            $dbElement = \Bitrix\Iblock\ElementTable::getList(['filter' => ['%NAME' => trim($address[0]), 'IBLOCK_ID' => 37]])->fetch();
//                            $ttValue = $dbElement['XML_ID'];
//                        }
//
                        $ttValue = $_COOKIE['BITRIX_TT'];
                        $ttId = $_COOKIE['ADDRESS_ID'];
                        if ($ttId > 0) {
                            if ($_COOKIE['DELIVERY'] == 'DELIVERY') {
                                $dbElement = \Bitrix\Iblock\ElementTable::getById($ttId)->fetch();
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
                        }

                        ?>
        <input class="<?= $required ?>" type="hidden" value="<?= $ttValue ?>" name="<?= $arProperties["FIELD_NAME"] ?>"
            id="<?= $arProperties["FIELD_NAME"] ?>" />
        <?
                    elseif ($arProperties["CODE"] == 'CARD_NUMBER'):
                        ?>
        <input class="<?= $required ?>" type="hidden" value="<?= $arProperties["VALUE"] ?>"
            name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" />
        <? elseif ($arProperties["CODE"] == 'NUMBER_SHOP' && $_COOKIE['DELIVERY'] == 'PICKUP'): ?>
        <input class="<?= $required ?>" type="hidden" value="<?= $arData["UF_NUMBER_SHOP"] ?>"
            name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" />
        <? elseif ($arProperties["CODE"] == 'TT_PICKUP' && $_COOKIE['DELIVERY'] == 'PICKUP'): ?>
        <input class="<?= $required ?>" type="hidden" value="<?= $arData["UF_NUMBER_SHOP"] ?>"
            name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" />
        <?
                    endif;
                }
            }
        }

        ?>
        <?
        /*
    $blockDate = ['01.01.2022'];
    $blockSlot = [
        '31.12.2021' => ['18:00– 21:00', '18:00 – 21:00', '21:00 – 23:00']
    ];
    $currentDate = date('d.m.Y');
    ?>
        <? foreach ($date['VARIANTS'] as $k => $item) {
        ?>
        <input type="radio" name="ORDER_PROP_<?= $date['ID'] ?>"
            id="ORDER_PROP_<?= $date['ID'] ?>_<?= $item['VALUE'] ?>" value="<?= $item['VALUE'] ?>" style="display: none"
            <?= $_REQUEST['ORDER_PROP_' . $date['ID']] == $item['VALUE'] ? 'checked' : '' ?>>
        <? } ?>
        <?
    $hideTimeBlock = $arResult['SELECTED_DELIVERY']['ID'] == CSiteUtils::$deliveryExpress;
    ?>
        <div class="checkout-categories-item-body-about-row__title" id="time-block-title"
            <?= $hideTimeBlock ? 'style="display:none"' : '' ?>>
            Время доставки
        </div>
        <div class="checkout-categories-item-body-about-time" id="time-block"
            <?= $hideTimeBlock ? 'style="display:none"' : '' ?>>
            <div class="checkout-categories-item-body-about-time-head">
                <span class="checkout-categories-item-body-about-time-head__text">Доступное время доставки:</span>
                <?
            $active = false;
            $i = 0;
            ?>
                <? if (!in_array($currentDate, $blockDate)): ?>
                <span
                    class="checkout-categories-item-body-about-time-head__option js-about-time <?= !$active ? 'active' : '' ?>"
                    data-index="<?= $i++ ?>"
                    data-date="ORDER_PROP_<?= $date['ID'] ?>_<?= $date['VARIANTS'][0]['VALUE'] ?>">сегодня</span>
                <?
                $active = true;
            endif; ?>
                <? if (!in_array(date('d.m.Y', strtotime($currentDate . '+ 1 days')), $blockDate) && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['TOMORROW']): ?>
                <span
                    class="checkout-categories-item-body-about-time-head__option js-about-time <?= !$active ? 'active' : '' ?>"
                    data-index="<?= $i++ ?>"
                    data-date="ORDER_PROP_<?= $date['ID'] ?>_<?= $date['VARIANTS'][1]['VALUE'] ?>">завтра</span>
                <?
                $active = true;
            endif; ?>
                <? if (!in_array(date('d.m.Y', strtotime($currentDate . '+ 2 days')), $blockDate) && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['AFTER_TOMORROW']): ?>
                <span
                    class="checkout-categories-item-body-about-time-head__option js-about-time <?= !$active ? 'active' : '' ?>"
                    data-index="<?= $i++ ?>"
                    data-date="ORDER_PROP_<?= $date['ID'] ?>_<?= $date['VARIANTS'][2]['VALUE'] ?>">послезавтра</span>
                <?
                $active = true;
            endif; ?>

            </div>
            <div class="checkout-categories-item-body-about-time-body">
                <?
            $slotId = 40;
            $slotIdTNext = 41;
            $slotIdAfterTomorrow = 69;
            if (SITE_ID == 's2') {
                $slotId = 65;
                $slotIdTNext = 66;
                $slotIdAfterTomorrow = 70;
            }

            if ($_REQUEST['ORDER_PROP_' . $date['ID']] == '1')
                $time = $_REQUEST['ORDER_PROP_' . $slotId];
            else if ($_REQUEST['ORDER_PROP_' . $date['ID']] == '2')
                $time = $_REQUEST['ORDER_PROP_' . $slotIdTNext];
            else
                $time = $_REQUEST['ORDER_PROP_' . $slotIdAfterTomorrow];

            ?>
                <?
            $date = $currentDate;
            if (!in_array($date, $blockDate)):?>
                <div class="checkout-categories-item-body-about-time-table js-about-time-tab active">
                    <?
                    $active = false;
                    $checked = false;
                    foreach ($arResult['VIRTUAL_TIME_SLOTS']['TODAY'] as $slotTom):
                        if ($blockSlot[$date]) {
                            if (in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                        }
                        $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                        if ($slotTom['SLOT_ACTIVE'] != 'NO' && !$checked)
                            $checked = true;

                        $checked = false;
                        if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotId] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '1')
                            $checked = true;
                        ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>">
                        <input type="radio" name="ORDER_PROP_<?= $slotId ?>" value="<?= $slotTom['SLOT'] ?>"
                            <?= $disabled ?> <?= $checked ? 'checked="checked"' : '' ?>>
                        <span><?= $slotTom['SLOT'] ?></span>
                    </label>
                    <? endforeach; ?>
                </div>
                <? endif; ?>
                <?
            $date = date('d.m.Y', strtotime($currentDate . '+ 1 days'));
            if (!in_array($date, $blockDate) && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['TOMORROW']):?>
                <div class="checkout-categories-item-body-about-time-table js-about-time-tab">
                    <? foreach ($arResult['VIRTUAL_TIME_SLOTS']['TOMORROW'] as $slotTom):
                        if ($blockSlot[$date]) {
                            if (in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                        }
                        $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                        $checked = false;
                        if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotIdTNext] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '2')
                            $checked = true;
                        ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>">
                        <input type="radio" name="ORDER_PROP_<?= $slotIdTNext ?>" value="<?= $slotTom['SLOT'] ?>"
                            <?= $disabled ?> <?= $checked ? 'checked="checked"' : '' ?>>
                        <span><?= $slotTom['SLOT'] ?></span>
                    </label>
                    <? endforeach; ?>
                </div>
                <? endif; ?>
                <?
            $date = date('d.m.Y', strtotime($currentDate . '+ 2 days'));
            if (!in_array($date, $blockDate) && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['AFTER_TOMORROW']):?>
                <div class="checkout-categories-item-body-about-time-table js-about-time-tab">
                    <?

                    foreach ($arResult['VIRTUAL_TIME_SLOTS']['AFTER_TOMORROW'] as $slotTom):
                        if ($blockSlot[$date]) {
                            if (in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                        }
                        $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                        $checked = false;
                        if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotIdAfterTomorrow] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '3')
                            $checked = true;
                        ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>">
                        <input type="radio" name="ORDER_PROP_<?= $slotIdAfterTomorrow ?>"
                            value="<?= $slotTom['SLOT'] ?>" <?= $disabled ?> <?= $checked ? 'checked="checked"' : '' ?>>
                        <span><?= $slotTom['SLOT'] ?></span>
                    </label>
                    <? endforeach; ?>
                </div>
                <? endif; ?>
            </div>
        </div>

        <? //if($USER->isAdmin()):?>
        <div class="checkout-categories-item-body-about-time-comment" style="display: none" id="delivery-warning">
            <div class="checkout-categories-item-body-about-time-comment__icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26px"
                    height="27px">
                    <path fill-rule="evenodd" fill="rgb(222, 45, 66)"
                        d="M23.989,20.710 L25.636,26.309 L19.996,24.722 C17.976,26.014 15.575,26.763 13.000,26.763 C5.820,26.763 -0.000,20.943 -0.000,13.763 C-0.000,6.584 5.820,0.763 13.000,0.763 C20.180,0.763 26.000,6.584 26.000,13.763 C26.000,16.318 25.262,18.700 23.989,20.710 Z">
                    </path>
                    <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                        d="M15.000,6.763 L14.091,16.854 L14.086,16.854 C14.033,17.326 13.578,17.672 13.091,17.672 C12.604,17.672 12.148,17.326 12.096,16.854 L12.091,16.854 L11.182,6.763 L11.184,6.763 C11.183,6.733 11.182,6.703 11.182,6.672 C11.182,5.651 12.069,4.797 13.091,4.797 C14.112,4.797 15.000,5.651 15.000,6.672 C15.000,6.703 14.999,6.733 14.997,6.763 L15.000,6.763 ZM13.091,18.672 C13.995,18.672 14.727,19.405 14.727,20.309 C14.727,21.212 13.995,21.945 13.091,21.945 C12.187,21.945 11.454,21.212 11.454,20.309 C11.454,19.405 12.187,18.672 13.091,18.672 Z">
                    </path>
                </svg>
            </div>
            <div class="checkout-categories-item-body-about-time-comment__text">
                <span class="mod-red">ВНИМАНИЕ!</span> Комплектация Вашего заказа будет выполняться <span
                    class="js-delivery-date"><?= date('d.m.Y') ?></span>, возможно отсутствие некоторых товаров.
                Оператор свяжется с вами для уточнения информации
            </div>
        </div>
        <? //endif;?>
        <?*/ ?>
        <?
        $show = (date('w') == 5 || date('w') == 6) && $arResult['SHOW_ATTENTION'];
        //        if ($USER->IsAdmin())
        //            $show = true;
        if ($show || true):
            $text = date('w') == 5 ? 'послезавтра' : 'завтра';
            ?>
        <div class="checkout-categories-item-body-about-time-comment" style="display: none" id="show-date-attention">
            <div class="checkout-categories-item-body-about-time-comment__icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26px"
                    height="27px">
                    <path fill-rule="evenodd" fill="rgb(222, 45, 66)"
                        d="M23.989,20.710 L25.636,26.309 L19.996,24.722 C17.976,26.014 15.575,26.763 13.000,26.763 C5.820,26.763 -0.000,20.943 -0.000,13.763 C-0.000,6.584 5.820,0.763 13.000,0.763 C20.180,0.763 26.000,6.584 26.000,13.763 C26.000,16.318 25.262,18.700 23.989,20.710 Z">
                    </path>
                    <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                        d="M15.000,6.763 L14.091,16.854 L14.086,16.854 C14.033,17.326 13.578,17.672 13.091,17.672 C12.604,17.672 12.148,17.326 12.096,16.854 L12.091,16.854 L11.182,6.763 L11.184,6.763 C11.183,6.733 11.182,6.703 11.182,6.672 C11.182,5.651 12.069,4.797 13.091,4.797 C14.112,4.797 15.000,5.651 15.000,6.672 C15.000,6.703 14.999,6.733 14.997,6.763 L15.000,6.763 ZM13.091,18.672 C13.995,18.672 14.727,19.405 14.727,20.309 C14.727,21.212 13.995,21.945 13.091,21.945 C12.187,21.945 11.454,21.212 11.454,20.309 C11.454,19.405 12.187,18.672 13.091,18.672 Z">
                    </path>
                </svg>
            </div>
            <div class="checkout-categories-item-body-about-time-comment__text">
                <span class="mod-red">ВНИМАНИЕ!</span> При выборе доставки на <?= $text ?>, 18:00 акция на некоторые
                товары в вашей корзине будет завершена. Пожалуйста, проверьте дату доставки или измените <a
                    href="/personal/">Ваш заказ.</a>
            </div>

        </div>
        <? if ($USER->IsAdmin()): ?>
        <div class="checkout-categories-item-body-about-time-comment products_items">
        </div>
        <? endif; ?>
        <? endif; ?>

        <div class="checkout-categories-item-body-about-row__title">
            Дополнительно
        </div>
        <div class="checkout-categories-item-body-about-row">
            <label class="profile-content-form__input">
                <input class="inp" type="text" name="ORDER_DESCRIPTION" autocomplete="off" placeholder=" "
                    value="<?= $_REQUEST['ORDER_DESCRIPTION'] ?>">
                <span>Комментарий к заказу</span>
            </label>
        </div>
        <div class="checkout-acception">
            <label class="js-accept" style='font-size: 10px;line-height:13px;'>
                <span>
                    <?
                                    if (SITE_ID == 's3')
                                        $path = SITE_TEMPLATE_PATH . "/include_areas/confurm_person.php";
                                    else
                                        $path = SITE_TEMPLATE_PATH . "/include_areas/confurm_person.php";
                                    if ($_COOKIE['BITRIX_TT'] == 'ТТ-79'):
                                        $path = SITE_TEMPLATE_PATH . "/include_areas/confurm_person_tt_79.php";
                                    endif;
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include", "", array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => $path
                                        )
                                    );
                                    ?>
                </span>
                <br />
            </label>
        </div>
        <div class="checkout-categories-item-bottom">
            <div class="checkout-categories-item-bottom-left">
                <a href="/" class="checkout-categories-item-bottom__btn js-checkout-back">&lt;&lt; Назад</a>
            </div>
            <div class="checkout-categories-item-bottom-left">
                <a href="/"
                    class="checkout-categories-item-bottom__btn checkout-categories-item-bottom__btn-green js-accept-success">Согласиться
                    с условиями и оформить заказ</a>
            </div>
            <input type="hidden" class="checkbox" name="confirmorder" value="Y">
            <input type="hidden" class="checkbox" name="confirmperson" value="Y">
        </div>
    </div>
</div>