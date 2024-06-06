<div class="checkout-categories-item js-checkout-category closed" data-category="delivery">
    <div class="checkout-categories-item-head js-checkout-head">
        <div class="checkout-categories-item-head__title">
            <?= $number;
            $number++ ?>.Выберите тип доставки
        </div>
        <i class="far fa-check-circle check-ok"></i>
    </div>
    <div class="checkout-categories-item-body">
        <div class="checkout-categories-item-body-row" id="delivery_body">
            <?
            $blockDate = ['31.12.2021', '01.01.2022'];
            $currentDate = date('d.m.Y');

            foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery) {
                if ($arDelivery["CHECKED"] == 'Y')
                    $arResult['SELECTED_DELIVERY'] = $arDelivery;
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

                if ($_COOKIE['ZONE_ID'] == 0 && in_array($arDelivery["ID"], CSiteUtils::$zonesDelivery))
                    continue;

                if ($arDelivery["ID"] == CSiteUtils::$petromostDelivery)
                    continue;

//                if (!$USER->IsAdmin() && $_COOKIE['BITRIX_TT'] != 'ТТ-55' && $arDelivery["ID"] == CSiteUtils::$petromostExpress)
//                    continue;

                if ($_COOKIE['DELIVERY'] == 'PICKUP' && $arDelivery["ID"] != CSiteUtils::$petromostPickup) {
                    continue;
                }
                if ($_COOKIE['DELIVERY'] == 'DELIVERY' && $arDelivery["ID"] == CSiteUtils::$petromostPickup) {
                    continue;
                }
                if (!$USER->IsAdmin() && in_array($arDelivery["ID"], CSiteUtils::$zonesDelivery))
                    continue;

               if (!$USER->IsAdmin() && $arDelivery["ID"] == CSiteUtils::$deliveryExpress && (SITE_ID == 's3'))
                   continue;

                if ($arDelivery["ID"] == CSiteUtils::$deliveryExpress && $arResult['SHOW_EXPRESS'] == 'N') {
                    continue;
                }

                if ($arDelivery["ID"] == CSiteUtils::$deliveryExpress && in_array($currentDate, $blockDate)) continue;

                if ($arDelivery["CHECKED"] == "Y" && $arDelivery["ID"] == CSiteUtils::$deliveryExpress)
                    $arResult['DELIVERY_PRICE_FORMATED'] = CCurrencyLang::CurrencyFormat($arDelivery["PRICE"], 'RUB');
                ?>
            
            <label class="checkout-categories-item-body-option">
                <input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?= $arDelivery["FIELD_NAME"] ?>"
                    value="<?= $arDelivery["ID"] ?>" <? if ($arDelivery["CHECKED"]=="Y" ) echo " checked" ; ?>
                class="delivery-change">

                <div class="checkout-categories-item-body-option-wrapper">
                    <? if ($arDelivery["ID"] == 2): ?>
                    <div class="checkout-categories-item-body-option__icon">
                        <svg id="Layer_1" enable-background="new 0 0 511.414 511.414" height="512"
                            viewBox="0 0 511.414 511.414" width="512" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m497.695 108.838c0-6.488-3.919-12.334-9.92-14.8l-225.988-92.838c-3.896-1.6-8.264-1.6-12.16 0l-225.988 92.838c-6.001 2.465-9.92 8.312-9.92 14.8v293.738c0 6.488 3.918 12.334 9.92 14.8l225.988 92.838c3.854 1.583 8.186 1.617 12.14-.001.193-.064-8.363 3.445 226.008-92.837 6.002-2.465 9.92-8.312 9.92-14.8zm-241.988 76.886-83.268-34.207 179.951-78.501 88.837 36.495zm-209.988-51.67 71.841 29.513v83.264c0 8.836 7.164 16 16 16s16-7.164 16-16v-70.118l90.147 37.033v257.797l-193.988-79.692zm209.988-100.757 55.466 22.786-179.951 78.501-61.035-25.074zm16 180.449 193.988-79.692v257.797l-193.988 79.692z" />
                        </svg>
                    </div>
                    <div class="checkout-categories-item-body-option__icon-hover">
                        <svg id="Layer_1" enable-background="new 0 0 506.139 506.139" height="512"
                            viewBox="0 0 506.139 506.139" width="512" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m128.621 134.599 222.768-95.506-92.24-37.893c-3.896-1.6-8.264-1.6-12.16 0l-221.545 91.013z" />
                            <path d="m392.849 56.125-222.836 95.478 83.056 34.121 227.626-93.511z" />
                            <path
                                d="m237.069 213.746-90.147-37.033v70.118c0 8.836-7.164 16-16 16s-16-7.164-16-16v-83.264l-103.841-42.659v281.668c0 6.488 3.918 12.334 9.92 14.8l216.068 88.763z" />
                            <path
                                d="m269.069 213.746v292.393l216.068-88.763c6.002-2.465 9.92-8.312 9.92-14.8 0-10.766 0-269.883 0-281.668z" />
                        </svg>
                    </div>
                    <? else: ?>
                    <div class="checkout-categories-item-body-option__icon">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 446.575 446.575"
                            style="enable-background:new 0 0 446.575 446.575;" xml:space="preserve">
                            <g>
                                <g>
                                    <path
                                        d="M446.4,316.088v-75.2c0-11.2-3.201-22.4-8-32l-46.4-81.6c-4.801-9.6-16-16-27.199-16h-72
                                                                c-11.201,0-20.801,9.6-20.801,20.8v203.2h17.6c8-27.2,32-48,62.4-48c25.6,0,46.4,14.4,56,35.2c4.801,8,11.199,12.8,19.199,12.8
                                                                C438.4,335.288,448,325.688,446.4,316.088z M432,314.488c0,3.2-1.6,4.8-3.199,4.8c-3.201,0-4.801-1.6-4.801-3.2
                                                                c-14.4-27.2-41.6-44.8-72-44.8c-25.6,0-49.6,12.8-64,32h-1.6v-171.2c0-3.2,3.199-4.8,4.799-4.8h73.602
                                                                c6.398,0,11.199,3.2,14.398,6.4l46.401,81.6c4.8,8,6.4,16,6.4,24V314.488z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M224,47.288H0v288h48c8-27.2,32-48,62.4-48c30.4,0,56,20.8,62.4,48H256v-256C256,61.688,241.6,47.288,224,47.288z
                                                                M240,319.288h-54.4c-12.799-28.8-41.6-48-73.6-48s-60.801,19.2-73.6,48H16v-256h208c9.6,0,16,6.4,16,16V319.288z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M352,303.288c-27.199,0-48,20.8-48,48s20.801,48,48,48c27.199,0,48-20.8,48-48S379.199,303.288,352,303.288z M352,383.288
                                                                c-17.6,0-32-14.4-32-32c0-17.6,14.4-32,32-32c17.6,0,32,14.4,32,32C384,368.888,369.6,383.288,352,383.288z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M112,303.288c-25.6,0-48,20.8-48,48s20.801,48,48,48s48-20.8,48-48S139.199,303.288,112,303.288z M112,383.288
                                                                c-17.6,0-32-14.4-32-32c0-17.6,14.4-32,32-32c17.6,0,32,14.4,32,32C144,368.888,129.6,383.288,112,383.288z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M364.801,143.288H304v80h107.199L364.801,143.288z M320,159.288h36.801l27.199,48h-64V159.288z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <circle cx="112" cy="351.288" r="12" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <circle cx="352" cy="351.288" r="12" />
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="checkout-categories-item-body-option__icon-hover">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 448.051 448.051"
                            style="enable-background:new 0 0 448.051 448.051;" xml:space="preserve">
                            <g>
                                <g>
                                    <path
                                        d="M447.875,316.825v-75.2c0-11.2-3.195-22.4-8-32L392,128.025c-4.801-9.6-16-16-27.199-16h-72
                                                                c-11.199,0-20.801,9.6-20.801,20.8v171.2h-16v-224c0-17.6-14.398-32-32-32H0v288h48c8-27.2,32-48,62.398-48
                                                                c30.402,0,56,20.8,62.402,48h116.801c8-27.2,32-48,62.398-48c25.602,0,46.398,14.4,56,35.2c4.801,8,12.68,12.8,20.68,12.8
                                                                C439.875,336.025,449.477,326.425,447.875,316.825z M304,224.025v-80h64l46.398,80H304z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M352,304.025c-27.199,0-48,20.8-48,48s20.801,48,48,48c27.199,0,48-20.8,48-48S379.199,304.025,352,304.025z M352,374.425
                                                                c-12.801,0-22.398-9.6-22.398-22.4s9.598-22.4,22.398-22.4c12.801,0,22.398,9.6,22.398,22.4S364.801,374.425,352,374.425z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M112,304.025c-27.199,0-48,20.8-48,48s20.801,48,48,48s48-20.8,48-48S139.199,304.025,112,304.025z M112,374.425
                                                                c-12.801,0-22.398-9.6-22.398-22.4s9.598-22.4,22.398-22.4s22.398,9.6,22.398,22.4S124.801,374.425,112,374.425z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <circle cx="112" cy="352.025" r="8" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <circle cx="352" cy="352.025" r="8" />
                                </g>
                            </g>
                        </svg>

                    </div>
                    <? endif; ?>
                    <div class="checkout-categories-item-body-option__text">
                        <?= $arDelivery["NAME"] ?>
                        <?
                            //                            if($USER->IsAdmin()) {
                            //                            echo '<pre>';
                            //                            var_dump($arDelivery["NAME"]);
                            //                             echo '</pre>';
                            //                            }
                            ?>
                    </div>
                </div>
            </label>
            <?
            } ?>
            <div class="checkout-categories-item-body-delivery">
                <div class="checkout-categories-item-body-delivery__text">
                    Стоимость доставки:
                </div>
                <div class="checkout-categories-item-body-delivery__price">
                    <?= $arResult['DELIVERY_PRICE_FORMATED'] ?>


                </div>
            </div>
        </div>
        <hr
            style="width:100%; border: none; color: #f6f6f6; background-color: #f6f6f6; height: 1px;margin: 20px 0 20px;">


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

        /*if($USER->IsAdmin()) {
            echo '<pre>';
            var_dump($city = $arProperties);
            echo '</pre>';
        }*/

        $blockDate = $arResult['DATE_FULL_BLOCK'];
        //        $blockSlot = [
        //            '31.12.2021' => ['18:00– 21:00', '18:00 – 21:00', '21:00 – 23:00']
        //        ];
        global $arZone;
        $blockSlot = $arResult['BLOCK_SLOTS'][$arZone['ID']];


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
        <? /*?>
        <div class="checkout-categories-item-body-about-row__title" id="time-block-title"
            <?= $hideTimeBlock ? 'style="display:none"' : 'style="display:none"' ?>>
            Время доставки
        </div>
        <?*/ ?>
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
                <? if (!in_array(date('d.m.Y', strtotime($currentDate . '+ 2 days')), $blockDate)
                    && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['AFTER_TOMORROW']): ?>
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
                                if (!in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                            }
                            $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                            if ($slotTom['SLOT_ACTIVE'] != 'NO' && !$checked)
                                $checked = true;

                            $checked = false;
                            if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotId] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '1')
                                $checked = true;
                            ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>" data-zone="<?= $slotTom['ZONE_ID'] ?>"
                        data-zone-name="<?= $slotTom['ZONE_NAME'] ?>">
                        <input type="radio" name="ORDER_PROP_<?= $slotId ?>" value="<?= $slotTom['SLOT'] ?>"
                            <?= $disabled ?> <?= $checked ? 'checked="checked"' : '' ?>>
                        <span><?= $slotTom['SLOT'] ?></span>
                    </label>
                    <? endforeach; ?>
                </div>
                <hr
                    style="width:100%; border: none; color: #f6f6f6; background-color: #f6f6f6; height: 1px;margin: 20px 0 20px;">

                <? endif; ?>
                <?
                $date = date('d.m.Y', strtotime($currentDate . '+ 1 days'));
                if (!in_array($date, $blockDate) && !$arResult["VIRTUAL_TIME_SLOTS"]['DEACTIVE']['TOMORROW']):?>
                <div class="checkout-categories-item-body-about-time-table js-about-time-tab">
                    <? foreach ($arResult['VIRTUAL_TIME_SLOTS']['TOMORROW'] as $slotTom):
                            if ($blockSlot[$date]) {
                                if (!in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                            }
                            $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                            $checked = false;
                            if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotIdTNext] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '2')
                                $checked = true;
                            ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>" data-zone="<?= $slotTom['ZONE_ID'] ?>"
                        data-zone-name="<?= $slotTom['ZONE_NAME'] ?>">
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
                                if (!in_array($slotTom['SLOT'], $blockSlot[$date])) $slotTom["SLOT_ACTIVE"] = "NO";
                            }
                            $disabled = $slotTom['SLOT_ACTIVE'] == 'NO' ? 'disabled="disabled"' : '';
                            $checked = false;
                            if ($slotTom['SLOT'] == $_REQUEST['ORDER_PROP_' . $slotIdAfterTomorrow] && $_REQUEST['ORDER_PROP_' . $date['ID']] == '3')
                                $checked = true;
                            ?>
                    <label class="checkout-categories-item-body-about-time-option<?= $checked ? ' checked' : '' ?>"
                        data-slot="<?= $slotTom['ID'] ?>" data-zone="<?= $slotTom['ZONE_ID'] ?>"
                        data-zone-name="<?= $slotTom['ZONE_NAME'] ?>">
                        <input type="radio" name="ORDER_PROP_<?= $slotIdAfterTomorrow ?>"
                            value="<?= $slotTom['SLOT'] ?>" <?= $disabled ?> <?= $checked ? 'checked="checked"' : '' ?>>
                        <span><?= $slotTom['SLOT'] ?></span>
                    </label>
                    <? endforeach; ?>
                </div>
                <? endif; ?>
            </div>
        </div>
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

        <p style="display: none" class="delivery_text"><a href="/delivery-and-payment/">Минимальная сумма</a>
            заказа для
            БЕСПЛАТНОЙ
            доставки
            составляет <span id="min_price_text"><?= SITE_ID == 's1' ? 1000 : 1000 ?></span> руб</p>

        <p style="display: none" class="delivery_text_express">Экспресс доставка – доставка от 30 мин* в зависимости от
            объема заказа и адреса доставки</p>


        <? //if ($USER->IsAdmin()): ?>
        <div class="delivery-block__bottom-price">
            <p>Стоимость доставки:
                <span><?= $arResult['DELIVERY_PRICE_FORMATED'] ?></span>
            </p>
        </div>
        <?// endif; ?>

        <div class="checkout-categories-item-bottom">
            <div class="checkout-categories-item-bottom-left">
                <a href="/" class="checkout-categories-item-bottom__btn js-checkout-back">&lt;&lt; Назад</a>
            </div>
            <div class="checkout-categories-item-bottom-right">
                <a href="/" class="checkout-categories-item-bottom__btn js-delivery-next">Далее &gt;&gt;</a>
            </div>
        </div>
    </div>
    <div class="checkout-categories-item-body-details" id="delivery_details"
        data-price="<?= $arResult['DELIVERY_PRICE_FORMATED'] ?>">
        <div id="delivery_name"></div>
        <div class="time-block">
            <div id="time-title">
            </div>
            <div id="time-value"></div>
        </div>
        <div class="checkout-categories-item-body-details__price">
            <?= $arResult['DELIVERY_PRICE_FORMATED'] ?>
        </div>
    </div>
</div>

<?
if ($_COOKIE['DELIVERY'] == 'PICKUP'):?>
<script>
    $(document).ready(function () {
        $('[name="DELIVERY_ID"][value=3]').trigger('click')
    })
</script>
<? endif; ?>
<? if ($_COOKIE['DELIVERY'] == 'DELIVERY'): ?>
<script>
    $(document).ready(function () {
        $('[name="DELIVERY_ID"][value=2]').trigger('click')
    })
</script>
<? endif; ?>