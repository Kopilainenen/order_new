<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();


$number = 1;
if ($_COOKIE['ADDRESS_ID'] == 0) {
    LocalRedirect('/personal/');
}
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
<a name="order_fform"></a>
<div id="order_form_div" class="order-checkout">
    <NOSCRIPT>
        <div class="errortext"><?= GetMessage("SOA_NO_JS") ?></div>
    </NOSCRIPT>
    <?
        if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") {
            if (!empty($arResult["ERROR"])) {
                foreach ($arResult["ERROR"] as $v)
                    echo ShowError($v);
            } elseif (!empty($arResult["OK_MESSAGE"])) {
                foreach ($arResult["OK_MESSAGE"] as $v)
                    echo ShowNote($v);
            }
            ?>
    <script>
        $(document).ready(function () {
            openPopup('.popup-auth-2020');
        });
    </script>
    <?
        //include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/auth.php");
        } else {
        if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y") {
        if (strlen($arResult["REDIRECT_URL"]) > 0) {
        ?>
    <script>
        window.top.location.href = '<?= CUtil::JSEscape($arResult["REDIRECT_URL"]) ?>';
    </script>
    <?
        die();
        } else {
            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/confirm.php");
        }
        } else {

        if ($_POST["is_ajax_post"] != "Y") {
        ?>
    <form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM">
        <div id="order_form_content">
            <?
                    } else {
                        $APPLICATION->RestartBuffer();
                    }
                    ?>
            <?= bitrix_sessid_post() ?>
            <?


                    if (!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
                        foreach ($arResult["ERROR"] as $v)
                            echo ShowError($v);
                        ?>
            <script>
                top.BX.scrollToNode(top.BX('ORDER_FORM'));
            </script>
            <?
                    }

                    if (count($arResult["PERSON_TYPE"]) > 1) {
                        ?>
            <b><?= GetMessage("SOA_TEMPL_PERSON_TYPE") ?></b>
            <table class="sale_order_full_table">
                <tr>
                    <td>
                        <?
                                    foreach ($arResult["PERSON_TYPE"] as $v) {
                                        ?><input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE"
                            value="<?= $v["ID"] ?>" <? if ($v["CHECKED"]=="Y" ) echo " checked=\" checked\""; ?>
                        onClick="submitForm()"> <label for="PERSON_TYPE_<?= $v["ID"] ?>"><?= $v["NAME"] ?></label><br />
                        <?
                                    }
                                    ?>
                        <input type="hidden" name="PERSON_TYPE_OLD"
                            value="<?= $arResult["USER_VALS"]["PERSON_TYPE_ID"] ?>">
                    </td>
                </tr>
            </table>
            <br /><br />
            <?
                    } else {
                        if (IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0) {
                            ?>
            <input type="hidden" name="PERSON_TYPE" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>">
            <input type="hidden" name="PERSON_TYPE_OLD" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>">
            <?
                        } else {
                            foreach ($arResult["PERSON_TYPE"] as $v) {
                                ?>
            <input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?= $v["ID"] ?>">
            <input type="hidden" name="PERSON_TYPE_OLD" value="<?= $v["ID"] ?>">
            <?
                            }
                        }
                    }
                    ?>
            <div class="checkout">
                <div class="checkout-categories">
                    <?

                            $showPie = sizeof($arResult['PIES']) > 0 && $USER->IsAdmin() && SITE_ID == 's1';
                            if (!$_SESSION['CARD_ACTIVE'])
                                include 'parts/card.php';

                            if (sizeof($arResult["BASKET_ITEMS"]) > 0) {
                                include 'parts/basket.php';
								//include 'parts/pribory.php';
                                include 'parts/delivery.php';
                                include 'parts/payment.php';
                                include 'parts/props.php';
                            }
                            ?>
                </div>

                <div class="checkout-bottom">
                    <a href="/catalog/" class="checkout-bottom__back">В каталог</a>
                    <? if (strlen($_COOKIE['BITRIX_ADRESS']) > 0 || SITE_ID == 's2'): ?>
                    <a style="display: none"
                        class="checkout-bottom__btn js-checkout-button checkout-btn
                                    <?= $_REQUEST['confirmorder'] == 'Y' && $_REQUEST['confirmperson'] == 'Y' ? ' active' : '' ?>"
                        href="javascript:void(0)">Оформить
                        заказ</a>
                    <? endif; ?>
                </div>
            </div>
            <?
                    if ($_POST["is_ajax_post"] != "Y") {
                    ?>
        </div>
        <?

                $freeDeliverySum = CH::getServiceData('free_delivery_sum');
                // if ($arResult['ORDER_PRICE'] < $freeDeliverySum) {
                ?>
        <input type="hidden" name="SLOT_INDEX" id="SLOT_INDEX" value="<?=$_REQUEST['SLOT_INDEX']?>">
        <input type="hidden" name="SLOT_ID" id="SLOT_ID" value="<?=$_REQUEST['SLOT_ID']?>">
        <input type="hidden" name="profile_change" id="profile_change" value="N">
        <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
        <input type="hidden" name="save" value="Y">
        <br /><br />
    </form>
    <? if ($arParams["DELIVERY_NO_AJAX"] == "N"): ?>
    <script language="JavaScript" src="/bitrix/js/main/cphttprequest.js"></script>
    <script language="JavaScript"
        src="/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.js"></script>
    <? endif; ?>
    <?
        }
        else {
            ?>
    <?
            die();
        }
        }
        }

        ?>
</div>
<script>
    window.orderPage = true;
</script>
<? $this->setViewTarget('checkout_aside') ?>
<aside class="content__right content__right-checkout">
    <div class="checkout-aside">
        <div class="checkout-aside-details">
            <div class="checkout-aside-details-top">
                <div class="checkout-aside-details-row">
                    <div class="checkout-aside-details__text">
                        Товаров на:
                    </div>
                    <div class="checkout-aside-details__price basket_full_price">
                        <?= $arResult['ORDER_PRICE_FORMATED'] ?>
                    </div>
                </div>
                <div class="checkout-aside-details-row" style="display: none">
                    <div class="checkout-aside-details__text">
                        Доставка:
                    </div>
                    <div class="checkout-aside-details__price delivery_price" id="delivery_price">
                        <?= $arResult['DELIVERY_PRICE_FORMATED'] ?>
                    </div>
                </div>
            </div>
            <div class="checkout-aside-details-bottom">
                <div class="checkout-aside-details-row" style="display: none">
                    <div class="checkout-aside-details__text">
                        Итого к оплате:
                    </div>
                    <div class="checkout-aside-details__price basket_full_price_wd" id="full_summ">
                        <?= $arResult['ORDER_TOTAL_PRICE_FORMATED'] ?>
                    </div>
                </div>
            </div>
        </div>
        <? if (strlen($_COOKIE['BITRIX_ADRESS']) > 0 || SITE_ID == 's2'): ?>
        <!--div class="checkout-aside-button">
            <a class="checkout-aside-button__btn js-checkout-button checkout-btn" href="javascript:void(0)">Оформить
                заказ</a>
        </div-->
        <? endif; ?>
    </div>
</aside>
<? $this->endViewTarget() ?>
<?

?>