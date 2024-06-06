<div class="checkout-categories-item js-checkout-category" data-category="card">
    <div class="checkout-categories-item-head js-checkout-head">
        <div class="checkout-categories-item-head__title">
            <?= $number;
            $number++ ?>. Карта снегири
        </div>
        <i class="far fa-check-circle check-ok"></i>
    </div>
    <div class="checkout-categories-item-body">
        <div class="checkout-categories-item-body__text">
            Введите номер карты Снегири для участия в акции «Цена по карте»
        </div>
        <div class="profile-content-banner-form" autocomplete="off">
            <label class="profile-content-banner__input js-snegiri-input">
                <input class="inp" type="text" name="CARD_NUMBER" autocomplete="off"
                       placeholder="Номер карты Снегири:"
                       value="<?= strlen($_SESSION['ADMIN_CARD']) > 0 ? $_SESSION['ADMIN_CARD'] : $_SESSION['CARD'] ?>">
            </label>
            <a href="/" class="profile-content-banner__button js-snegiri-button card_btn">Применить
                карту</a>
        </div>
    </div>
    <a href="javascript:void(0)" class="checkout-categories-item__next js-checkout-next">Далее >></a>
</div>