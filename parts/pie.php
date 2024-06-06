<div class="checkout-categories-item js-checkout-category js-pies complete">
    <div class="checkout-categories-item-head js-checkout-head">
        <div class="checkout-categories-item-head__title">Пироги</div>
        <div class="checkout-categories-item-head__arrow"></div>
    </div>
    <div class="checkout-categories-item-body">
        <div class="checkout-categories-item-pies">
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
            foreach ($arResult['PIES'] as $item) {
                ?>
                <div class="checkout-categories-item-pie">
                    <div class="checkout-categories-item-pie-left">
                        <div class="checkout-categories-item-pie__img">
                            <img src="<?= $item['DETAIL_PICTURE']['src'] ?>" alt="Пирог ржаной грибная лукерья">
                        </div>
                    </div>
                    <div class="checkout-categories-item-pie-right">
                        <p class="checkout-categories-item-pie__title">
                            <?= $item['NAME'] ?><span
                                    class="checkout-categories-item-pie__weight"> / Вес: <?= $item['VES'] ?> кг</span>
                        </p>
                        <p class="checkout-categories-item-pie__subtitle"><?= $item['COMPOSITION'] ?></p>
                        <p class="checkout-categories-item-pie__info"><?= $item['CALORIE'] ?> </p>
                        <p class="checkout-categories-item-pie__numbers">
                            <span class="checkout-categories-item-pie__amount"><?= $item['QUANTITY'] ?> шт</span>
                            ;
                            <span class="checkout-categories-item-pie__price"><?= CCurrencyLang::CurrencyFormat($item['QUANTITY'] * $item['PRICE'], 'RUB') ?></span>
                        </p>
                    </div>
                </div>
                <?
            } ?>
        </div>

        <div class="checkout-categories-item-pies__title">
            Покупатель
        </div>
        <div class="checkout-categories-item-body-about-row">
            <label class="profile-content-form__input">
                <input class="inp" type="text" name="pie-fio" autocomplete="off" placeholder=" " value="<?= $fio["VALUE"] ?>">
                <span>ФИО</span>
            </label>
            <label class="profile-content-form__input">
                <input class="inp" type="text" name="pie-phone" autocomplete="off" placeholder=" " value="<?= $phone["VALUE"] ?>">
                <span>Телефон</span>
            </label>
        </div>
        <div class="checkout-categories-item-body-about-row__title">
            Адрес доставки
        </div>
        <div class="checkout-categories-item-body-about-row">
            <?
            $address = explode('д.', $_COOKIE['BITRIX_ADRESS']);
            ?>
            <label class="profile-content-form__input">
                <input class="inp pie-address-value" type="text" autocomplete="off"
                       value="<?//= $address[0] ?>" id="pie-street-checkout" name="pie-street_se"
                       placeholder=" ">
                <span>Улица</span>
            </label>
        </div>
        <div class="checkout-categories-item-body-about-row">
            <label class="profile-content-form__input mod-house">
                <input class="inp pie-address-value" type="text" autocomplete="off"
                       value="<?//= $arResult['MAIN_ADDRESS']['house'] ?>" id="pie-house-checkout" name="pie-house-checkout"
                       placeholder=" ">
                <span>Дом</span>
            </label>
            <label class="profile-content-form__input mod-building">
                <input class="inp pie-address-value" type="text" autocomplete="off"
                       value="<?//= $arResult['MAIN_ADDRESS']['korpus'] ?>" id="pie-korpus-checkout" name="pie-korpus-checkout"
                       placeholder=" ">
                <span>Корпус</span>
            </label>
            <label class="profile-content-form__input mod-porch" id="pie-porch-checkout">
                <input class="inp pie-address-value" type="text"
                       maxlength="250" value="<?//= $porch["VALUE"] ?>"
                       name="pie-porch-checkout" id="pie-porch-checkout">
                <span>Подъезд</span>
            </label>
            <label class="profile-content-form__input mod-floor" id="pie-floor-checkout">
                <input class="inp pie-address-value <?//= $floor['REQUIED'] == 'Y' ? 'required' : '' ?>" type="text"
                       maxlength="250"
                       size="<?//= $floor["SIZE1"] ?>" value="<?//= $floor["VALUE"] ?>"
                       name="pie-floor-checkout" id="pie-floor-checkout">
                <span>Этаж</span>
            </label>
            <label class="profile-content-form__input mod-appartment">
                <input class="inp pie-address-value" type="text"
                       autocomplete="off" placeholder=" " value="<?//= $arResult['MAIN_ADDRESS']['flat'] ?>"
                       id="pie-flat-checkout" name="pie-flat-checkout">
                <span>Квартира</span>
            </label>
            <label class="profile-content-form__input mod-remark">
                <input class="inp pie-address-value" type="text" autocomplete="off"
                       value="<?//= $arResult['MAIN_ADDRESS']['comment'] ?>"
                       placeholder=" " id="pie-comment-checkout" name="pie-comment-checkout">
                <span>Примечание</span>
            </label>
        </div>

        <div class="checkout-categories-item-pies-options">
            <div class="checkout-categories-item-pies-datepicker js-datepicker"></div>
            <div class="checkout-categories-item-pies-order">
                <div class="checkout-categories-item-pies-time">
                    <div class="checkout-categories-item-pies-time-head">
                                                    <span class="checkout-categories-item-pies-time-head__text">
                                                        Время доставки
                                                    </span>
                    </div>
                    <div class="checkout-categories-item-body-about-time-body">
                        <div class="checkout-categories-item-body-about-time-table js-pies-time-tab active">
                            <label class="checkout-categories-item-body-about-time-option">
                                <input name="pie-time" type="radio"
                                       value="09:00 - 12:00" <? //= (int)date('H') > 8 ? 'disabled' : '' ?>>
                                <span>09:00 - 12:00</span>
                            </label>
                            <label class="checkout-categories-item-body-about-time-option">
                                <input name="pie-time" type="radio"
                                       value="12:00 - 15:00" <? //= (int)date('H') > 11 ? 'disabled' : '' ?>>
                                <span>12:00 - 15:00</span>
                            </label>
                            <label class="checkout-categories-item-body-about-time-option">
                                <input name="pie-time" type="radio"
                                       value="15:00 - 18:00" <? //= (int)date('H') > 14 ? 'disabled' : '' ?>>
                                <span>15:00 - 18:00</span>
                            </label>
                            <label class="checkout-categories-item-body-about-time-option">
                                <input name="pie-time" type="radio"
                                       value="18:00 - 21:00" <? //= (int)date('H') > 17 ? 'disabled' : '' ?>>
                                <span>18:00 - 21:00</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="checkout-categories-item-pies-input">
                    <label class="profile-content-form__input">
                        <input class="inp" type="text" name="pie-comment" autocomplete="off" placeholder=" ">
                        <span>Комментарий к заказу</span>
                    </label>
                </div>
                <div class="checkout-categories-item-pies-acception">
                    <div class="checkout-acception">
                        <label class="js-accept-pies">
                            <input type="checkbox" class="checkbox" name="pie-confirmorder" value="Y">
                            <span class="checkbox"></span><span>Я принимаю условия пользовательского соглашения</span>
                        </label>
                    </div>
                </div>
                <div class="checkout-categories-item-pies-button">
                    <input type="hidden" value="" name="pie-date" id="pie-date">
                    <input type="hidden" value="" name="pie-address" id="pie-address">
                    <a class="checkout-categories-item-pies-button__btn js-pies-checkout-button active" href="#">Заказать пироги</a>
                </div>
            </div>
        </div>
    </div>

</div>