<?

use Bitrix\Main\Grid\Declension;

$declension = new Declension('товар', 'товара', 'товаров');

//if($USER->isAdmin())
  //echo '<pre>'; print_r($arResult['BASKET_ITEMS']); echo '</pre>';
global $USER;
?>
<div class="checkout-categories-item js-checkout-category <?= !$_SESSION['CARD_ACTIVE'] ? 'closed' : '' ?>"
     data-category="basket">
    <div class="checkout-categories-item-head js-checkout-head">
        <div class="checkout-categories-item-head__title">
            <?= $number;
            $number++ ?>. Проверьте список товаров
        </div>
        <div class="checkout-categories-item-head__text">
            <?= sizeof($arResult["BASKET_ITEMS"]) ?> <?= $declension->get(sizeof($arResult["BASKET_ITEMS"])) ?>
        </div>
        <div class="checkout-show-toggle checkout-show-toggle_down">
            <span>развернуть</span>
            <i class="fas fa-chevron-down"></i>
        </div>

        <i class="far fa-check-circle check-ok"></i>
    </div>


<?

?>
            <div class="delivery-block__bottom">
                <div class="checkout-categories-item-head__text">
                    <?= sizeof($arResult["BASKET_ITEMS"]) ?> <?= $declension->get(sizeof($arResult["BASKET_ITEMS"])) ?>
                </div>
                <div class="checkout-aside-details__price basket_full_price_wd" id="full_summ">
                <?= $arResult['ORDER_TOTAL_PRICE_FORMATED'] ?>
            </div>
        </div>



    <div class="checkout-categories-item-body" id="basket-items">
        <? foreach ($arResult['PRODUCTS_BY_SECTIONS'] as $sectionId => $arItems): ?>
            <div class="profile-content-history-more__category"><?= $arResult['SECTION'][$sectionId] ?></div>
            <? foreach ($arItems as $arItem):
                $product = $arResult['PRODUCTS'][$arItem['PRODUCT_ID']];
                $pic = file_exists($_SERVER['DOCUMENT_ROOT'] . $arItem['DETAIL_PICTURE']['src']) ? $arItem['DETAIL_PICTURE']['src'] : SITE_TEMPLATE_PATH . "/img/default-mini.jpg";
                if ($arItem['NEW_DETAIL_PICTURE'])
                    $pic = $arItem['DETAIL_PICTURE'];

                if (strlen($pic) == 0)
                    $pic = SITE_TEMPLATE_PATH . "/img/default-mini.jpg";

                if (!$arItem['CUSTOM_PRICE']) {
                    $product['DIFF'] = $product['CART_PRICE'] = 0;
                }
				//print_r($product['CASHBACK']);
				//print_r($product['CASHBACK_DATE']);
                //print_r($arItem);
                // echo print_r($product,1);
				//$db_props = CIBlockElement::GetProperty(43, $arItem['PRODUCT_ID'], array("sort" => "asc"), Array("CODE"=>"FORUM_TOPIC_ID"))->Fetch();
                ?>
                <div class="profile-content-history-more-row" id="item_<?=$arItem['ID']?>" <?=$arItem['IS_PACKAGE'] ? 'data-entity="package"' : ''?>>
                    <div class="profile-content-history-more-left">
                        <?if($product["CASHBACK"] > 0 && $product['CASHBACK_DATE'] > date('d.m.Y')): ?>
                            <div class="cashback order-cashback">
                                <div class="cashback-left">
                                    <p>Кэшбэк <?=$product["CASHBACK"]?>%</p>
                                </div>
                            </div>
                        <? endif;?>
                        <div class="profile-content-history-more__img"
                             style="background-image: url(<?= str_replace(' ', '%20', $pic) ?>);">
                        </div>
                    </div>
                    <div class="profile-content-history-more-left">
                        <div class="profile-content-history-more__description">
                            <p class="cart-new-category-item-info__name"><?= $arItem['NAME'] ?>
                              <?if($arItem['IS_PACKAGE']):?>
                               x <span class="js-pack-quant"><?=round($arItem['QUANTITY'])?></span><?=$arResult['MEASURE'][$arItem['PRODUCT_ID']]['MEASURE']['SYMBOL']?>
                               <span class="package-info">*Количество упаковки рассчитано автоматически</span>
                              <?endif;?>
                            </p>
                            <? if ($product['CART_PRICE'] > 0): ?>
                                <div class="cart-new-category-item-info__tag-sale">..
                                    <div class="cart-new-category-item-info__tag-sale-left">
                                        Цена по карте
                                    </div>
                                    <div class="cart-new-category-item-info__tag-sale-right">
                                        -<?= $product['PERCENT'] ?>%
                                    </div>
                                </div>
                            <? elseif ($product['OLD_PRICE'] > 0): ?>
                                <div class="cart-new-category-item-info__tag">
                                    Только неделю
                                </div>
                            <? endif; ?>
                            <?/*//Вывод свойств товара
                            foreach ($arItem['PROPS'] as $prop):
                                switch($prop['CODE']):
                                    case 'ITEM_OPTION':
                                    case 'ITEM_OPTION_COMMENT':?>
                                    <p class="cart-new-category-item-info__name props">- <?=$prop['VALUE']?></p>
                                    <?break;
                                endswitch;
                            endforeach*/?>
                          <?  foreach ($arItem['PROPS'] as $prop):
                            switch ($prop['CODE']):
                            case 'ITEM_OPTION':
                            if ($prop['VALUE'] !== 'Позвонить, если товар отсутствует'):
                            ?>
                            <!----p class="cart-new-category-item-info__name props">- </*?=$prop['VALUE']*/?></p--->
                            <?endif;
                            break;
                            case 'ITEM_OPTION_COMMENT':
                            
                            ?>
                            <p class="cart-new-category-item-info__name props"><?=$prop['VALUE']?></p>
                            <?
                            break;							
                            case 'ITEM_OPTION_COMMENT':
                                break;
                                endswitch;
                                endforeach?>


                        </div>
                    </div>
                    <div class="profile-content-history-more-options">
                        <? if (!$arItem['IS_PACKAGE']):
                            //$unitView = $arItem['we'] ? 'кг.' : 'шт.';
                            $unitView = $arResult['MEASURE'][$arItem['PRODUCT_ID']]['MEASURE']['SYMBOL'];
                            $unit = ($arItem['we']) ? 'weight' : 'piece';
                            ?>
                            <div class="item-card__buttons">
                                <span class="item-card__btn mod-minus js-minus" data-id="<?= $arItem['PRODUCT_ID'] ?>" data-basket-id="<?= $arItem['ID'] ?>">-</span>
                                <input class="item-card__counter js-input" type="tel" value="<?=$arItem['QUANTITY']?>" data-max="12"
                                       readonly="" data-id="<?= $arItem['PRODUCT_ID'] ?>" id="quantity_<?= $arItem['PRODUCT_ID'] ?>"
                                       data-ratio="<?= $arResult['MEASURE'][$arItem['PRODUCT_ID']]['RATIO'] ?>"
                                       data-unit="<?= $unit ?>">
                                <span class="item-card__unit"> /<?= $arResult['MEASURE'][$arItem['PRODUCT_ID']]['MEASURE']['SYMBOL'] ?></span>
                                <span class="item-card__btn mod-plus js-plus" data-id="<?= $arItem['PRODUCT_ID'] ?>" data-basket-id="<?= $arItem['ID'] ?>">+</span>
                            </div>
                        <? endif; ?>
                    </div>
                    <div class="profile-content-history-more-price">
                        <? if ($product['DIFF'] > 0 && $_SESSION['CARD_ACTIVE']): ?>
                            <div class="profile-content-history-more__discount" id="item_diff_<?=$arItem['ID']?>">
                                Скидка: <?= CCurrencyLang::CurrencyFormat($product['DIFF'], $arItem['CURRENCY']) ?></div>
                        <? endif; ?>
                        <div class="profile-content-history-more__price<?if($arItem['IS_PACKAGE']):?> js-pack-price<?endif;?>" id="price_<?=$arItem['ID']?>">
                            <?= CCurrencyLang::CurrencyFormat(($arItem['QUANTITY'] * $arItem['PRICE']), $arItem['CURRENCY']) ?>
                        </div>

                            <!-- Место для отображения значения из textarea -->
                            <p id="additional_comment"></p>




                    </div>
                </div>
            <? endforeach; ?>
        <? endforeach; ?>
	    <?
		    global $APPLICATION;?>
            <div class="checkout-categories-item-body-about-row">
			    <?$APPLICATION->IncludeComponent('leeft:how.proceed.products','', array(
				    'IBLOCK_ID' => 82,
				    'INPUT_NAME' => 'ORDER_PROP_121',
				    'TITLE' => "Как поступить если выбранный товар закончился?"
			    ))?>
            </div>
	    
        <div class="checkout-categories-item-bottom">
            <div class="checkout-categories-item-bottom-left">
                <a href="/personal/" class="checkout-categories-item-bottom__btn">&lt;&lt; Вернуться в корзину</a>
            </div>
            <div class="checkout-categories-item-bottom-right">
                <a href="/" class="checkout-categories-item-bottom__btn js-checkout-next">Далее &gt;&gt;</a>
            </div>
        </div>
    </div>

    <div class="checkout-categories-item-head checkout-categories-item-head-bottom">
        <div class="checkout-categories-item-head__title">
            1. Проверьте список товаров
        </div>
        <div class="checkout-categories-item-head__text">
            <?= sizeof($arResult["BASKET_ITEMS"]) ?> <?= $declension->get(sizeof($arResult["BASKET_ITEMS"])) ?>
        </div>
        <div class="checkout-show-toggle checkout-show-toggle_up">
            <span>свернуть</span>
            <i class="fas fa-chevron-up"></i>
        </div>
        <i class="far fa-check-circle check-ok"></i>
    </div>
</div>