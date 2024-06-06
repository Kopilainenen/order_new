<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?
BXClearCache(true, "/oneway/timeslot/");    // скинем кеш компонента, который отображает свободные слоты в шапке сайта
if (!empty($arResult["ORDER"])) {
    $order = \Bitrix\Sale\Order::load($arResult['ORDER']['ID']);
    $basket = $order->getBasket()->getBasketItems();
    $propertyCollection = $order->getPropertyCollection();
    /** @var \Bitrix\Sale\BasketItem $item */
    /** @var \Bitrix\Sale\Payment $pay */

    $pay = $order->getPaymentCollection()->current();
    foreach ($basket as $item) {
        $section = [];
        $element = \Bitrix\Iblock\ElementTable::getById($item->getProductId())->fetch();
        $nav = CIBlockSection::GetNavChain($element['IBLOCK_ID'], $element['IBLOCK_SECTION_ID']);
        while ($db = $nav->Fetch())
            $section[] = $db['NAME'];

        $brand = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], [], ['CODE' => 'BRAND'])->Fetch()['VALUE'];
        $arr[] = [
            'name' => $item->getField('NAME'),
            'price' => $item->getPrice(),
            "quantity" => $item->getQuantity(),
            'brand' => $brand,
            'id' => $item->getProductId(),
            'category' => implode('/', $section)
        ];
    }
    $ecomm = ['currency' => 'RUB', 'actionField' => ['id' => $arResult['ORDER']['ID']], 'products' => $arr];
    ?>
    <? $this->setViewTarget('buffer_js') ?>
    <script>setAnalytics(<?=json_encode($ecomm)?>, 'order')</script>
    <? $this->endViewTarget() ?>
    <?php
    if (!empty($arResult["ORDER"])) {
        $orderPayment = $arResult['ORDER_PAYMENTS'][$arResult["ORDER"]["ID"]]['PAYMENT'];
        ?>
        <div class="content__fonts">
            <div class="order-complete">
                <div class="order-after-content">
                    <div class="order-after-content__head">
                        <div class="order-after-content__head-ico is_pay">
                            <img src="<?= $templateFolder ?>/ico/green-galochka.png" alt="">
                        </div>
                        <div class="order-after-content__head-ico no_pay" style="display: none">
                            <img src="<?= $templateFolder ?>/ico/close-ico.png" alt="" width="30px" height="30px">
                        </div>
                        <div class="order-after-content__head-text">
                            <? if (strlen($orderPayment['PAY_VOUCHER_NUM']) > 0): ?>
                                <p id="order_status_title"><b>Заказ оплачен</b></p>
                            <? else: ?>
                                <p class="no_pay"><b>Ошибка оплаты</b></p>
                                <p id="order_status_title" class="is_pay">
                                    <b><?= GetMessage("SOA_TEMPL_ORDER_COMPLETE") ?></b></p>
                            <? endif; ?>
                            <p class="desc no_pay"
                               style="display: none"><?= GetMessage("SOA_TEMPL_ORDER_SUC_N", array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER_ID"])) ?></p>
                            <p class="desc is_pay"
                               style="<?= strlen($orderPayment['PAY_VOUCHER_NUM']) > 0 ? 'display: none' : '' ?>"><?= GetMessage("SOA_TEMPL_ORDER_SUC", array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER_ID"])) ?></p>
                            <p class="desc is_payed"
                               style="<?= strlen($orderPayment['PAY_VOUCHER_NUM']) == 0 ? 'display: none' : '' ?>"><?= GetMessage("SOA_TEMPL_ORDER_SUC_PAY", array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER_ID"])) ?></p>
                            <? if ((int)$arResult['ORDER']['DELIVERY_ID'] !== (int)CSiteUtils::$petromostPickup):
                                $propIdStart = 62;
                                $propIdEnd = 63;
                                $propAddress = 4;
                                if (SITE_ID == 's2') {
                                    $propIdStart = 67;
                                    $propIdEnd = 68;
                                    $propAddress = 18;
                                }
                                $dateStart = explode(' ', $propertyCollection->getItemByOrderPropertyId($propIdStart)->getValue());
                                $dateEnd = explode(' ', $propertyCollection->getItemByOrderPropertyId($propIdEnd)->getValue());
                                $address = $propertyCollection->getItemByOrderPropertyId($propAddress)->getValue();

                                ?>
                                <p class="desc" style="">Дата доставки заказа:
                                    <b><?= $dateStart[0] . ' ' . $dateStart[1] . ' - ' . $dateEnd[1] ?></b></p>
                                <p style="">Адрес доставки: <b><?= $address ?></b></p>
                                <p style="">Тип оплаты: <b><?= $pay->getPaymentSystemName() ?></b></p>
                                <? if ($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] == CSiteUtils::$onlinePayment
                                || in_array($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"], CSiteUtils::$onlinePayments)) { ?>
                                <p style="margin-top: 20px" class="pay_text">
                                    Вы можете следить за выполнением Вашего заказа в <a
                                            href="/personal/history-of-orders/">личном
                                        кабинете</a> интернет магазина Петромост
                                </p>
                            <? } ?>
                            <? endif; ?>
                            <div class="order-after-content__body">
                                <? if ((int)$arResult['ORDER']['DELIVERY_ID'] === (int)CSiteUtils::$petromostPickup): ?>
                                    <?
                                    $selectStore = $propertyCollection->getItemByOrderPropertyId(4)->getValue();

                                    $time = strtotime($arResult["ORDER"]["DATE_INSERT"]);

                                    $select = 1;
                                    if (date('H', $time) >= 8 && date('H', $time) < 17) {
                                        $select = 1;
                                    } elseif (date('H', $time) >= 17 && date('H', $time) <= 23) {
                                        $select = 2;
                                    } elseif (date('H', $time) >= 0 && date('H', $time) < 8) {
                                        $select = 3;
                                    }
                                    ?>
                                    <div class="info__text info__text-1 <?= ($select == 1) ? '' : 'hide' ?>">
                                        Вы сможете забрать заказ в выбранном Вами магазине <span
                                                class="address-pickup"><b><?= $selectStore ?></b></span> при получении
                                        СМС
                                        сообщения
                                        о готовности выдачи, но не ранее чем через 2 часа от оформления заказа.<br>
                                        <span class="red">Важно</span> - заказ будет храниться в магазине до
                                        <b>21:00 <?= date('d.m.Y') ?></b>.
                                    </div>
                                    <div class="info__text info__text-2 <?= ($select == 2) ? '' : 'hide' ?>">
                                        Вы сможете забрать заказ в выбранном Вами магазине <span
                                                class="address-pickup"><b><?= $selectStore ?></b></span> при получении
                                        СМС
                                        сообщения
                                        о готовности выдачи, но не ранее чем
                                        <b>10:00 <?= date('d.m.Y', strtotime(date('d.m.Y') . "+1 days")) ?></b>.<br>
                                        <span class="red">Важно</span> - заказ будет храниться в магазине до
                                        <b>21:00 <?= date('d.m.Y', strtotime(date('d.m.Y') . "+1 days")) ?></b>.
                                    </div>
                                    <div class="info__text info__text-3 <?= ($select == 3) ? '' : 'hide' ?>">
                                        Вы сможете забрать заказ в выбранном Вами магазине <span
                                                class="address-pickup"><b><?= $selectStore ?></b></span> при получении
                                        СМС
                                        сообщения
                                        о готовности выдачи, но не ранее чем <b>10:00 <?= date('d.m.Y') ?></b>.<br>
                                        <span class="red">Важно</span> - заказ будет храниться в магазине до
                                        <b>21:00 <?= date('d.m.Y') ?></b>.
                                    </div>

                                    <p><?= GetMessage("SOA_TEMPL_ORDER_SUC2_2", array("#ORDER_ID#" => $arResult["ORDER_ID"])) ?></p>

                                <? endif; ?>

                                <p><? //= GetMessage("SOA_TEMPL_ORDER_SUC1", array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?></p>

                                <? if (!empty($arResult["PAY_SYSTEM"])) {
                                    if ($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] != CSiteUtils::$onlinePayment
                                        && !in_array($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"], CSiteUtils::$onlinePayments)) {

                                        if ($_COOKIE['DELIVERY'] == 'PICKUP' && $arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] == 1) {
                                            $arResult["PAY_SYSTEM"]["NAME"] = 'Оплата наличными';
                                        }

                                        if ($_COOKIE['DELIVERY'] == 'PICKUP' && $arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] == 2) {
                                            $arResult["PAY_SYSTEM"]["NAME"] = 'Банковской картой в пункте выдачи';
                                        }
                                        ?>
                                        <p><?= GetMessage("SOA_TEMPL_PAY") ?>
                                            : <?= $arResult["PAY_SYSTEM"]["NAME"] ?></p>
                                        <?
                                    } ?>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <? if ((int)$arResult['ORDER']['DELIVERY_ID'] === (int)CSiteUtils::$petromostPickup): ?>
                <div class="order-after-content-block" id="info-pickup-zone"
                     style="<?= strlen($orderPayment['PAY_VOUCHER_NUM']) == 0 ? 'display: none' : '' ?>">
                    <div class="order-after-content">
                        <div class="img">
                            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/selfservice.png" alt="">
                        </div>
                        <div class="order-after-content__head">
                            <p>
                                При получении заказа в магазине - найдите островок выдачи заказов
                                в торговом зале, нажмите на кнопку вызова персонала, назовите
                                сотруднику магазина номер вашего заказа - <b>№<?= $arResult['ORDER_ID'] ?></b>
                            </p>
                        </div>
                    </div>
                </div>
            <? endif; ?>
            <div class="order-after-content">
                <div>
                    <div id="result" style="display:none"></div>

                    <? if (($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] == CSiteUtils::$onlinePayment
                            || in_array($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"], CSiteUtils::$onlinePayments))
                        && strlen($orderPayment['PAY_VOUCHER_NUM']) == 0) { ?>
                        <div class="order-after-content__head no_pay_text">
                            <div class="order-after-content__head-ico">
                                <img src="/bitrix/templates/main_adaptive_new/components/custom/sale.order.ajax/order_new/ico/green-galochka.png"
                                     alt="">
                            </div>
                            <div class="order-after-content__head-text">
                                <p class="pay_text">
                                    Обратите внимание, заказ без оплаты может быть аннулирован в течение 30 минут. <a
                                            href="/delivery-and-payment/">Подробнее о условиях оплаты</a>
                                </p>
                            </div>
                        </div>
                    <? }
                    ?>
                    <? if ($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"] == CSiteUtils::$onlinePayment
                        || in_array($arResult["PAY_SYSTEM"]["PAY_SYSTEM_ID"], CSiteUtils::$onlinePayments)) { ?>
                        <? if ($USER->IsAdmin()): ?>

                    <? endif; ?>
                        <p><?= $arResult["PAY_SYSTEM"]["BUFFERED_OUTPUT"] ?></p>
                    <? } ?>
                    <div class="order-complete-image mod-mobile-only">
                        <div class="order-complete-image__img">
                            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/order-after.png" alt="Спасибо за заказ">
                        </div>
                    </div>
                </div>
            </div>
            <!--        <img src="/bitrix/templates/main_adaptive_new/assets/img/order-after.png" alt="Спасибо за заказ">-->

        </div>

        <? ob_start(); ?>
        <div class="order-after-image mod-not-mobile">
            <div class="order-after-image__img">
                <? if ((int)$arResult['ORDER']['DELIVERY_ID'] === (int)CSiteUtils::$petromostPickup): ?>
                    <img class="pickup-img" src="<?= $templateFolder ?>/ico/order-after-2.png" alt="Спасибо за заказ">
                <? else: ?>
                    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/order-after.png" alt="Спасибо за заказ">
                <? endif; ?>
            </div>
        </div>
        <?
        $html = ob_get_contents();
        ob_end_clean();
        $APPLICATION->AddViewContent('footer', $html);
        ?>

        <?
    } else {
        ?>
        <b><?= GetMessage("SOA_TEMPL_ERROR_ORDER") ?></b><br/><br/>

        <table class="sale_order_full_table">
            <tr>
                <td>
                    <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ORDER_ID"])) ?>
                    <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1") ?>
                </td>
            </tr>
        </table>
        <?
    }
} else {
    ?>
    <b><?= GetMessage("SOA_TEMPL_ERROR_ORDER") ?></b><br/><br/>

    <table class="sale_order_full_table">
        <tr>
            <td>
                <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ORDER_ID"])) ?>
                <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1") ?>
            </td>
        </tr>
    </table>
    <?
}
?>
